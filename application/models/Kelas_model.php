<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    private $table = 'kelas'; // Nama tabel di database

    public function __construct() {
        
    }

    // Ambil semua data kelas
    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    // Ambil data kelas berdasarkan nama
    public function get_by_name($nama_kelas) {
        return $this->db->get_where($this->table, ['nama_kelas' => $nama_kelas])->row();
    }
    
    // Ambil nama kelas berdasarkan ID
    public function get_nama_kelas($id_kelas) {
        $row = $this->db->select('nama_kelas')
                        ->from('kelas')
                        ->where('id_kelas', $id_kelas)
                        ->get()
                        ->row();
        return $row ? $row->nama_kelas : null;
    }

    // Ambil data kelas berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_kelas' => $id])->row();
    }

    // Insert data kelas baru
    public function insert_kelas($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update data kelas berdasarkan ID
    public function update_kelas($id, $data) {
        $this->db->where('id_kelas', $id);
        return $this->db->update($this->table, $data);
    }

    // Hapus data kelas berdasarkan ID
    public function delete_kelas($id) {
        $this->db->where('id_kelas', $id);
        return $this->db->delete($this->table);
    }
    
    public function get_id_kelas_by_nama($nama_kelas)
    {
        $this->db->select('id_kelas');
        $this->db->where('nama_kelas', $nama_kelas);
        $query = $this->db->get('kelas');
        if ($query->num_rows() > 0) {
            return $query->row()->id_kelas;
        } else {
            return null; // jika nama kelas tidak ditemukan
        }
    }
}
