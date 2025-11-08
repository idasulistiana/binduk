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
    //kelola data kategori
    public function insert_kategori($data)
    {
        $this->db->insert('kategori', $data);
    }
    public function select_kategori()
    {
        $this->db->select('*');
        $this->db->from('kategori');
        return $this->db->get()->result();
    }
    public function cek_no_induk($no_induk)
    {
        $this->db->where('no_induk', $no_induk);
        $query = $this->db->get('siswa');
        return $query->num_rows() > 0;
    }

    public function edit_kategori($id)
    {
        $this->db->select('*');
        $this->db->from('kategori');
        $this->db->where('id_kategori', $id);
        return $this->db->get()->row();
    }
    public function update_kategori($id, $data)
    {
        $this->db->where('id_kategori', $id);
        $this->db->update('kategori', $data);
    }
    public function delete_kategori($id)
    {
        $this->db->where('id_kategori', $id);
        $this->db->delete('kategori');
    }
    // Ambil semua data siswa
    // public function get_all_siswa() {
    //     $this->db->select('siswa.*, kelas.nama_kelas');
    //     $this->db->from('siswa');
    //     $this->db->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left'); // ubah 'siswa.id_kelas' ke 'siswa.kelas'
    //     $this->db->where('siswa.status_siswa', 'aktif');
    //     $this->db->order_by('siswa.no_induk', 'ASC'); 
    //     $query = $this->db->get();
    //     return $query->result();
    // }

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

    //kelola data produk
    public function insert_produk($data)
    {
        $this->db->insert('produk', $data);
    }
    public function select_produk()
    {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori', 'left');
        $this->db->join('supplier', 'produk.id_supplier = supplier.id_supplier', 'left');
        $this->db->where('supplier.id_supplier', $this->session->userdata('id'));
        return $this->db->get()->result();
    }
    public function select_produk_admin($supplier)
    {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.id_kategori = kategori.id_kategori', 'left');
        $this->db->join('supplier', 'produk.id_supplier = supplier.id_supplier', 'left');
        $this->db->where('supplier.id_supplier', $supplier);
        return $this->db->get()->result();
    }
    public function edit_produk($id)
    {
        $this->db->select('*');
        $this->db->from('produk');
        $this->db->where('id_produk', $id);
        return $this->db->get()->row();
    }
    public function update_produk($id, $data)
    {
        $this->db->where('id_produk', $id);
        $this->db->update('produk', $data);
    }
    public function delete_produk($id)
    {
        $this->db->where('id_produk', $id);
        $this->db->delete('produk');
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
