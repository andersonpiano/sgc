<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'profissionais', 'residentes', 'sac', 'sad');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->helper('number');
        $this->load->model('admin/dashboard_model');
        $this->load->model('cemerge/escala_model');
        $this->load->model('cemerge/passagemtroca_model');
    }

    public function index()
    {
        if (! $this->ion_auth->logged_in() or !$this->ion_auth->in_group($this->_permitted_groups)) {
            redirect('auth/login', 'refresh');
        } else {
            /* Title Page */
            $this->page_title->push(lang('menu_dashboard'));
            $this->data['pagetitle'] = $this->page_title->show();

            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            // Profissional logado
            $profissional = null;
            $setores_profissional = null;
            if ($this->ion_auth->in_group('profissionais')) {
                $this->load->model('cemerge/usuarioprofissional_model');
                $this->load->model('cemerge/profissional_model');
                $this->load->model('cemerge/setor_model');

                $userId = $this->ion_auth->user()->row()->id;
                if ($userId) {
                    $usuarioProfissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId]);
                    if (!empty($usuarioProfissional)) {
                        $profissional = $this->profissional_model->get_where(['id' => $usuarioProfissional[0]->profissional_id])[0];
                        $setores_profissional = $this->setor_model->get_setores_por_profissional($profissional->id);
                        $setores_profissional_arr = array();
                        foreach ($setores_profissional as $setor) {
                            array_push($setores_profissional_arr, $setor->id);
                        }
                        $setores_profissional_str = '(' . implode(', ', $setores_profissional_arr) . ')';
                    }
                }
            }

            /* Reset arrays */
            $this->data['plantoes_recebidos_confirmar'] = array();
            $this->data['plantoes_passados_confirmar'] = array();
            $this->data['trocas_propostas_confirmar'] = array();
            $this->data['trocas_recebidas_confirmar'] = array();
            $this->data['oportunidades'] = array();
            $this->data['justificativas'] = array();
            $this->data['cessoes'] = array();
            $this->data['trocas'] = array();
            $this->data['proximosplantoes'] = array();
            $this->data['count_users'] = 0;
            $this->data['count_professionals'] = 0;
            $this->data['count_hospitals'] = 0;
            $this->data['count_sectors'] = 0;

            /* Data */
            if ($this->ion_auth->is_admin()) {
                $this->data['count_users'] = $this->dashboard_model->get_count_record('users');
                $this->data['count_professionals'] = $this->dashboard_model->get_count_record('profissionais');
                $this->data['count_hospitals'] = $this->dashboard_model->get_count_record('unidadeshospitalares');
                $this->data['count_sectors'] = $this->dashboard_model->get_count_record('setores');
            }
            
            if ($this->ion_auth->in_group('profissionais')) {
                $this->data['plantoes_recebidos_confirmar']
                    = $this->escala_model->get_plantoes_recebidos_a_confirmar($profissional->id);
                $this->data['plantoes_passados_confirmar']
                    = $this->escala_model->get_plantoes_passados_a_confirmar($profissional->id);
                $this->data['trocas_propostas_confirmar']
                    = $this->escala_model->get_trocas_propostas_a_confirmar($profissional->id);
                $this->data['trocas_recebidas_confirmar']
                    = $this->escala_model->get_trocas_recebidas_a_confirmar($profissional->id);
                $this->data['oportunidades']
                    = $this->passagemtroca_model->get_sessoes_from_limbo(date('Y-m-d'), date('Y-m-d', strtotime('+30 days')), 0, $profissional->id);
                                        /*= $this->escala_model->get_oportunidades($profissional->id);*/
                                       // var_dump($this->data['oportunidades']); exit;
                $this->data['justificativas']
                    = $this->Escala_model->get_justificativas_a_confirmar($profissional->id);

                $this->data['cessoes'] = $this->getCessoes($profissional->id);
                $this->data['trocas'] = $this->getTrocas($profissional->id);
                $this->data['proximosplantoes'] = $this->getProximosPlantoes($profissional->id);
            }

            /* TEST */
            $this->data['url_exist'] = is_url_exist('http://www.cemerge.com.br'); //http://www.domprojects.com

            /* Load Template */
            $this->template->admin_render('admin/dashboard/index', $this->data);
        }
    }

    public function getCessoes($profissional_id)
    {
        //Você tem 1 cessão não confirmada
        return "Clique aqui para ver os plant&otilde;es cedidos &agrave; voc&ecirc;";
    }

    public function getTrocas($profissional_id)
    {
        //Você tem 3 trocas propostas
        return "Clique aqui para ver os plant&otilde;es ofertados como troca &agrave; voc&ecirc;";
    }

    public function getProximosPlantoes($profissional_id)
    {
        //Setor em 12/09 das 13h às 19h
        return "Clique aqui para ver seus pr&oacute;ximos plant&otilde;es";
    }
}
