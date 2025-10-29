<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Klapper_model extends CI_Model {

    private $table = 'klapper';

    public function get_all() {
        $this->db->select('k.*, s.nama_siswa as nama_siswa, s.gender as gender');
        $this->db->from('klapper k');
        $this->db->join('siswa s', 's.no_induk = k.no_induk', 'left');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_by_no_induk($no_induk)
    {
        return $this->db->get_where('klapper', ['no_induk' => $no_induk])->row();
    }

    public function get_by_id($no_induk) {
        $this->db->select('klapper.*, siswa.nama_siswa');
        $this->db->from('klapper');
        $this->db->join('siswa', 'siswa.no_induk = klapper.no_induk'); 
        $this->db->where('klapper.no_induk', $no_induk);
        return $this->db->get()->row();
    }

    public function insert_klapper($data) {
        return $this->db->insert($this->table, $data);
    }

   public function update_klapper($id_klapper, $data)
    {
        $this->db->where('no_induk', $id_klapper);
        $this->db->update('klapper', $data);
    }

    public function delete_klapper($no_induk) {
        $this->db->where('no_induk', $no_induk);
        return $this->db->delete($this->table);
    }

    public function get_siswa_by_no_induk($no_induk) {
        return $this->db->get_where('siswa', ['no_induk' => $no_induk])->row();
    }

}
