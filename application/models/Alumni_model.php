<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumni_model extends CI_Model {

    private $table = 'siswa'; // tetap ambil dari tabel siswa

    public function get_all_alumni() {
        $this->db->select('
            siswa.*, 
            kelas.nama_kelas, 
            klapper.kelas_6 AS tahun_lulus
        ');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');
        $this->db->join('klapper', 'klapper.no_induk = siswa.no_induk', 'left');
        $this->db->where('siswa.status', 'lulus');
        return $this->db->get()->result();
    }

    public function get_filtered_alumni($kelas_id = null) {
        $this->db->select('siswa.*, kelas.nama_kelas, klapper.kelas_6 AS tahun_lulus');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas', 'left');
        $this->db->join('klapper', 'klapper.no_induk = siswa.no_induk', 'left');
        $this->db->where('klapper.kelas_6 IS NOT NULL');
        
        if (!empty($kelas_id)) {
            $this->db->where('siswa.id_kelas', $kelas_id);
        }

        return $this->db->get()->result();
    }

    public function delete_alumni($nisn) {
        $this->db->where('nisn', $nisn);
        return $this->db->delete('siswa');
    }
    public function update_alumni($nisn, $data) {
        $this->db->where('nisn', $nisn);
        return $this->db->update('siswa', $data);
    }
       // Ambil 1 data berdasarkan NISN
   public function get_by_nisn($nisn) {
        $this->db->select('
            siswa.*, 
            kelas.nama_kelas, 
            klapper.kelas_6 AS tahun_lulus
        ');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');
        $this->db->join('klapper', 'klapper.no_induk = siswa.no_induk', 'left');
        $this->db->where('siswa.nisn', $nisn);
        $this->db->where('siswa.status', 'lulus');
        return $this->db->get()->row();
    }

}
