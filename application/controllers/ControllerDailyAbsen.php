<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerDailyAbsen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('DataMaster');	
		$this->load->library('form_validation'); // ← ini wajib	
		$this->load->library('pdf');
	  	$this->load->library('session');
        $this->load->helper(['url', 'form']);
		$this->load->model('Kelas_model'); // Model Rekap Kehadiran
        $this->load->model('Absensi_model');
        $this->load->model('Absensi_detail_model');
    }

    public function index() {
        $data['siswa'] = $this->DataMaster->get_all_siswa();
		$data['kelas'] = $this->Kelas_model->get_all_active_class();

       // $this->load->view('Layout/head');
      //  $this->load->view('Layout/navbar');
        //$this->load->view('Layout/aside');
        $this->load->view('Content/absen_siswa_harian', $data);
       // $this->load->view('Layout/footer', $data);
    }
    
   // ================= CREATE =================

    public function store()
    {


        // Validasi hanya kelas
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required');

        if ($this->form_validation->run() == FALSE) {

            $data = [
                'kelas' =>  $this->Kelas_model->get_all_active_class(),
                'siswa' =>  $this->DataMaster->get_all_siswa()
            ];

            $this->load->view('Content/absen_siswa_harian', $data);

        } else {

    $this->db->trans_start();

    $id_kelas     = $this->input->post('id_kelas');
    $status_kelas = $this->input->post('status_kelas');

    $no_induk     = $this->input->post('no_induk') ?: [];
    $keterangan   = $this->input->post('keterangan') ?: [];

    $tanggal = date('Y-m-d');

    $bulan = date('m');

    $semester = ($bulan >= 7) ? 1 : 2;

    if ($bulan >= 7) {
        $tahun_ajaran = date('Y') . '/' . (date('Y') + 1);
    } else {
        $tahun_ajaran = (date('Y') - 1) . '/' . date('Y');
    }

    $absensi = [
        'tgl'           => $tanggal,
        'id_kelas'      => $id_kelas,
        'status_kelas'  => $status_kelas,
        'semester'      => $semester,
        'tahun_ajaran'  => $tahun_ajaran,
        'created_at'    => date('Y-m-d H:i:s')
    ];

    $id_absensi = $this->Absensi_model->insert($absensi);

    // jika ada siswa tidak hadir
    if ($status_kelas == 0) {

        foreach ($no_induk as $i => $ni) {

            if (empty($ni)) {
                continue;
            }

            $siswa = $this->DataMaster->get_by_no_induk($ni);

            if (!$siswa) {
                continue;
            }

            $this->Absensi_detail_model->insert([
                'id_absensi' => $id_absensi,
                'id_siswa'   => $siswa->id_siswa,
                'keterangan' => $keterangan[$i]
            ]);
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {

        $this->session->set_flashdata(
            'error',
            'Gagal menyimpan absensi'
        );

    } else {

        $this->session->set_flashdata(
            'success',
            'Absensi berhasil disimpan'
        );
    }

    redirect('absensi');
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

