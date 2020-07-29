<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    private $_permitted_groups = array('admin', 'profissionais');

    public function __construct()
    {
        parent::__construct($this->_permitted_groups);

        /* Load :: Common */
        $this->load->helper('number');
        $this->load->model('admin/dashboard_model');
        $this->load->model('cemerge/escala_model');
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
                $usuarioProfissional = $this->usuarioprofissional_model->get_where(['user_id' => $userId])[0];
                if ($usuarioProfissional) {
                    $profissional = $this->profissional_model->get_where(['id' => $usuarioProfissional->profissional_id])[0];
                    $setores_profissional = $this->setor_model->get_setores_por_profissional($profissional->id); // Trocar para buscar na tabela associativa o id
                    $setores_profissional_arr = array();
                    foreach ($setores_profissional as $setor) {
                        array_push($setores_profissional_arr, $setor->setor_id);
                    }
                    $setores_profissional_str = '(' . implode(', ', $setores_profissional_arr) . ')';
                }
            }

            /* Data */
            if ($this->ion_auth->is_admin()) {
                $this->data['count_users']       = $this->dashboard_model->get_count_record('users');
                $this->data['count_groups']      = $this->dashboard_model->get_count_record('groups');
                $this->data['disk_totalspace']   = $this->dashboard_model->disk_totalspace(DIRECTORY_SEPARATOR);
                $this->data['disk_freespace']    = $this->dashboard_model->disk_freespace(DIRECTORY_SEPARATOR);
                $this->data['disk_usespace']     = $this->data['disk_totalspace'] - $this->data['disk_freespace'];
                $this->data['disk_usepercent']   = $this->dashboard_model->disk_usepercent(DIRECTORY_SEPARATOR, false);
                $this->data['memory_usage']      = $this->dashboard_model->memory_usage();
                $this->data['memory_peak_usage'] = $this->dashboard_model->memory_peak_usage(true);
                $this->data['memory_usepercent'] = $this->dashboard_model->memory_usepercent(true, false);
            } else {
                $this->data['plantoes_recebidos_confirmar']
                    = $this->escala_model->get_escalas(
                        ['profissionalsubstituto_id' => $profissional->id,
                         'statuspassagem' => 0]
                    );
                $this->data['plantoes_passados_confirmar']
                    = $this->escala_model->get_escalas(
                        ['profissional_id' => $profissional->id,
                        'statuspassagem' => 0]
                    );
                $this->data['oportunidades']
                    = $this->escala_model->get_escalas(
                        ['profissionalsubstituto_id' => 0,
                        'statuspassagem' => 0],
                        ['setor_id in' => $setores_profissional_str]
                    );

                //var_dump($this->data['oportunidades']); exit; 
            }

            /* TEST */
            $this->data['url_exist']    = is_url_exist('http://www.cemerge.com.br'); //http://www.domprojects.com


            /* Load Template */
            $this->template->admin_render('admin/dashboard/index', $this->data);
        }
    }
}
