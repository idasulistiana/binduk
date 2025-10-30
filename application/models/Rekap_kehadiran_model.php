<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Rekap_kehadiran_model extends CI_Model {

    public function get_all_siswa() {
        return $this->db->get('siswa')->result(); 
        // pastikan tabel siswa ada untuk ambil daftar siswa
    }

    public function insert_rekap_absen_siswa($data) {
        return $this->db->insert('rekap_kehadiran', $data);
    }
    
    public function cek_duplikat($no_induk, $semester, $tahun_ajaran)
    {
        $this->db->where('no_induk', $no_induk);
        $this->db->where('semester', $semester);
        $this->db->where('tahun_ajaran', $tahun_ajaran);
        return $this->db->get('rekap_kehadiran')->num_rows() > 0;
    }

    public function get_all_rekap() {
        $this->db->select('rekap_kehadiran.*, siswa.nama_siswa, kelas.nama_kelas');
        $this->db->from('rekap_kehadiran');
        $this->db->join('siswa', 'siswa.no_induk = rekap_kehadiran.no_induk', 'left');
        $this->db->join('kelas', 'kelas.id_kelas = rekap_kehadiran.id_kelas', 'left');
        $this->db->order_by('rekap_kehadiran.no_induk', 'ASC');
        $this->db->order_by('rekap_kehadiran.semester', 'ASC');
        return $this->db->get()->result();
    }
    public function get_by_id($id_rekap)
    {
        $this->db->select('rekap_kehadiran.*, siswa.no_induk, siswa.nama_siswa');
        $this->db->from('rekap_kehadiran');
        $this->db->join('siswa', 'siswa.no_induk = rekap_kehadiran.no_induk', 'left');
        $this->db->where('rekap_kehadiran.id_rekap', $id_rekap);
        return $this->db->get()->row();
    }
    // Ambil data rekap kehadiran berdasarkan siswa, kelas, dan semester
     public function get_rekap_kehadiran($no_induk, $id_kelas, $semester) {
        $this->db->select('kh.id_rekap, k.id_kelas, k.nama_kelas, kh.sakit, kh.izin, kh.tanpa_keterangan, kh.tahun_ajaran, kh.semester');
        $this->db->from('rekap_kehadiran kh');
        $this->db->join('kelas k', 'k.id_kelas = kh.id_kelas', 'left');
        $this->db->where('kh.no_induk', $no_induk);
        $this->db->where('kh.id_kelas', $id_kelas);
        $this->db->where('kh.semester', $semester);

        $query = $this->db->get();
        return $query->row();
    }

    public function update_rekap($id, $data) {
        $this->db->where('id_rekap', $id);
        return $this->db->update('rekap_kehadiran', $data);
    }

    public function delete_rekap($id) {
        $this->db->where('id_rekap', $id);
        return $this->db->delete('rekap_kehadiran');
    }
    public function get_rekap_siswa($no_induk, $id_kelas, $semester) {
        return $this->db->get_where('rekap_kehadiran', [
            'no_induk' => $no_induk,
            'id_kelas' => $id_kelas,
            'semester' => $semester
        ])->row();
    }

      public function check_siswa_rekap($no_induk, $id_kelas, $semester)
    {
        $this->db->where('no_induk', $no_induk);
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('semester', $semester);
        $query = $this->db->get('rekap_kehadiran');

        if ($query->num_rows() > 0) {
            return true; // sudah ada datanya
        } else {
            return false; // belum ada
        }
    }

    // Cek apakah siswa tertentu sudah ada rekap di kelas & semester tertentu
    public function check_duplicate($no_induk, $id_kelas, $semester)
    {
        $this->db->where('no_induk', $no_induk);
        $this->db->where('id_kelas', $id_kelas);
        $this->db->where('semester', $semester);
        $query = $this->db->get('rekap_kehadiran');

        return ($query->num_rows() > 0); // true = sudah ada, false = belum ada
    }
    public function update_kehadiran($id_rekap, $data) {
        $this->db->where('id_rekap', $id_rekap);
        return $this->db->update('rekap_kehadiran', $data);
    }
    public function get_siswa_autocomplete($term)
    {
        $this->db->like('no_induk', $term);
        $this->db->or_like('nama_siswa', $term);
        return $this->db->get('siswa')->result();
    }

    public function update_or_insert_kehadiran($no_induk, $id_kelas, $semester, $kehadiran)
    {
        $data = [
            'no_induk'           => $no_induk,
            'id_kelas'           => $id_kelas,
            'semester'           => $semester,
            'sakit'              => $kehadiran['sakit'],
            'izin'               => $kehadiran['izin'],
            'tanpa_keterangan'   => $kehadiran['tanpa_keterangan'],
            'tahun_ajaran'       => $kehadiran['tahun_ajaran']
        ];

        // Cek apakah data sudah ada
        $cek = $this->db->get_where('rekap_kehadiran', [
            'no_induk' => $no_induk,
            'id_kelas' => $id_kelas,
            'semester' => $semester
        ])->row();

        if ($cek) {
            // Update jika sudah ada
            $this->db->where('id_rekap', $cek->id);
            $this->db->update('rekap_kehadiran', $data);
        } else {
            // Insert jika belum ada
            $this->db->insert('rekap_kehadiran', $data);
        }
    }
    public function get_rekap_by_kelas_semester($kelas, $semester)
    {
        $this->db->select('rekap_kehadiran.*, siswa.nama_siswa, kelas.nama_kelas');
        $this->db->from('rekap_kehadiran');
        $this->db->join('siswa','rekap_kehadiran.no_induk = siswa.no_induk','left');
        $this->db->join('kelas','rekap_kehadiran.id_kelas = kelas.id_kelas','left');
        $this->db->where_in('rekap_kehadiran.id_kelas', $kelas);
        $this->db->where_in('rekap_kehadiran.semester', $semester);
        $this->db->order_by('rekap_kehadiran.no_induk','ASC');
        $this->db->order_by('rekap_kehadiran.semester','ASC');
        
        return $this->db->get()->result();
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
