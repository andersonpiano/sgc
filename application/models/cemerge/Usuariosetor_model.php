<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuariosetor_model extends MY_Model
{
    protected $table = 'usuariosetor';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function get_usuarios_por_setor($setor_id) {
        $fields = 'users.id, users.first_name, users.last_name, users.email, usuariosetor.coordenador';

        $this->db->select($fields);
        $this->db->from('users');
        $this->db->join('usuariosetor', 'users.id = usuariosetor.user_id');
        $this->db->join('setores', 'setores.id = usuariosetor.setor_id');
        $this->db->where('setores.id', $setor_id);
        $query = $this->db->get();

        return $query->result();
    }

    public function trocar_coordenador($setor, $user){
        
        return $this->db->update($this->table, ['user_id' => $user], ['setor_id' => $setor]);
    }
}
