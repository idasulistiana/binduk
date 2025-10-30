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
    public function get_all_siswa() {
        $this->db->order_by('nama_siswa', 'ASC');
        $query = $this->db->get('siswa'); // Nama tabel = siswa
        return $query->result();
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

    //kelola data supplier
    public function select_supplier()
    {
        $this->db->select('*');
        $this->db->from('supplier');
        return $this->db->get()->result();
    }
    public function insert_supplier($data)
    {
        $this->db->insert('supplier', $data);
    }
    public function edit_supplier($id)
    {
        $this->db->select('*');
        $this->db->from('supplier');
        $this->db->where('id_supplier', $id);
        return $this->db->get()->row();
    }
    public function update_supplier($id, $data)
    {
        $this->db->where('id_supplier', $id);
        $this->db->update('supplier', $data);
    }
    public function delete_supplier($id)
    {
        $this->db->where('id_supplier', $id);
        $this->db->delete('supplier');
    }

////////kelola data siswa///////////////////
    public function select_siswa()
    {
        return $this->db->get('siswa')->result();
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

    // Fungsi untuk ambil siswa berdasarkan nisn
    public function get_siswa_by_no_induk($no_induk) {
        $this->db->where('no_induk', $no_induk);
        $query = $this->db->get('siswa');
        return $query->row(); // ambil 1 record
    }
}
                        
/* End of file DataMaster.php */
