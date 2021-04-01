<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrequenciaAssessus_model extends MY_Model
{
    protected $table = 'tb_ctl_frq';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function update($id, $data)
    {
        $where = "cd_ctl_frq = $id";

        return $this->db->update($this->table, $data, $where);
    }

    public function get_by_cdctlfrq($cd_ctl_frq)
    {
        $query = $this->db->get_where($this->table, array('cd_ctl_frq' => $cd_ctl_frq));

        return $query->row();
    }

    public function get_by_escala_id($escala_id, $tipo_batida)
    {
        $query = $this->db->get_where($this->table, array('escala_id' => $escala_id, 'tp_frq' => $tipo_batida));

        return $query->row();
    }

    public function get_batida_profissional_entrada($data_plantao, $profissional_id, $entrada, $saida){
        
        $entrada_m = date('H:i:s', strtotime("-1 hour", strtotime($entrada)));
        $saida_m = date('H:i:s', strtotime("-2 hour", strtotime($saida)));
        
        $sql = "SELECT p.nome, f.dt_frq as entrada, f.tipo_batida ";
        $sql .= "FROM `tb_ctl_frq` as f ";
        $sql .= "left join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "WHERE date(f.dt_frq) = '".$data_plantao."' ";
        $sql .= "and time(f.dt_frq) ";
        $sql .= "BETWEEN time('".$entrada_m."') and time('".$saida_m."') ";
        $sql .= "and p.id = $profissional_id ";
        $sql .= "ORDER BY f.dt_frq ";
        $sql .= "LIMIT 1 ";
        
        $query = $this->db->query($sql);
        
        return $query->row();
    }
    public function get_batida_profissional_saida($data_plantao, $profissional_id, $entrada, $saida){
        
        $entrada_m = date('H:i:s', strtotime("+3 hour", strtotime($entrada)));
        $saida_m = date('H:i:s', strtotime("+1 hour", strtotime($saida)));
        
        $sql = "SELECT p.nome, f.dt_frq as saida , f.tipo_batida ";
        $sql .= "FROM `tb_ctl_frq` as f ";
        $sql .= "left join profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "WHERE date(f.dt_frq) = '".$data_plantao."' ";
        $sql .= "and time(f.dt_frq) ";
        $sql .= "BETWEEN time('".$entrada_m."') and time('".$saida_m."') ";
        $sql .= "and p.id = $profissional_id ";
        $sql .= "ORDER BY f.dt_frq DESC ";
        $sql .= "LIMIT 1 ";
        
        $query = $this->db->query($sql);
        
        return $query->row();
    }
    var $column_search = array("DT_FRQ");
    var $column_order = array("DT_FRQ", "CD_PES_FIS");

    private function _get_batidas() {

        $search = NULL;
        if ($this->input->post("search")) {
            $search = $this->input->post("search")["value"];
        }
        $order_column = NULL;
        $order_dir = NULL;
        $order = $this->input->post("order");
        if (isset($order)) {
            $order_column = $order[0]["column"];
            $order_dir = $order[0]["dir"];
        }

        $this->db->from($this->table);
        $this->db->where('ignorar', 1);

        if (isset($search)) {
            $first = TRUE;
            foreach ($this->column_search as $field) {
                if ($first) {
                    $this->db->group_start();
                    $this->db->like($field, $search);
                    $first = FALSE;
                } else {
                    $this->db->or_like($field, $search);
                }
            }
            if (!$first) {
                $this->db->group_end();
            }
        }

        if (isset($order)) {
            $this->db->order_by($this->column_order[$order_column], $order_dir);
        }
    }

    public function get_batidas(){

        $length = $this->input->post("length");
        $start = $this->input->post("start");
        $this->_get_batidas();
        if (isset($length) && $length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    public function get_data($id, $select = NULL) {
		if (!empty($select)) {
			$this->db->select($select);
		}
		$this->db->from($this->table);
		$this->db->where("id", $id);
		return $this->db->get();
    }

    public function records_filtered() {

        $this->_get_batidas();
        return $this->db->get()->num_rows();

    }

    public function records_total() {

        $this->_get_batidas();
        return $this->db->count_all_results();

    }
}
