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
       $this->data['pagetitle'] = '<li class="fa fa-address-card fa-2x"><bold> Folha de Pagamentos</bold></li>';

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

        $this->template->admin_render('admin/fopag/index', $this->data);
    }

    private function _get_tipo_evento()
    {
        
        $this->load->model('cemerge/Evento_model');

        $tipo_evento_select = array(
            '' => 'Selecione o tipo de Evento',
            '1' => 'Crédito',
            '2' => 'Débito',
        );

        return $tipo_evento_select;
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
            '' => '2021',
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
                    <button class="btn btn-primary btn-profissional-view" 
                        id='.$profissional->id.'>
                        <i class="fa fa-address-card"></i>
                    </button>
                    <button class="btn btn-success btn-profissional-frequencia" 
                        id='.$profissional->id.'>
                        <i class="fa fa-check-square-o"></i>
                    </button>
                    <button  class="btn btn-info btn-profissional-holerite" 
                        id='.$profissional->id.'>
                        <i class="fa fa-file-o"></i>
                    </button>
                    <button style="color:white; background-color:#2F4F4F;" class="btn btn-profissional-folha" 
                        id='.$profissional->id.'>
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

    public function ajax_get_fornecedor_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/Fornecedor_model");
        
        $id = $this->input->post("id");
        $data = $this->Fornecedor_model->get_data($id)->result_array()[0];
        $json["input"]["fornecedor_id"] = $data["id"];
        $json["input"]["fornecedor_nome"] = $data["nome"];
        $json["input"]["fornecedor_cnpj"] =$data["cnpj"];
        $json["input"]["fornecedor_endereco"] =$data["endereco"];
        $json["input"]["fornecedor_email"] =$data["email"];
        $json["input"]["fornecedor_contato"] =$data["contato"];

        echo json_encode($json);
        exit;
    }

    public function troca_profissional($id){
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/Profissional_model");
        $profissional = $this->input->get_post('nivel_estoque');
        $this->Profissional_model->update($id, ['nivel_estoque'=>$profissional]);
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
            $row[] = '<center>'.$evento->registro.'</center>';
            $row[] = '<center>'.$evento->email.'</center>';
    
            $row[] = '<center><div style="display: inline-block;">
                        <button class="btn btn-primary btn-evento-fopag" 
                            id='.$evento->id.'>
                            <i class="fa fa-address-card"></i>
                        </button>
                        <button class="btn btn-warning btn-evento-view" 
                            id='.$evento->id.'>
                            <i class="fa fa-check-square-o"></i>
                        </button>
                        <button class="btn btn-info btn-del-evento" 
                            id='.$evento->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-evento" 
                            id='.$evento->id.'>
                            <i class="fa fa-file-o"></i>
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

}