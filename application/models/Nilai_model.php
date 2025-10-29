<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_model extends CI_Model {

    private $table = 'nilai'; // ganti dengan nama tabel nilai di database

    public function __construct() {
        parent::__construct();
    }

    // Ambil semua data nilai
    public function get_all() {
        return $this->db->get($this->table)->result();
    }


    // Ambil data nilai berdasarkan siswa
    public function get_by_siswa($no_induk) {
        return $this->db->get_where($this->table, ['no_induk' => $no_induk])->result();
    }

    // Ambil data nilai berdasarkan mata pelajaran
    public function get_by_mapel($id_mapel) {
        return $this->db->get_where($this->table, ['id_mapel' => $id_mapel])->result();
    }

    // Cek apakah data nilai sudah ada untuk siswa dan mapel tertentu
    public function get_nilai_by_no_induk_mapel($no_induk, $id_mapel) {
        $this->db->select('nilai.*, siswa.no_induk, siswa.nama_siswa');
        $this->db->from($this->table); // tabel nilai
        $this->db->join('siswa', 'nilai.no_induk= siswa.no_induk'); // join tabel siswa
        $this->db->where('siswa.no_induk', $no_induk);
        $this->db->where('nilai.id_mapel', $id_mapel);
        return $this->db->get()->row();
    }
    public function get_nilai_by_no_induk($no_induk) {
        $this->db->select('n.*, m.nama_mapel, k.nama_kelas');
        $this->db->from('nilai n');
        $this->db->join('mata_pelajaran m', 'n.id_mapel = m.id_mapel');
        $this->db->join('kelas k', 'n.id_kelas = k.id_kelas');
        $this->db->where('n.no_induk', $no_induk);
        return $this->db->get()->result();
    }

    public function get_nilai_by_no_induk_mapel_kelas_semester($no_induk, $id_mapel, $id_kelas, $semester) {
        return $this->db->get_where('nilai', [
            'no_induk' => $no_induk,
            'id_mapel' => $id_mapel,
            'id_kelas' => $id_kelas,
            'semester' => $semester
        ])->row();
    }
    public function get_nilai_by_mapel($no_induk, $id_kelas, $semester, $id_mapel) {
        return $this->db->get_where('nilai', [
            'no_induk' => $no_induk,
            'id_kelas' => $id_kelas,
            'semester' => $semester,
            'id_mapel' => $id_mapel
        ])->row();
    }


    // application/models/Nilai_model.php
    public function get_existing_nilai_by_no_induk_kelas_semester($no_induk, $id_kelas, $semester) {
        $this->db->select('n.id_nilai, n.no_induk, n.id_kelas, n.id_mapel, n.semester, n.nilai_akhir, n.capaian_pembelajaran, m.nama_mapel, k.nama_kelas');
        $this->db->from('nilai n');
        $this->db->join('mata_pelajaran m', 'n.id_mapel = m.id_mapel', 'left');
        $this->db->join('kelas k', 'n.id_kelas = k.id_kelas', 'left');
        $this->db->where('n.no_induk', $no_induk);
        $this->db->where('n.id_kelas', $id_kelas);
        $this->db->where('n.semester', $semester);
        return $this->db->get()->result(); // hanya yang memang ada di tabel nilai
    } 

    public function get_all_mapel_with_nilai($no_induk, $id_kelas, $semester) {
        $this->db->select('m.id_mapel, m.nama_mapel,  m.kode_mapel, n.id_nilai, n.nilai_akhir, n.capaian_pembelajaran');
        $this->db->from('mata_pelajaran m');
        $this->db->join(
            'nilai n',
            'm.id_mapel = n.id_mapel AND n.no_induk = '.$this->db->escape($no_induk).' 
            AND n.id_kelas = '.$this->db->escape($id_kelas).' 
            AND n.semester = '.$this->db->escape($semester),
            'left'
        );
        return $this->db->get()->result();
    }
        public function get_nilai_by_no_induk_kelas_semester($no_induk, $id_kelas, $semester) {
            $this->db->select('m.id_mapel, m.nama_mapel, k.nama_kelas, 
                            n.id_nilai, n.no_induk, n.nilai_akhir, n.capaian_pembelajaran, n.semester');
            $this->db->from('mata_pelajaran m');
            $this->db->join('nilai n', 'm.id_mapel = n.id_mapel 
                                    AND n.no_induk = '.$this->db->escape($no_induk).' 
                                    AND n.id_kelas = '.$this->db->escape($id_kelas).' 
                                    AND n.semester = '.$this->db->escape($semester), 'left');
            $this->db->join('kelas k', 'n.id_kelas = k.id_kelas', 'left');
            $this->db->order_by('m.nama_mapel', 'ASC');
            return $this->db->get()->result();
        }

        // Insert 1 nilai
        public function insert_nilai($data) {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }

        // Insert banyak nilai
        public function insert_batch_nilai($data) {
            return $this->db->insert_batch($this->table, $data);
        }

        // Update nilai berdasarkan ID
        // public function update_nilai($id, $data) {
        //     $this->db->where('id_nilai', $id);
        //     $this->db->update($this->table, $data);
        // }
        public function update_nilai($no_induk, $id_kelas, $semester, $id_mapel, $data)
        {
            $this->db->where('no_induk', $no_induk);
            $this->db->where('id_kelas', $id_kelas);
            $this->db->where('semester', $semester);
            $this->db->where('id_mapel', $id_mapel);
            $this->db->update('nilai', $data);
        }


        // Delete nilai berdasarkan ID
        public function delete_nilai($id) {
            $this->db->where('id_nilai', $id);
            $this->db->delete($this->table);
        }

        // Update nilai berdasarkan id_nilai
        public function update($id_nilai, $data) {
            $this->db->where('id_nilai', $id_nilai);
            return $this->db->update('nilai', $data);
        }

        // Hapus nilai berdasarkan id_nilai
        public function delete($id_nilai) {
            $this->db->where('id_nilai', $id_nilai);
            return $this->db->delete('nilai');
        }

        public function get_mapel_by_kode($kode_mapel)
        {
            $this->db->where('kode_mapel', $kode_mapel);
            $query = $this->db->get('mata_pelajaran'); // tabel mapel
            return $query->row();
        }

        // Ambil nilai by id_nilai (untuk keperluan edit/delete)
        public function get_by_id($id_nilai) {
            return $this->db->get_where('nilai', ['id_nilai' => $id_nilai])->row();
        }
        
        
          public function update_nilai_siswa($no_induk, $id_kelas, $semester, $id_mapel, $data)
            {
                $this->db->where('no_induk', $no_induk);
                $this->db->where('id_kelas', $id_kelas);
                $this->db->where('semester', $semester);
                $this->db->where('id_mapel', $id_mapel);
                return $this->db->update('nilai', $data);
            }


    }
