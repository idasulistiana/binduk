<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ekskul_model extends CI_Model {

    private $table = 'ekskul'; // ganti sesuai nama tabel di database

    public function __construct() {
        
    }

    // Ambil semua data ekskul
    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    // Ambil data ekskul berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_ekskul' => $id])->row();
    }

    // Ambil data ekskul berdasarkan nama
    public function get_by_name($nama_mapel) {
        return $this->db->get_where($this->table, ['nama_ekskul' => $nama_ekskul])->row();
    }

    // Insert data ekskul baru
    public function insert_ekskul($data) {
        return $this->db->insert($this->table, $data);
    }
    public function insert_nilai_ekskul($data)
    {
        return $this->db->insert('nilai_ekskul', $data);
    }

    // Update data ekskul
    public function update_ekskul($id, $data) {
        $this->db->where('id_ekskul', $id);
        return $this->db->update($this->table, $data);
    }

    // Hapus data ekskul
    public function delete_ekskul($id) {
        $this->db->where('id_ekskul', $id);
        return $this->db->delete($this->table);
    }
    
    // show nilai ekskul
    public function get_nilai_ekskul_siswa($no_induk, $id_kelas, $semester){
        $this->db->select('e.nama_ekskul, ne.nilai, ne.keterangan');
        $this->db->from('nilai_ekskul ne');
        $this->db->join('ekskul e', 'e.id_ekskul = ne.id_ekskul', 'left');
        $this->db->where('ne.no_induk', $no_induk);
        $this->db->where('ne.id_kelas', $id_kelas);
        $this->db->where('ne.semester', $semester);
        return $this->db->get()->result();
    }


    public function get_nilai_ekskul_siswa_withID($no_induk, $id_kelas, $semester, $id_ekskul)
    {
        return $this->db->get_where('nilai_ekskul', [
            'no_induk'  => $no_induk,
            'id_kelas'  => $id_kelas,
            'semester'  => $semester,
            'id_ekskul' => $id_ekskul
        ])->row();
    }


    public function update_nilai_ekskul($no_induk, $id_kelas, $semester, $id_ekskul, $data)
    {
        $this->db->where('no_induk', $no_induk);
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('semester', $semester);
        $this->db->where('id_ekskul', $id_ekskul);
        return $this->db->update('nilai_ekskul', $data);
    }

        public function update_nilai_ekskul_by_id($id_nilai_ekskul, $data)
    {
        $this->db->where('id_nilai_ekskul', $id_nilai_ekskul);
        return $this->db->update('nilai_ekskul', $data);
    }


    public function get_all_ekskul_with_nilai($no_induk, $id_kelas, $semester) {
        $this->db->select('e.id_ekskul, e.nama_ekskul, ne.id_nilai_ekskul, ne.nilai, ne.keterangan');
        $this->db->from('ekskul e');
        $this->db->join('nilai_ekskul ne', 'ne.id_ekskul = e.id_ekskul AND ne.no_induk = "'.$no_induk.'" AND ne.id_kelas = "'.$id_kelas.'" AND ne.semester = "'.$semester.'"', 'left');
        $this->db->order_by('e.nama_ekskul', 'ASC');
        return $this->db->get()->result();
    }
    public function get_by_kode($kode_ekskul)
    {
        return $this->db->get_where('ekskul', ['kode_ekskul' => $kode_ekskul])->row();
    }

    

}
