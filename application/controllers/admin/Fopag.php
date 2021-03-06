<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Fopag extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_permitted_groups);

       /* Load :: Common */
       //$this->load->model('cemerge/Fopag_model');
       $this->lang->load('admin/fopag');

       /* Title Page */
       
       $this->page_title->push(lang('menu_fopag'));
       $this->data['pagetitle'] = '<li class="fa fa-money fa-2x"><bold> Folha de Pagamentos</bold></li>';

       /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_fopag'), 'admin/fopag');
    }

    public function index()
    {
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para listar as Especializações.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('auth/login', 'refresh');
        }
        
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $tipo_evento_select = $this->_get_tipo_evento();
        $eventos_select = $this->_get_eventos();
        $anos_select = $this->_get_anos();
        $meses_select = $this->_get_meses();
        $profissionais_select = $this->_get_profissionais();
        $tipos_folha_select = $this->_get_tipo_folha();
            
        $this->data['tipo_evento_select'] = array(
            'name'  => 'tipo_evento_select',
            'id'    => 'tipo_evento_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('tipo_evento_select'),
            'options' => $tipo_evento_select,
        );

        $this->data['eventos_select'] = array(
            'name'  => 'eventos_select',
            'id'    => 'eventos_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('eventos_select'),
            'options' => $eventos_select,
        );

        $this->data['anos_select'] = array(
            'name'  => 'anos_select',
            'id'    => 'anos_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('anos_select'),
            'options' => $anos_select,
        );

        $this->data['meses_select'] = array(
            'name'  => 'meses_select',
            'id'    => 'meses_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('meses_select'),
            'options' => $meses_select,
        );

        $this->data['profissionais_select'] = array(
            'name'  => 'profissionais_select',
            'id'    => 'profissionais_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('profissionais_select'),
            'options' => $profissionais_select,
        );

        $this->data['tipos_folha_select'] = array(
            'name'  => 'tipos_folha_select',
            'id'    => 'tipos_folha_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('tipos_folha_select'),
            'options' => $tipos_folha_select,
        );

        $this->template->admin_render('admin/fopag/index', $this->data);
    }

    private function _get_tipo_evento()
    {

        $tipo_evento_select = array(
            '' => 'Selecione o tipo de Evento',
            '1' => 'Crédito',
            '2' => 'Débito',
        );

        return $tipo_evento_select;
    }

    private function _get_tipo_folha()
    {
        
        $tipos_folha_select = array(
            '' => 'Selecione o tipo de Folha',
            '1' => 'Normal',
            '2' => 'Complementar',
        );

        return $tipos_folha_select;
    }

    private function _get_eventos()  {
        
        $this->load->model('cemerge/Evento_model');

        $eventos = $this->Evento_model->get_all();

        $eventos_select = array(
            '' => 'Selecione um Evento',
        );
        foreach ($eventos as $evento) {
            $eventos_select[$evento->id] = $evento->nome;
        }
        //var_dump($categorias); exit;
        return $eventos_select;
    }

    private function _get_profissionais()  {
        
        $this->load->model('cemerge/profissional_model');

        $profissionais = $this->profissional_model->get_all();

        $profissionais_select = array(
            '' => 'Selecione um profissional',
        );
        foreach ($profissionais as $profissional) {
            $profissionais_select[$profissional->id] = $profissional->nome;
        }
        //var_dump($categorias); exit;
        return $profissionais_select;
    }

    private function _get_anos()  {
        
        $anos_select = array(
            '' => 'Selecione um Ano',
            '2021' => '2021',
        );

        return $anos_select;
    }

    private function _get_meses()  {
        
        $meses_select = array(
            '' => 'Selecione um Mês',
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
            '13' => '13º Salário',
        );

        return $meses_select;
    }

    public function ajax_listar_profissionais() {

    if (!$this->input->is_ajax_request()) {
        exit("Nenhum acesso de script direto permitido!");
    }
        
    $this->load->model("cemerge/Profissional_model");
    $profissionais = $this->Profissional_model->get_datatable();

    $data = array();
    foreach ($profissionais as $profissional) {

        $row = array();
        $row[] = '<center>'.$profissional->id.'</center>';
        $row[] = '<center>'.$profissional->nome.'</center>';
        $row[] = '<center>'.$profissional->registro.'</center>';
        $row[] = '<center>'.$profissional->email.'</center>';

        $row[] = '<center><div style="display: inline-block;">
                    <button class="btn btn-primary btn-profissional-edit" 
                        id='.$profissional->id.'>
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-success btn-profissional-frequencia" 
                        id='.$profissional->id.'>
                        <i class="fa fa-check-square-o"></i>
                    </button>
                    <button class="btn btn-info btn-profissional-holerite" 
                        id='.$profissional->id.'>
                        <i class="fa fa-file-o"></i>
                    </button>
                    <button style="color:white; background-color:#2F4F4F;" class="btn btn-profissional-folha" 
                        id='.$profissional->id.' >
                        <i class="fa fa-money"></i>
                    </button>
                </div></center>';

        $data[] = $row;

    }
    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Profissional_model->records_total(),
        "recordsFiltered" => $this->Profissional_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }


    public function deletar_profissional($id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            $this->load->model("cemerge/Profissional_model");
            $this->Profissional_model->delete(['id' => $id]);
        exit;
    }

    public function ajax_get_profissional_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/Profissional_model");
        
        $id = $this->input->post("id");
        $data = $this->Profissional_model->get_data($id)->result_array()[0];
        $json["input"]["profissional_id"] = $data["id"];
        $json["input"]["profissional_nome"] = $data['nome'];
        $json["input"]["tipo_evento_select"] = $data['tipo'];
        //var_dump($data); exit;
        echo json_encode($json);
        exit;
    }

    public function ajax_get_evento_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/evento_model");
        
        $id = $this->input->post("id");
        $data = $this->evento_model->get_data($id)->result_array()[0];
        $json["input"]["evento_id"] = $data["id"];
        $json["input"]["evento_nome"] = $data["nome"];
        $json["input"]["evento_fixo"] =$data["fixo"];
        $json["input"]["evento_percentual"] =$data["percentual"];
        $json["input"]["evento_valor"] =$data["valor_ref"];
        $json["input"]["eventos_select"] =$data["valor_base"];
        $json["input"]["eventos_incidencias"] =$data["incidencias"];

        echo json_encode($json);
        exit;
    }
    public function cadastrar_evento(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/Evento_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/fopag/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $nome = $this->input->post('evento_nome');
        $tipo = $this->input->post('evento_tipo');
        $fixo = $this->input->post('evento_fixo');
        $percentual = $this->input->post('evento_percentual');
        $valor_ref = $this->input->post('evento_valor');
        $valor_base = $this->input->post('eventos_select');
        $incidencias = $this->input->post('evento_incidencias');


        $this->form_validation->set_rules('evento_nome', 'Nome', 'required');
        $this->form_validation->set_rules('evento_tipo', 'Tipo', 'required');
        $this->form_validation->set_rules('evento_fixo', 'Fixo', 'required');
        $this->form_validation->set_rules('evento_percentual', 'Percentual', 'required');
        $this->form_validation->set_rules('evento_valor', 'Valor', 'required');
        
        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['evento_id'])) {
                $this->Evento_model->insert([
                    'nome'=>$nome, 
                    'tipo'=>$tipo, 
                    'fixo'=>$fixo, 
                    'percentual'=>$percentual,
                    'valor_ref'=>$valor_ref,
                    'valor_base'=>$valor_base,
                    'incidencias'=>$incidencias
                ]);
            } else {
                $id = $data["evento_id"];
                unset($data['evento_id']);
                $this->Evento_model->update($id, [
                    'nome'=>$nome, 
                    'tipo'=>$tipo, 
                    'fixo'=>$fixo, 
                    'percentual'=>$percentual,
                    'valor_ref'=>$valor_ref,
                    'valor_base'=>$valor_base,
                    'incidencias'=>$incidencias
                ]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 

            //var_dump($data); exit;
            json_encode($json);
            exit;
    }
    
    public function jade(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/Evento_model");
        $this->load->model("cemerge/Folha_model");
        $this->load->model("cemerge/Profissional_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/fopag/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $id = $this->input->post('profissional_id');
        $ano = $this->input->post('anos_select');
        $mes = $this->input->post('meses_select');
        $tipo = $this->input->post('tipos_folha_select');
        
        $this->form_validation->set_rules('profissional_id', 'ID', 'required');
        $this->form_validation->set_rules('anos_select', 'Ano', 'required');
        $this->form_validation->set_rules('meses_select', 'Mês', 'required');
        $this->form_validation->set_rules('tipos_folha_select', 'Tipo', 'required');
        
        $json = array();
        $json["status"] = 1;
        if ($this->form_validation->run() == true) {
            $json = $this->input->post();
            
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 
            //var_dump($json); exit;
            json_encode($json);
            exit;
    }

    public function ajax_listar_eventos() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/evento_model");
        $eventos = $this->evento_model->get_datatable();
    
        $data = array();
        foreach ($eventos as $evento) {
    
            $row = array();
            $row[] = '<center>'.$evento->id.'</center>';
            $row[] = '<center>'.$evento->nome.'</center>';
            $row[] = '<center>'.$evento->tipo.'</center>';
            $row[] = '<center>'.$evento->fixo.'</center>';
            $row[] = '<center>'.$evento->percentual.'</center>';
            $row[] = '<center>'.$evento->valor_ref.'</center>';
    
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-evento" 
                            id='.$evento->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-evento" 
                            id='.$evento->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div></center>';
    
            $data[] = $row;
    
        }
        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->evento_model->records_total(),
            "recordsFiltered" => $this->evento_model->records_filtered(),
            "data" => $data,
        );
        echo json_encode($json);
        exit;
        }

        public function ajax_listar_folhas_profissional() {

            if (!$this->input->is_ajax_request()) {
                exit("Nenhum acesso de script direto permitido!");
            }
                
            $this->load->model("cemerge/folha_model");

            $id_profissional = $this->input->get_post('profissional_id');
            $mes = $this->input->get_post('meses_select');
            $ano = $this->input->get_post('anos_select');
            $tipo_folha = $this->input->get_post('tipos_folha_select');

            $folhas = $this->folha_model->get_folhas(1, 1, 2021, 1);

            $data = array();
            foreach ($folhas as $folha) {
        
                $row = array();
                $row[] = '<center>'.$folha->ano.'</center>';
                $row[] = '<center>'.$folha->mes.'</center>';
                $row[] = '<center>'.$folha->tp_folha.'</center>';
                $row[] = '<center>'.$folha->valor_ref.'</center>';
        
                $row[] = '<center><div style="display: inline-block;">
                            <button class="btn btn-primary btn-edit-folha" 
                                id='.$folha->id.'>
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-del-folha" 
                                id='.$folha->id.'>
                                <i class="fa fa-times"></i>
                            </button>
                        </div></center>';
        
                $data[] = $row;
        
            }
            $json = array(
                "draw" => $this->input->post("draw"),
                "recordsTotal" =>$this->folha_model->records_total(),
                "recordsFiltered" => $this->folha_model->records_filtered(),
                "status" => 1,
                "data" => $data,
            ); 
            echo json_encode($json);
            exit;
        }

    public function ajax_listar_folhas() {

                if (!$this->input->is_ajax_request()) {
                    exit("Nenhum acesso de script direto permitido!");
                }
                    
                $this->load->model("cemerge/folha_model");
                $this->load->model("cemerge/Evento_model");
    
                $id_profissional = $this->input->get_post('profissional_id');
                $mes = $this->input->get_post('meses_select');
                $ano = $this->input->get_post('anos_select');
                $tipo_folha = $this->input->get_post('tipos_folha_select');
                $eventos = $this->Evento_model->get_all();
    
                $folhas = $this->folha_model->get_folhas(1, 1, 2021, 1);
    
                $data = array();
                foreach ($folhas as $folha) {
            
                    $row = array();
                    $row[] = '<center>'.$folha->ano.'</center>';
                    $row[] = '<center>'.$folha->mes.'</center>';
                    $row[] = '<center>'.$folha->quantidade.'</center>';
                    $row[] = '<center>'.$folha->valor_ref.'</center>';

                    if($folha->tipo == 'C'){
                        $row[] = '<center>'.(10*$folha->valor_ref).'</center>';
                        $row[] = '';
                    } else {
                        $row[] = '';
                        $row[] = '<center>'.($folha->quantidade*$folha->valor_ref).'</center>';
                    }
            
                    $row[] = '<center><div style="display: inline-block;">
                                <button class="btn btn-primary btn-edit-folha" 
                                    id='.$folha->id.'>
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-del-folha" 
                                    id='.$folha->id.'>
                                    <i class="fa fa-times"></i>
                                </button>
                            </div></center>';
            
                    $data[] = $row;
            
                }
                $json = array(
                    "draw" => $this->input->post("draw"),
                    "recordsTotal" => $this->folha_model->records_total(),
                    "recordsFiltered" => $this->folha_model->records_filtered(),
                    "status" => 1,
                    "data" => $data,
                );
                echo json_encode($json);
                exit;
            }

    public function ajax_import_image() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./assets/img/fopag/";
		$config["allowed_types"] = "gif|png|jpg|jpeg";
		$config["overwrite"] = FALSE;

		$this->load->library("upload", $config);

		$json = array();
		$json["status"] = 1;

		if (!$this->upload->do_upload("image_file")) {
			$json["status"] = 0;
			$json["error"] = $this->upload->display_errors("","");
		} else {
			if ($this->upload->data()["file_size"] <= 5120) {
				$file_name = $this->upload->data()["file_name"];
				$json["img_path"] = base_url() . "assets/img/" . $file_name;

			} else {
				$json["status"] = 0;
				$json["error"] = "Arquivo não deve ser maior que 5 MB!";
			}

		}

		echo json_encode($json);
        exit;
	}

    public function cadastrar_folha(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/Folha_model");
        $this->load->model("cemerge/Evento_model");
        $this->load->model("cemerge/Profissional_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/fopag/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $ano = $this->input->post('anos_select');
        $mes = $this->input->post('meses_select');
        $tipo = $this->input->post('tipos_folha_select');
        $profissionais = $this->Profissional_model->get_all();
        $eventos_fixos = $this->Evento_model->get_eventos_fixos();

        $this->form_validation->set_rules('anos_select', 'Ano', 'required');
        $this->form_validation->set_rules('meses_select', 'Mês', 'required');
        $this->form_validation->set_rules('tipos_folha_select', 'Tipo', 'required');
        
        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['folha_id'])) {
                foreach ($profissionais as $profissional){
                    if($profissional){
                        foreach ($eventos_fixos as $evento){
                            $this->Folha_model->insert([
                                'ano'=>$ano, 
                                'mes'=>$mes, 
                                'id_evento'=>$evento->id, 
                                'tipo'=>$evento->tipo,
                                'tp_folha'=>$tipo,
                                'valor_ref'=>$evento->valor_ref,
                                'valor_total'=>$evento->valor_ref,
                                'id_unidade'=> '1',
                                'id_setor' => '1',
                                'id_profissional' => $profissional->id
                            ]);   
                        }
                    } else {
                        alert("Não existem nenhum profissional para processar");
                    }
                }

            } else {
                $id = $data["folha_id"];
                unset($data['folha_id']);
                $this->folha_model->update($id, [
                    'nome'=>$nome, 
                    'tipo'=>$tipo, 
                    'fixo'=>$fixo, 
                    'percentual'=>$percentual,
                    'valor_ref'=>$valor_ref,
                    'valor_base'=>$valor_base,
                    'incidencias'=>$incidencias
                ]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 

            
            json_encode($json);
            exit;
    }

}