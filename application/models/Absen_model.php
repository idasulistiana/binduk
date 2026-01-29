<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absen_model extends CI_Model
{
    private $table = 'absen_harian';

    public function get_all()
    {
        return $this->db->order_by('tanggal', 'DESC')->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_absen' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_absen', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id_absen' => $id]);
    }

    // Cek duplikat (no_induk + tanggal)
    public function exists($no_induk, $tanggal)
    {
        return $this->db
            ->where('no_induk', $no_induk)
            ->where('tanggal', $tanggal)
            ->count_all_results($this->table) > 0;
    }
}
