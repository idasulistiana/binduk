<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_detail_model extends CI_Model {

    private $table = 'absensi_detail';

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

    public function delete_by_absensi($id_absensi)
    {
        return $this->db->where('id_absensi', $id_absensi)
                        ->delete($this->table);
    }

    public function get_by_absensi($id_absensi)
    {
        return $this->db->get_where($this->table, [
            'id_absensi' => $id_absensi
        ])->result();
    }
}
