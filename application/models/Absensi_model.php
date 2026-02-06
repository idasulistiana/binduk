<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {

    private $table = 'absensi';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id_absensi, $data)
    {
        return $this->db->where('id_absensi', $id_absensi)
                        ->update($this->table, $data);
    }

    public function delete($id_absensi)
    {
        return $this->db->where('id_absensi', $id_absensi)
                        ->delete($this->table);
    }

    public function get_by_id($id_absensi)
    {
        return $this->db->get_where($this->table, [
            'id_absensi' => $id_absensi
        ])->row();
    }
}
