<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {

    private $table = 'absensi';

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id_absensi, $data)
    {
        return $this->db->where('id_absensi', $id_absensi)
                        ->update($this->table, $data);
    }

    public function delete($id_absensi)
    {
        $this->db->trans_start();

        // Hapus detail absensi
        $this->db->where('id_absensi', $id_absensi);
        $this->db->delete('absensi_detail');

        // Hapus header absensi
        $this->db->where('id_absensi', $id_absensi);
        $this->db->delete('absensi');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->session->set_flashdata(
                'error',
                'Data absensi gagal dihapus.'
            );

        } else {

            $this->session->set_flashdata(
                'success',
                'Data absensi berhasil dihapus.'
            );

        }

        redirect('data_absensi_daily');
    }

    public function get_by_id($id_absensi)
    {
        return $this->db->get_where($this->table, [
            'id_absensi' => $id_absensi
        ])->row();
    }
    public function get_all()
    {
        return $this->db
            ->select('a.*, k.nama_kelas')
            ->from('absensi a')
            ->join('kelas k','k.id_kelas = a.id_kelas')
            ->order_by('a.tgl','DESC')
            ->get()
            ->result();
    }
    public function get_absensi_by_id($id_absensi)
    {
        return $this->db
            ->select('a.*, k.nama_kelas')
            ->from('absensi a')
            ->join('kelas k','k.id_kelas=a.id_kelas')
            ->where('a.id_absensi',$id_absensi)
            ->get()
            ->row();
    }
    public function get_detail_absensi($id_absensi)
    {
        return $this->db
            ->select('
                ad.*,
                s.no_induk,
                s.nama_siswa,
                k.nama_kelas,
                a.tgl
            ')
            ->from('absensi_detail ad')
            ->join('siswa s', 's.no_induk = ad.no_induk')
            ->join('absensi a', 'a.id_absensi = ad.id_absensi')
            ->join('kelas k', 'k.id_kelas = a.id_kelas')
            ->where('ad.id_absensi', $id_absensi)
            ->get()
            ->result();
    }
    public function get_absensi($id)
    {
        return $this->db
            ->where('id_absensi',$id)
            ->get('absensi')
            ->row();
    }
    public function update_absensi($id,$data)
    {
        $this->db->where('id_absensi',$id);

        return $this->db->update(
            'absensi',
            $data
        );
    }
    
}
