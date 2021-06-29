<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch extends CI_Controller
{
    private $_diasdasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/unidadehospitalar_model');
        $this->load->model('cemerge/setor_model');
        $this->load->model('cemerge/profissional_model');
        $this->load->model('cemerge/frequenciaassessus_model');
        $this->load->model('cemerge/frequencia_model');
        $this->lang->load('admin/escalas');
        $this->load->helper('messages');
    }

    public function index()
    {
        echo('Controller para processamento em lote');
        exit;
    }

    public function processarescala($hash, $unidadehospitalar_id, $setor_id, $datainicial = null)
    {
        $unidadehospitalar_id = (int)$unidadehospitalar_id;
        $setor_id = (int)$setor_id;
        $datainicial = $datainicial ? $datainicial : date('Y-m-d');
        if ($setor_id == 0) {
            $setor_id = null;
        }

        // Validação de hash para controle de acesso Cmg@2020
        if (isset($hash)) {
            if ($hash != "94672914a632eab3db28854925a08f42") {
                die("Acesso não autorizado");
            }
        } else {
            die("Acesso não autorizado");
        }

        if ($datainicial && $unidadehospitalar_id) {
            $data_inicial_escala = date('Y-m-d', strtotime($datainicial . ' -1 day'));
            $data_final_escala = date('Y-m-d', strtotime($datainicial));

            $escalas = $this->escala_model->get_escala_consolidada_a_processar($unidadehospitalar_id, $setor_id, $data_inicial_escala, $data_final_escala);
            //var_dump($escalas); exit;

            foreach ($escalas as $escala) {
                // Plantões manhã e tarde do dia solicitado
                if ($escala->dataplantao == $data_final_escala and $escala->dataplantao == $escala->datafinalplantao) {
                    if (is_null($escala->frequencia_entrada_id)) {
                        $entradas = $this->escala_model->get_frequencia_por_escala($escala->dataplantao, $escala->horainicialplantao, $escala->id_profissional, $unidadehospitalar_id);
                        //echo ('Entradas');
                        //var_dump($entradas); exit;
                        if ($entradas) {
                            if (sizeof($entradas) > 1) {
                                $entrada = $entradas[1]; // Acho que no caso de prescrição deve pegar a primeira[0] para entrada e a segunda[1] para saída
                            } else {
                                $entrada = $entradas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_entrada_id' => $entrada->frequencia_id]);
                            $this->frequencia_model->update($entrada->frequencia_id, ['escala_id' => $escala->escala_id, 'tipobatida' => 1, 'setor_id' => $escala->setor_id]);
                            echo('Frequencia de entrada atualizada<br>');
                        }
                    }
                    if (is_null($escala->frequencia_saida_id)) {
                        $saidas = $this->escala_model->get_frequencia_por_escala($escala->dataplantao, $escala->horafinalplantao, $escala->id_profissional, $unidadehospitalar_id);
                        //var_dump($saidas); exit;
                        if ($saidas) {
                            if (sizeof($saidas) > 1) {
                                $saida = $saidas[0]; // Acho que no caso de prescrição deve pegar a primeira[0] para entrada e a segunda[1] para saída
                            } else {
                                $saida = $saidas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_saida_id' => $saida->frequencia_id]);
                            $this->frequencia_model->update($saida->frequencia_id, ['escala_id' => $escala->escala_id, 'tipobatida' => 2]);
                            echo('Frequencia de saída atualizada<br>');
                        }
                    }
                }
                // Plantões noite do dia anterior
                if ($escala->dataplantao == $data_inicial_escala and $escala->horainicialplantao > $escala->horafinalplantao) {
                    if (is_null($escala->frequencia_saida_id)) {
                        $saidas = $this->escala_model->get_frequencia_por_escala($escala->datafinalplantao, $escala->horafinalplantao, $escala->id_profissional, $unidadehospitalar_id);
                        if ($saidas) {
                            if (sizeof($saidas) > 1) {
                                $saida = $saidas[0];
                            } else {
                                $saida = $saidas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_saida_id' => $saida->frequencia_id]);
                            $this->frequencia_model->update($saida->frequencia_id, ['escala_id' => $escala->escala_id, 'tipobatida' => 2]);
                            echo('Frequencia de saída atualizada<br>');
                        }
                    }
                }
                // Plantões noite do dia solicitado
                if ($escala->dataplantao == $data_final_escala and $escala->datafinalplantao > $escala->dataplantao and $escala->horainicialplantao > $escala->horafinalplantao) {
                    if (is_null($escala->frequencia_entrada_id)) {
                        $entradas = $this->escala_model->get_frequencia_por_escala($escala->dataplantao, $escala->horainicialplantao, $escala->id_profissional, $unidadehospitalar_id);
                        if ($entradas) {
                            if (sizeof($entradas) > 1) {
                                $entrada = $entradas[1];
                            } else {
                                $entrada = $entradas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_entrada_id' => $entrada->frequencia_id]);
                            $this->frequencia_model->update($entrada->frequencia_id, ['escala_id' => $escala->escala_id, 'tipobatida' => 1]);
                            echo('Frequencia de entrada atualizada<br>');
                        }
                    }
                }
            }
        } else {
            echo('Favor preencher todos os parâmetros da validação.');
            $this->session->set_flashdata('message', 'Favor preencher todos os parâmetros da validação.');
        }

        echo('Escalas processadas com sucesso.');
        exit;

        /* Redirect */
        //redirect('admin/escalas/processarescala', 'refresh');
    }

    public function processarescalaprescricao($hash, $unidadehospitalar_id, $datainicial = null)
    {
        $unidadehospitalar_id = (int)$unidadehospitalar_id;
        $datainicial = $datainicial ? $datainicial : date('Y-m-d');

        // Validação de hash para controle de acesso Cmg@2020
        if (isset($hash)) {
            if ($hash != "94672914a632eab3db28854925a08f42") {
                die("Acesso não autorizado");
            }
        } else {
            die("Acesso não autorizado");
        }

        if ($datainicial && $unidadehospitalar_id) {
            $data_inicial_escala = date('Y-m-d', strtotime($datainicial));
            $data_final_escala = $data_inicial_escala;

            $escalas = $this->escala_model->get_escala_prescricao_a_processar($unidadehospitalar_id, $data_inicial_escala, $data_final_escala);

            foreach ($escalas as $escala) {
                // Plantões manhã e tarde do dia solicitado
                if ($escala->dataplantao == $data_final_escala and $escala->dataplantao == $escala->datafinalplantao) {
                    if (is_null($escala->frequencia_entrada_id)) {
                        $batidas = $this->escala_model->get_frequencia_por_escala_prescricao($escala->dataplantao, $escala->id_profissional, $unidadehospitalar_id, $escala->setor_id);
                        if ($batidas) {
                            $entrada = $batidas[0];
                            $this->escala_model->update($escala->escala_id, ['frequencia_entrada_id' => $entrada->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($entrada->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 1]);
                        }
                    }
                    if (is_null($escala->frequencia_saida_id)) {
                        $batidas = $this->escala_model->get_frequencia_por_escala_prescricao($escala->dataplantao, $escala->id_profissional, $unidadehospitalar_id, $escala->setor_id);
                        if ($batidas) {
                            if (sizeof($batidas) > 1) {
                                $saida = $batidas[1];
                            } else {
                                $saida = $batidas[0];
                            }
                            $this->escala_model->update($escala->escala_id, ['frequencia_saida_id' => $saida->cd_ctl_frq]);
                            $this->frequenciaassessus_model->update($saida->cd_ctl_frq, ['escala_id' => $escala->escala_id, 'tipo_batida' => 2]);
                        }
                    }
                }
            }
        } else {
            echo('Favor preencher todos os parâmetros da validação.');
            $this->session->set_flashdata('message', 'Favor preencher todos os parâmetros da validação.');
        }

        echo('Escalas de prescrição processadas com sucesso.');
        exit;
    }

    public function processarbatidasmtmesmomedico($hash, $unidadehospitalar_id, $dataplantao, $recriar)
    {
        $unidadehospitalar_id = (int)$unidadehospitalar_id;
        $dataplantao = $dataplantao;
        $recriar = (int)$recriar;

        // Validação de hash para controle de acesso Cmg@2020
        if (isset($hash)) {
            if ($hash != "94672914a632eab3db28854925a08f42") {
                die("Acesso não autorizado");
            }
        } else {
            die("Acesso não autorizado");
        }

        if ($dataplantao && $unidadehospitalar_id && isset($recriar)) {
            // Apagar todas as batidas inseridas do tipo 3 e 4 deste dia nesta unidade
            if ($recriar == 1) {
                $where_entrada = [
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'date(datahorabatida)' => $dataplantao,
                    'tipobatida' => 3
                ];
                $where_saida = [
                    'unidadehospitalar_id' => $unidadehospitalar_id,
                    'date(datahorabatida)' => $dataplantao,
                    'tipobatida' => 4
                ];
                $this->frequencia_model->delete($where_entrada);
                $this->frequencia_model->delete($where_saida);
            }

            $plantoes_mt = $this->escala_model->get_plantoes_mt($unidadehospitalar_id, $dataplantao);

            foreach ($plantoes_mt as $k => $p) {
                $proximo_plantao = null;
                if (isset($plantoes_mt[$k+1])) {
                    $proximo_plantao = $plantoes_mt[$k+1];
                }
                if ($proximo_plantao) {
                    if ($p->id_profissional == $proximo_plantao->id_profissional
                        && $p->idsetor == $proximo_plantao->idsetor
                        && $p->horainicialplantao == '07:00:00'
                        && $proximo_plantao->horainicialplantao == '13:00:00'
                    ) {
                        $saida = [
                            'unidadehospitalar_id' => $p->idunidade,
                            'setor_id' => $p->idsetor,
                            'escala_id' => $p->id,
                            'profissional_id' => $p->id_profissional,
                            'datahorabatida' => $dataplantao . ' 13:00:00',
                            'tipobatida' => 4, // saída incluída pelo sistema
                        ];
                        $entrada = [
                            'unidadehospitalar_id' => $p->idunidade,
                            'setor_id' => $p->idsetor,
                            'escala_id' => $proximo_plantao->id,
                            'profissional_id' => $p->id_profissional,
                            'datahorabatida' => $dataplantao . ' 13:00:01',
                            'tipobatida' => 3, // entrada incluída pelo sistema
                        ];
                        $this->frequencia_model->insert($saida);
                        $this->frequencia_model->insert($entrada);
                    }
                }
            }
        } else {
            echo('Favor preencher todos os parâmetros da validação.');
        }

        echo('Batidas MT do mesmo profissional processadas com sucesso.');
        exit;
    }
}
