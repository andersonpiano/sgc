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

        $profissional_tipo_select = $this->_get_tipo_profissionais();
        //$produtos_select = $this->_get_produtos();
            
        $this->data['profissional_tipo_select'] = array(
            'name'  => 'profissional_tipo_select',
            'id'    => 'profissional_tipo_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('profissional_tipo_select'),
            'options' => $profissional_tipo_select,
        );

        $this->template->admin_render('admin/fopag/index', $this->data);
    }

    private function _get_tipo_profissionais()
    {
        
        $this->load->model('cemerge/Profissional_model');

        $profissional_tipo_select = array(
            '' => 'Selecione o tipo de profissional',
            '1' => 'Bens de Consumo',
            '2' => 'Bens Móveis',
        );

        //var_dump($profissionais); exit;
        return $profissional_tipo_select;
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
        $row[] = '<center>'.$profissional->registro.'</center>';
        $row[] = '<center>'.$profissional->id.'</center>';
        $row[] = '<center>'.$profissional->nome.'</center>';
        $row[] = '<center>'.$profissional->email.'</center>';

        $row[] = '<center><div style="display: inline-block;">
                    <button class="btn btn-primary btn-profissional-fopag" 
                        id='.$profissional->id.'>
                        <i class="fa fa-address-card"></i>
                    </button>
                    <button class="btn btn-info btn-profissional-view" 
                        id='.$profissional->id.'>
                        <i class="fa fa-file-o"></i>
                    </button>
                    <button class="btn btn-danger btn-del-profissional" 
                        id='.$profissional->id.'>
                        <i class="fa fa-times"></i>
                    </button>
                    <button class="btn btn-danger btn-del-profissional" 
                        id='.$profissional->id.'>
                        <i class="fa fa-times"></i>
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
        $json["input"]["profissional_tipo_select"] = $data['tipo'];
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