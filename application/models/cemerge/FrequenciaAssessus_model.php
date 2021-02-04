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

    public function get_batida_profissional($data_plantao, $profissional_id, $tipo_batida, $entrada, $saida){
        $sql  = "SELECT HR_FRQ " ;
        $sql .= "FROM tb_ctl_frq as f ";
        $sql .= "left JOIN profissionais p on (f.cd_pes_fis = p.cd_pes_fis) ";
        $sql .= "WHERE date(dt_frq) = '$data_plantao' ";
        $sql .= "AND p.id = '$profissional_id' ";
        $sql .= "AND tipo_batida = '$tipo_batida' ";
        $sql .= "AND time(hr_frq) between '".$entrada."' and '".$saida."' ";
        $sql .= "ORDER by HR_FRQ DESC ";
        
        $query = $this->db->query($sql);
        
        return $query->row();
    }
}
