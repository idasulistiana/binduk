<?php
defined('BASEPATH') or exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Dashboard_model extends CI_Model
{
   public function get_total_siswa()
    {
        return $this->db
            ->where('status', 'Aktif') // hanya siswa aktif
            ->from('siswa')
            ->count_all_results();
    }


   public function get_siswa_laki()
    {
        return $this->db
            ->where('gender', 'Laki-laki')
            ->where('status', 'Aktif') // hanya yang aktif
            ->from('siswa')
            ->count_all_results();
    }

    public function get_siswa_perempuan()
    {
        return $this->db
            ->where('gender', 'Perempuan')
            ->where('status', 'Aktif') // hanya yang aktif
            ->from('siswa')
            ->count_all_results();
    }

     public function get_total_kelas()
    {
        return $this->db
            ->where('status', 1) // hanya yang status = 1
            ->from('kelas')
            ->count_all_results();
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
                SUM(CASE WHEN gender = "Laki-Laki" THEN 1 ELSE 0 END) AS total_L,
                SUM(CASE WHEN gender = "Perempuan" THEN 1 ELSE 0 END) AS total_P,
                COUNT(no_induk) AS total_siswa
            ')
            ->from('siswa')
            ->where('status', 'aktif') // opsional: hanya siswa aktif
            ->get();

        return $query->row(); // hasil satu baris
    }

    public function getJumlahSiswaPerKelas()
    {
        $query = $this->db->select('
                kelas.nama_kelas,
                SUM(CASE WHEN siswa.gender = "Laki-Laki" THEN 1 ELSE 0 END) AS total_L,
                SUM(CASE WHEN siswa.gender = "Perempuan" THEN 1 ELSE 0 END) AS total_P,
                COUNT(siswa.no_induk) AS total_siswa
            ')
            ->from('siswa')
            ->join('kelas', 'kelas.id_kelas = siswa.kelas', 'left')
            ->where('siswa.status', 'aktif') // opsional: hanya siswa aktif
            ->group_by('kelas.id_kelas')
            ->get();

        return $query->result(); // hasil banyak baris (array objek)
    }


}
