<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function get_total_siswa()
    {
        return $this->db->count_all('siswa');
    }

    public function get_siswa_laki()
    {
        return $this->db->where('gender', 'Laki-laki')->from('siswa')->count_all_results();
    }

    public function get_siswa_perempuan()
    {
        return $this->db->where('gender', 'Perempuan')->from('siswa')->count_all_results();
    }

    public function get_total_kelas()
    {
        return $this->db->count_all('kelas');
    }

    public function get_identitas_sekolah()
    {
        return $this->db->get('identitas_sekolah')->row();
    }
   public function getJumlahSiswaPerRombel()
    {
       
        return $this->db->get('kelas')->result();
    }

    public function getTotalPerKelas()
    {
        $query = $this->db->select('
                nama_kelas, 
                L, 
                P, 
                (L + P) as total_siswa
            ')
            ->from('kelas')
            ->order_by('nama_kelas', 'ASC')
            ->get();

        return $query->row();// hasil banyak baris (per kelas)
    }

    public function getTotalKeseluruhan()
    {
        $query = $this->db->select('
            SUM(L) as total_L, 
            SUM(P) as total_P, 
            (SUM(L) + SUM(P)) as total_siswa
        ')
        ->from('kelas')
        ->get();

        return $query->row(); // hasil satu baris (objek)
    }

}
