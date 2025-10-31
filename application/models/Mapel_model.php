<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Mapel_model extends CI_Model {

    private $table = 'mata_pelajaran'; // ganti sesuai nama tabel di database

    public function __construct() {
        
    }

    // Ambil semua data mapel
    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    // Ambil data mapel berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_mapel' => $id])->row();
    }

    // Ambil data mapel berdasarkan nama
    public function get_by_name($nama_mapel) {
        return $this->db->get_where($this->table, ['nama_mapel' => $nama_mapel])->row();
    }

    // Insert data mapel baru
    public function insert_mapel($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update data mapel
    public function update_mapel($id, $data) {
        $this->db->where('id_mapel', $id);
        return $this->db->update($this->table, $data);
    }

    // Hapus data mapel
    public function delete_mapel($id) {
        $this->db->where('id_mapel', $id);
        return $this->db->delete($this->table);
    }
}
