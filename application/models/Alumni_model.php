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

    public function delete_alumni($no_induk) {
        $this->db->where('no_induk', $no_induk);
        return $this->db->delete('siswa');
    }
    public function update_alumni($no_induk, $data) {
        $this->db->where('no_induk', $no_induk);
        return $this->db->update('siswa', $data);
    }
    public function save_tahun_lulus($no_induk, $tahun_lulus)
    {
        // Cek apakah data klapper sudah ada berdasarkan nisn
        $cek = $this->db->where('no_induk', $no_induk)->get('klapper');

        if ($cek->num_rows() > 0) {
            // Jika sudah ada → update kelas_6
            $this->db->where('no_induk', $no_induk);
            return $this->db->update('klapper', [
                'kelas_6' => $tahun_lulus
            ]);
        } else {
            // Jika belum ada → insert baru
            return $this->db->insert('klapper', [
                'no_induk'    => $no_induk,
                'kelas_6' => $tahun_lulus
            ]);
        }
    }

       // Ambil 1 data berdasarkan NISN
   public function get_by_no_induk($no_induk) {
        $this->db->select('
            siswa.*, 
            kelas.nama_kelas, 
            klapper.kelas_6 AS tahun_lulus
        ');
        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');
        $this->db->join('klapper', 'klapper.no_induk = siswa.no_induk', 'left');
        $this->db->where('siswa.no_induk', $no_induk);
        $this->db->where('siswa.status', 'lulus');
        return $this->db->get()->row();
    }

}
