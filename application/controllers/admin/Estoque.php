<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Estoque extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'sac', 'profissionais');
    private $_admin_groups = array('admin', 'sac');
    function __construct()
    {
        parent::__construct($this->_permitted_groups);

       /* Load :: Common */

       $this->load->model('cemerge/Estoque_model');
       $this->lang->load('admin/estoque');
       $this->lang->load('admin/fornecedores');

       /* Title Page */
       
       $this->page_title->push(lang('menu_estoque'));
       $this->data['pagetitle'] = '<li class="fa fa-truck fa-2x"><bold> Controle de Estoque</bold></li>';

       /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_estoque'), 'admin/estoque');
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

        $categoria_tipo_select = $this->_get_tipo_categorias();
        $categorias_select = $this->_get_categorias();
        $fornecedores_select = $this->_get_fornecedores();
        $nf_select = $this->_get_nf();
        $responsavel_select = $this->_get_responsaveis();
            

        $this->data['categoria_tipo_select'] = array(
            'name'  => 'categoria_tipo_select',
            'id'    => 'categoria_tipo_select',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('categoria_tipo_select'),
            'options' => $categoria_tipo_select,
        );

        $this->data['categorias_select'] = array(
            'name'  => 'produto_categoria',
            'id'    => 'produto_categoria',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('categorias_select'),
            'options' => $categorias_select,
        );

        $this->data['fornecedores_select'] = array(
            'name'  => 'produto_fornecedor',
            'id'    => 'produto_fornecedor',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('fornecedores_select'),
            'options' => $fornecedores_select,
        );

        $this->data['nf_select'] = array(
            'name'  => 'produto_nf',
            'id'    => 'produto_nf',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('nf_select'),
            'options' => $nf_select,
        );

        $this->data['responsavel_select'] = array(
            'name'  => 'produto_responsavel',
            'id'    => 'produto_responsavel',
            'type'  => 'select',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('responsavel_select'),
            'options' => $responsavel_select,
        );


        $this->template->admin_render('admin/estoque/index', $this->data);
    }

    private function _get_tipo_categorias()
    {
        
        $this->load->model('cemerge/Categoria_Estoque_model');

        $categoria_tipo_select = array(
            '' => 'Selecione o tipo de Categoria',
            '1' => 'Bens de Consumo',
            '2' => 'Bens Móveis',
        );

        //var_dump($categorias); exit;
        return $categoria_tipo_select;
    }

    private function _get_fornecedores()
    {
        
        $this->load->model('cemerge/Fornecedor_model');

        $fornecedores = $this->Fornecedor_model->get_all();

        $fornecedores_select = array(
            '' => 'Selecione um Fornecedor',
        );
        foreach ($fornecedores as $fornecedor) {
            $fornecedores_select[$fornecedor->id] = $fornecedor->nome;
        }
        //var_dump($categorias); exit;
        return $fornecedores_select;
    }

    private function _get_categorias()
    {
        
        $this->load->model('cemerge/Fornecedor_model');

        $categorias = $this->Categoria_Estoque_model->get_all();

        $categorias_select = array(
            '' => 'Selecione uma Categoria',
        );
        foreach ($categorias as $categoria) {
            $categorias_select[$categoria->id] = $categoria->nome;
        }
        //var_dump($categorias); exit;
        return $categorias_select;
    }

    private function _get_nf()
    {
        
        $this->load->model('cemerge/NF_model');

        $nfs = $this->NF_model->get_all();

        $nf_select = array(
            '' => 'Selecione uma Nota Fiscal',
        );
        foreach ($nfs as $nf) {
            $nf_select[$nf->id] = $nf->codigo;
        }
        //var_dump($categorias); exit;
        return $nf_select;
    }

    private function _get_responsaveis()
    {
        
        $this->load->model('cemerge/Responsavel_model');

        $responsaveis = $this->Responsavel_model->get_all();

        $responsavel_select = array(
            '' => 'Selecione um Responsavel',
        );
        foreach ($responsaveis as $responsavel) {
            $responsavel_select[$responsavel->id] = $responsavel->nome;
        }
        //var_dump($categorias); exit;
        return $responsavel_select;
    }
    
    public function cadastrar_categoria(){
        
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma categoria.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
        }
        
        $this->load->model("cemerge/Categoria_Estoque_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_categorias_create'), 'admin/estoque/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
            $json = array();
            $json["status"] = 1;
        
        $nome = $this->input->post('categoria_nome');
        $tipo = $this->input->post('categoria_tipo_select');

        $this->form_validation->set_rules('categoria_nome', 'lang:nome', 'required');
        $this->form_validation->set_rules('categoria_tipo_select', 'lang:nome', 'required');

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['categoria_id'])) {
                $this->Categoria_Estoque_model->insert(['nome'=>$nome, 'tipo' => $tipo ]);
            } else {
                $id = $data["categoria_id"];
                unset($data['categoria_id']);
                $this->Categoria_Estoque_model->update($id, ['nome' => $nome, 'tipo' => $tipo]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['nome'] = $nome;
        } 

        var_dump($data); exit;
        echo json_encode($json);
    }

    public function cadastrar_fornecedor(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/Fornecedor_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/estoque/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $nome = $this->input->post('fornecedor_nome');
        $cnpj = $this->input->post('fornecedor_cnpj');
        $endereco = $this->input->post('fornecedor_endereco');
        $email = $this->input->post('fornecedor_email');
        $contato = $this->input->post('fornecedor_contato');

        $this->form_validation->set_rules('fornecedor_nome', 'lang:fornecedores_nome', 'required');
        $this->form_validation->set_rules('fornecedor_cnpj', 'lang:fornecedores_cnpj', 'required');
        $this->form_validation->set_rules('fornecedor_endereco', 'lang:fornecedores_endereco', 'required');
        $this->form_validation->set_rules('fornecedor_email', 'lang:fornecedores_email', 'required');
        $this->form_validation->set_rules('fornecedor_contato', 'lang:fornecedores_contato', 'required');


        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['fornecedor_id'])) {
                $this->Fornecedor_model->insert(['nome'=>$nome, 'cnpj'=>$cnpj, 'endereco'=>$endereco, 'email'=>$email, 'contato'=>$contato]);
            } else {
                $id = $data["fornecedor_id"];
                unset($data['fornecedor_id']);
                $this->Fornecedor_model->update($id, ['nome'=>$nome, 'cnpj'=>$cnpj, 'endereco'=>$endereco, 'email'=>$email, 'contato'=>$contato]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 

        //var_dump($data); exit;
            json_encode($json);
            exit;
    }

    public function cadastrar_nf(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/NF_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/estoque/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $codigo = $this->input->post('nf_codigo');
        $fornecedor = $this->input->post('nf_fornecedor');
        $valor = $this->input->post('nf_valor');
        $datanf = $this->input->post('nf_data');

        $this->form_validation->set_rules('nf_codigo', 'Código', 'required');
        $this->form_validation->set_rules('nf_fornecedor', 'Fornecedor', 'required');
        $this->form_validation->set_rules('nf_valor', 'Valor', 'required');
        $this->form_validation->set_rules('nf_data', 'Data', 'required');


        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['nf_id'])) {
                $this->NF_model->insert(['codigo'=>$codigo, 'cod_fornecedor'=>$fornecedor, 'valor'=>$valor, 'data'=>$datanf]);
            } else {
                $id = $data["nf_id"];
                unset($data['nf_id']);
                $this->NF_model->update($id, ['codigo'=>$codigo, 'cod_fornecedor'=>$fornecedor, 'valor'=>$valor, 'data'=>$datanf]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 

        //var_dump($data); exit;
            json_encode($json);
            exit;
    }

    public function cadastrar_responsavel(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/Responsavel_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/estoque/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $nome = $this->input->post('responsavel_nome');
        $cpf = $this->input->post('responsavel_cpf');
        $email = $this->input->post('responsavel_email');
        $contato = $this->input->post('responsavel_contato');
        $setor = $this->input->post('responsavel_setor');


        $this->form_validation->set_rules('responsavel_nome', 'Nome', 'required');
        $this->form_validation->set_rules('responsavel_cpf', 'CPF', 'required');
        $this->form_validation->set_rules('responsavel_email', 'Email', 'required');
        $this->form_validation->set_rules('responsavel_contato', 'Contato', 'required');
        $this->form_validation->set_rules('responsavel_setor', 'Setor', 'required');
        
        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['responsavel_id'])) {
                $this->Responsavel_model->insert([
                    'nome'=>$nome, 
                    'cpf'=>$cpf, 
                    'email'=>$email, 
                    'contato'=>$contato,
                    'setor'=>$setor
                ]);
            } else {
                $id = $data["responsavel_id"];
                unset($data['responsavel_id']);
                $this->Responsavel_model->update($id, [
                    'nome'=>$nome, 
                    'cpf'=>$cpf, 
                    'email'=>$email, 
                    'contato'=>$contato,
                    'setor'=>$setor
                ]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 

        //var_dump($data); exit;
            json_encode($json);
            exit;
    }

    public function cadastrar_produto(){
            
        if (!$this->ion_auth->logged_in()) {
            $this->session->set_flashdata('message', 'Você deve estar autenticado para criar uma especialização.');
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group($this->_permitted_groups)) {
            $this->session->set_flashdata('message', 'O acesso &agrave; este recurso não é permitido ao seu perfil de usuário.');
            redirect('admin/dashboard', 'refresh');
        }

        $this->load->model("cemerge/Produto_model");
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_estoque_create'), 'admin/estoque/');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
        $nome = $this->input->post('produto_nome');
        $descricao = $this->input->post('produto_descricao');
        $marca = $this->input->post('produto_marca');
        $fornecedor = $this->input->post('produto_fornecedor');
        $tombamento = $this->input->post('produto_tombamento');
        $n_serie = $this->input->post('produto_n_serie');
        $responsavel = $this->input->post('produto_responsavel');
        $nf = $this->input->post('produto_nf');
        $data_tombamento = $this->input->post('produto_data_tombamento');
        $data_validade = $this->input->post('produto_data_validade');
        $data_aquisicao = $this->input->post('produto_data_aquisicao');
        $valor_compra = $this->input->post('produto_valor_compra');
        $valor_atual = $this->input->post('produto_valor_atual');
        $categoria = $this->input->post('produto_categoria');
        $obs = $this->input->post('produto_obs');

        $this->form_validation->set_rules('produto_nome', 'Nome', 'required');
        $this->form_validation->set_rules('produto_descricao', 'Descrição', 'required');
        $this->form_validation->set_rules('produto_marca', 'Marca', 'required');
        $this->form_validation->set_rules('produto_fornecedor', 'Fornecedor', 'required');
        $this->form_validation->set_rules('produto_n_serie', 'Nº de Série', 'required');
        $this->form_validation->set_rules('produto_nf', 'Nota Fiscal', 'required');
        $this->form_validation->set_rules('produto_data_aquisicao', 'Data de Aquisição', 'required');
        $this->form_validation->set_rules('produto_valor_compra', 'Valor da Compra', 'required');

        $json = array();

        if ($this->form_validation->run() == true) {
            $data = $this->input->post();
            if(empty($data['produto_id'])) {
                $this->Produto_model->insert(
                    [
                        'nome'=>$nome, 
                        'descricao'=>$descricao, 
                        'marca'=>$marca, 
                        'cod_fornecedor'=>$fornecedor,
                        'tombamento'=>$tombamento,
                        'n_serie'=>$n_serie,
                        'data_aquisicao'=>$data_aquisicao,
                        'data_tombamento'=>$data_tombamento,
                        'data_validade'=>$data_validade,
                        'valor_compra'=>$valor_compra,
                        'valor_atual'=>$valor_atual,
                        'cod_responsavel'=>$responsavel,
                        'cod_notafiscal'=>$nf,
                        'cod_categoria'=>$categoria,
                        'obs'=>$obs
                ]);
            } else {
                $id = $data["produto_id"];
                unset($data['produto_id']);
                $this->Produto_model->update($id, [
                        'nome'=>$nome, 
                        'descricao'=>$descricao, 
                        'marca'=>$marca, 
                        'cod_fornecedor'=>$fornecedor,
                        'tombamento'=>$tombamento,
                        'n_serie'=>$n_serie,
                        'data_aquisicao'=>$data_aquisicao,
                        'data_tombamento'=>$data_tombamento,
                        'data_validade'=>$data_validade,
                        'valor_compra'=>$valor_compra,
                        'valor_atual'=>$valor_atual,
                        'cod_responsavel'=>$responsavel,
                        'cod_notafiscal'=>$nf,
                        'cod_categoria'=>$categoria,
                        'obs'=>$obs
                ]);
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        } 

        //var_dump($this->data); exit;
            json_encode($json);
            exit;
    }

    public function ajax_listar_categorias() {

    if (!$this->input->is_ajax_request()) {
        exit("Nenhum acesso de script direto permitido!");
    }
        
    $this->load->model("cemerge/Categoria_Estoque_model");
    $categorias = $this->Categoria_Estoque_model->get_datatable();

    $data = array();
    foreach ($categorias as $categoria) {

        switch ($categoria->tipo) {
            case 1:
                $categoria->tipo = 'Bens de Consumo';
                break;
            case 2:
                $categoria->tipo = 'Bens Móveis';
                break;
        }

        $row = array();
        $row[] = '<center>'.$categoria->id.'</center>';
        $row[] = $categoria->nome;
        $row[] = '<center>'.$categoria->tipo.'</center>';

        $row[] = '<div style="display: inline-block;">
                    <button class="btn btn-primary btn-edit-categoria" 
                        id='.$categoria->id.'>
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-del-categoria" 
                        id='.$categoria->id.'>
                        <i class="fa fa-times"></i>
                    </button>
                </div>';

        $data[] = $row;

    }
    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Categoria_Estoque_model->records_total(),
        "recordsFiltered" => $this->Categoria_Estoque_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }

    public function ajax_listar_nf() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/NF_model");
        $nfs = $this->NF_model->get_datatable();
    
        $data = array();
        foreach ($nfs as $nf) {
    
            $row = array();
            $row[] = '<center>'.$nf->data.'</center>';
            $row[] = $nf->codigo;
            $row[] = $nf->cod_fornecedor;
            $row[] = $nf->valor;
            $row[] = $nf->img;
    
            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-nf" 
                            id='.$nf->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-nf" 
                            id='.$nf->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>';
    
            $data[] = $row;
    
        }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->NF_model->records_total(),
        "recordsFiltered" => $this->NF_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }


    public function ajax_listar_produtos() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Produto_model");
        $produtos = $this->Produto_model->get_datatable();

        $data = array();
        foreach ($produtos as $produto) {

            $row = array();
            $row[] = '<center>'.$produto->id.'</center>';
            $row[] = $produto->nome;
            $row[] = '<center>'.$produto->marca.'</center>';
            $row[] = '<center>'.$produto->cod_setor.'</center>';
            $row[] = '<center>'.$produto->cod_categoria.'</center>';
            $row[] = '<center>'.$produto->tombamento.'</center>';
            $row[] = '<center>'.$produto->cod_responsavel.'</center>';

            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-produto" 
                            id='.$produto->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-produto" 
                            id='.$produto->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>';

            $data[] = $row;

        }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Produto_model->records_total(),
        "recordsFiltered" => $this->Produto_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }

    public function ajax_listar_estoque() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Estoque_model");
        $estoque = $this->Estoque_model->get_datatable();

        $data = array();
        foreach ($estoque as $est) {

            $row = array();
            $row[] = '<center>'.$est->id.'</center>';
            $row[] = $est->cod_produto;

            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-categoria" 
                            id='.$est->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-categoria" 
                            id='.$est->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>';

            $data[] = $row;

        }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Estoque_model->records_total(),
        "recordsFiltered" => $this->Estoque_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }

    public function ajax_listar_entrada() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Fluxo_Estoque_model");
        $entradas = $this->Fluxo_Estoque_model->get_datatable();

        $data = array();
        foreach ($entradas as $entrada) {

            $row = array();
            $row[] = '<center>'.$entrada->id.'</center>';
            $row[] = $entrada->cod_produto;

            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-categoria" 
                            id='.$entrada->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-categoria" 
                            id='.$entrada->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>';

            $data[] = $row;

        }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Fluxo_Estoque_model->records_total(),
        "recordsFiltered" => $this->Fluxo_Estoque_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }

    public function ajax_listar_saida() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Fluxo_Estoque_model");
        $estoque = $this->Fluxo_Estoque_model->get_datatable();

        $data = array();
        foreach ($estoque as $est) {

            $row = array();
            $row[] = '<center>'.$est->id.'</center>';
            $row[] = $est->cod_produto;

            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-categoria" 
                            id='.$est->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-categoria" 
                            id='.$est->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>';

            $data[] = $row;

        }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Fluxo_Estoque_model->records_total(),
        "recordsFiltered" => $this->Fluxo_Estoque_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }

    public function ajax_listar_responsaveis() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            
        $this->load->model("cemerge/Responsavel_model");
        $responsaveis = $this->Responsavel_model->get_datatable();

        $data = array();
        foreach ($responsaveis as $responsavel) {

            $row = array();
            $row[] = '<center>'.$responsavel->id.'</center>';
            $row[] = '<center>'.$responsavel->nome.'</center>';
            $row[] = '<center>'.$responsavel->cpf.'</center>';
            $row[] = '<center>'.$responsavel->email.'</center>';
            $row[] = '<center>'.$responsavel->contato.'</center>';
            $row[] = '<center>'.$responsavel->setor.'</center>';

            $row[] = '<div style="display: inline-block;">
                        <button class="btn btn-primary btn-edit-categoria" 
                            id='.$responsavel->id.'>
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-del-categoria" 
                            id='.$responsavel->id.'>
                            <i class="fa fa-times"></i>
                        </button>
                    </div>';

            $data[] = $row;

        }

    $json = array(
        "draw" => $this->input->post("draw"),
        "recordsTotal" => $this->Responsavel_model->records_total(),
        "recordsFiltered" => $this->Responsavel_model->records_filtered(),
        "data" => $data,
    );
    echo json_encode($json);
    exit;
    }
    
    public function ajax_listar_fornecedores() {

    if (!$this->input->is_ajax_request()) {
        exit("Nenhum acesso de script direto permitido!");
    }
    $this->load->model("cemerge/Fornecedor_model");
    $fornecedores = $this->Fornecedor_model->get_datatable();

    $data = array();
    foreach ($fornecedores as $fornecedor) {

        $row = array();
        $row[] = '<center>'.$fornecedor->id.'</center>';
        $row[] = $fornecedor->nome;
        $row[] = '<center>'.$fornecedor->cnpj.'</center>';
        $row[] = '<center>'.$fornecedor->contato.'</center>';

        $row[] = '<div style="display: inline-block;" >
                    <button class="btn btn-primary btn-edit-fornecedor" 
                        id="'.$fornecedor->id.'">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-del-fornecedor" 
                        id="'.$fornecedor->id.'">
                        <i class="fa fa-times"></i>
                    </button>
                </div>';

        $data[] = $row;

    }

        $json = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $this->Fornecedor_model->records_total(),
            "recordsFiltered" => $this->Fornecedor_model->records_filtered(),
            "data" => $data,
        );
        echo json_encode($json);
        exit;
        }

    public function deletar_categoria($id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            $this->load->model("cemerge/Categoria_Estoque_model");
            $this->Categoria_Estoque_model->delete(['id' => $id]);
        exit;
    }

    public function deletar_fornecedor($id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            $this->load->model("cemerge/Fornecedor_model");
            $this->Fornecedor_model->delete(['id' => $id]);
        exit;
    }

    public function deletar_estoque($id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
            $id = $this->input->post("id");
            $this->Fornecedor_model->delete(['id' => $id]);
        exit;
    }
    public function deletar_nf($id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/NF_model");
            $this->NF_model->delete(['id' => $id]);
        exit;
    }

    public function deletar_produto($id) {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/Produto_model");
            $this->Produto_model->delete(['id' => $id]);
        exit;
    }


    public function ajax_get_categoria_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/Categoria_Estoque_model");
        
        $id = $this->input->post("id");
        $data = $this->Categoria_Estoque_model->get_data($id)->result_array()[0];
        $json["input"]["categoria_id"] = $data["id"];
        $json["input"]["categoria_nome"] = $data['nome'];
        $json["input"]["categoria_tipo_select"] = $data['tipo'];
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

    public function ajax_get_nf_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/NF_model");
        
        $id = $this->input->post("id");
        $data = $this->NF_model->get_data($id)->result_array()[0];
        $json["input"]["nf_id"] = $data["id"];
        $json["input"]["nf_codigo"] = $data["codigo"];
        $json["input"]["nf_fornecedor"] =$data["cod_fornecedor"];
        $json["input"]["nf_valor"] =$data["valor"];
        $json["input"]["nf_data"] =$data["data"];
        //$json["input"]["nf_img"] =$data["contato"];

        echo json_encode($json);
        exit;
    }

    public function ajax_get_produto_data() {

        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $json = array();
        $json["status"] = 1;
        $json["input"] = array();

        $this->load->model("cemerge/Produto_model");
        
        $id = $this->input->post("id");
        $data = $this->Produto_model->get_data($id)->result_array()[0];
        $json["input"]["produto_id"] = $data["id"];
        $json["input"]["produto_nome"] = $data["nome"];
        $json["input"]["produto_descricao"] = $data["descricao"];
        $json["input"]["produto_marca"] = $data["marca"];
        $json["input"]["produto_fornecedor"] = $data["cod_fornecedor"];
        $json["input"]["produto_tombamento"] = $data["tombamento"];
        $json["input"]["produto_n_serie"] = $data["n_serie"];
        $json["input"]["produto_data_aquisicao"] = $data["data_aquisicao"];
        $json["input"]["produto_data_tombamento"] = $data["data_tombamento"];
        $json["input"]["produto_data_validade"] = $data["data_validade"];
        $json["input"]["produto_valor_compra"] = $data["valor_compra"];
        $json["input"]["produto_valor_atual"] = $data["valor_atual"];
        $json["input"]["produto_responsavel"] = $data["cod_responsavel"];
        $json["input"]["produto_nf"] = $data["cod_notafiscal"];
        $json["input"]["produto_categoria"] = $data["cod_categoria"];
        $json["input"]["produto_obs"] = $data["obs"];

        echo json_encode($json);
        exit;
    }

    public function troca_categoria($id){
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/Profissional_model");
        $categoria = $this->input->get_post('nivel_estoque');
        $this->Profissional_model->update($id, ['nivel_estoque'=>$categoria]);
    }

    public function troca_estoque($id){
        if (!$this->input->is_ajax_request()) {
            exit("Nenhum acesso de script direto permitido!");
        }
        $this->load->model("cemerge/Profissional_model");

        $estoque = $this->input->get_post('estoque');
        $this->Profissional_model->update($id, ['estoque' => $estoque]);
    }

    public function ajax_import_image() {

		if (!$this->input->is_ajax_request()) {
			exit("Nenhum acesso de script direto permitido!");
		}

		$config["upload_path"] = "./assets/img/";
		$config["allowed_types"] = "gif|png|jpg|jpeg";
		$config["overwrite"] = TRUE;

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
	}

}