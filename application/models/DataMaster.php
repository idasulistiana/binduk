<?php

defined('BASEPATH') or exit('No direct script access allowed');
#[\AllowDynamicProperties]
class DataMaster extends CI_Model
{
    //kelola user
    public function insert_user($data)
    {
        $this->db->insert('user', $data);
    }
    public function select_user()
    {
        $this->db->select('*');
        $this->db->from('user');
        return $this->db->get()->result();
    }
    public function edit_user($id)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('id_user', $id);
        return $this->db->get()->row();
    }
    public function update_user($id, $data)
    {
        $this->db->where('id_user', $id);
        $this->db->update('user', $data);
    }
    public function delete_user($id)
    {
        $this->db->where('id_user', $id);
        $this->db->delete('user');
    }
    public function get_kelas_by_nama($nama_kelas)
    {
        return $this->db->get_where('kelas', ['nama_kelas' => $nama_kelas])->row();
    }
    
    // Ambil semua data siswa
   public function siswa_forklapper() {
        $this->db->select('
            klapper.*, 
            siswa.*, 
            kelas.nama_kelas
        ');
        $this->db->from('klapper');
        $this->db->join('siswa', 'siswa.no_induk = klapper.no_induk', 'left');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');
        $this->db->order_by('siswa.no_induk', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }



    public function get_all_siswa() {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');
        $this->db->where('siswa.status', 'aktif'); // hanya siswa aktif
        $this->db->where('siswa.status IS NOT NULL'); // pastikan tidak null
        $this->db->where('siswa.status !=', ''); // pastikan tidak kosong
        $this->db->order_by('siswa.no_induk', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

   public function get_siswa($kelas_id = '') // buat filter kelas 
    {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');
        $this->db->where('siswa.status', 'aktif');

        if($kelas_id != ''){
            $this->db->where('siswa.kelas', $kelas_id);
        }

        $query = $this->db->get();
        return $query->result(); // hanya return data
    }

    
   public function get_siswa_forklapper($kelas_id = '') 
    {
        $this->db->select('klapper.*, kelas.nama_kelas, siswa.nama_siswa, siswa.gender, siswa.status');
        $this->db->from('klapper');
        $this->db->join('siswa', 'siswa.no_induk = klapper.no_induk', 'left');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');

        if ($kelas_id != '') {
            if ($kelas_id == 'lulus') {
                $this->db->where('siswa.status', 'lulus');
            } else {
                $this->db->where('siswa.kelas', $kelas_id);
                $this->db->where('siswa.status', 'aktif');
            }
        } else {
            $this->db->where('siswa.status', 'aktif');
        }

        $this->db->order_by('kelas.nama_kelas', 'ASC');
        $this->db->order_by('siswa.nama_siswa', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }



   public function get_siswa_fornilai($kelas_id = '') 
    {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left');

        if ($kelas_id != '') {
            if ($kelas_id == 'lulus') {
                // Jika yang dipilih adalah "lulus", tampilkan hanya siswa dengan status lulus
                $this->db->where('siswa.status', 'lulus');
            } else {
                // Jika bukan "lulus", tampilkan siswa berdasarkan id kelas
                $this->db->where('siswa.kelas', $kelas_id);
                $this->db->where('siswa.status', 'aktif'); // pastikan hanya siswa aktif
            }
        } else {
            // Jika tidak ada filter kelas, tampilkan semua siswa aktif
            $this->db->where('siswa.status', 'aktif');
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function cek_nisn($nisn){
        $this->db->where('nisn', $nisn);
        $query = $this->db->get('siswa');
        return $query->num_rows() > 0;
    }
    public function insert_siswa($data)
    {
        $this->db->insert('siswa', $data);
    }
    public function delete_siswa($id)
    {
        $this->db->where('nisn', $id);
        $this->db->delete('siswa');
    }
    public function edit_siswa($id)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->where('nisn', $id);
        return $this->db->get()->row();
    }
    public function update_siswa($id, $data)
    {
      
        $this->db->where('nisn', $id);
        $this->db->update('siswa', $data);
    }
    // untuk dropdown di halaman klapper
    public function get_siswa_like($term) {
        $this->db->like('no_induk', $term);
        $this->db->or_like('nama_siswa', $term);
        return $this->db->get('siswa')->result();
    }

    
    public function get_siswa_by_no_induk($no_induk) {
        $this->db->select('siswa.*, kelas.nama_kelas'); // ambil nama_kelas dari tabel kelas
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left'); // relasi id_kelas dengan siswa.kelas
        $this->db->where('siswa.no_induk', $no_induk);
        $query = $this->db->get();
        return $query->row();
        
    }


}
                        
/* End of file DataMaster.php */
