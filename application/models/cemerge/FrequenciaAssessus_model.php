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
}
