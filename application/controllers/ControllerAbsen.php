<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Absensi_model');
        $this->load->model('Absensi_detail_model');
        $this->load->database();
    }

    // ================= CREATE =================
    public function store()
    {
        $this->db->trans_begin();

        try {
            $status_kelas = $this->input->post('status_kelas');

            $id_absensi = $this->Absensi_model->insert([
                'tanggal'      => $this->input->post('tanggal'),
                'id_kelas'     => $this->input->post('id_kelas'),
                'status_kelas' => $status_kelas,
                'semester'     => $this->input->post('semester'),
                'tahun_ajaran' => $this->input->post('tahun_ajaran')
            ]);

            if ($status_kelas == 0) {
                $this->_save_detail($id_absensi);
            }

            $this->_commit();

        } catch (Exception $e) {
            $this->_rollback($e->getMessage());
        }
    }

    // ================= READ =================
    public function edit($id_absensi)
    {
        $data['absensi'] = $this->Absensi_model->get_by_id($id_absensi);
        $data['detail']  = $this->Absensi_detail_model
                                ->get_by_absensi($id_absensi);

        echo json_encode($data);
    }

    // ================= UPDATE =================
    public function update($id_absensi)
    {
        $this->db->trans_begin();

        try {
            $status_kelas = $this->input->post('status_kelas');

            // update header
            $this->Absensi_model->update($id_absensi, [
                'status_kelas' => $status_kelas
            ]);

            // hapus detail lama
            $this->Absensi_detail_model
                 ->delete_by_absensi($id_absensi);

            // insert detail baru jika ada
            if ($status_kelas == 0) {
                $this->_save_detail($id_absensi);
            }

            $this->_commit();

        } catch (Exception $e) {
            $this->_rollback($e->getMessage());
        }
    }

    // ================= DELETE =================
    public function delete($id_absensi)
    {
        $this->db->trans_begin();

        try {
            // detail terhapus otomatis (ON DELETE CASCADE)
            $this->Absensi_model->delete($id_absensi);

            $this->_commit();

        } catch (Exception $e) {
            $this->_rollback($e->getMessage());
        }
    }

    // ================= HELPER =================
    private function _save_detail($id_absensi)
    {
        $siswa = $this->input->post('siswa');

        if (empty($siswa)) {
            throw new Exception('Detail siswa tidak hadir wajib diisi');
        }

        $detail = [];
        foreach ($siswa as $row) {
            $detail[] = [
                'id_absensi' => $id_absensi,
                'no_induk'   => $row['no_induk'],
                'keterangan' => $row['keterangan'],
                'catatan'    => $row['catatan'] ?? null
            ];
        }

        $this->Absensi_detail_model->insert_batch($detail);
    }

    private function _commit()
    {
        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Transaksi gagal');
        }

        $this->db->trans_commit();

        echo json_encode([
            'status' => true,
            'message' => 'Proses berhasil'
        ]);
    }

    private function _rollback($message)
    {
        $this->db->trans_rollback();

        echo json_encode([
            'status' => false,
            'message' => $message
        ]);
        exit;
    }
}
