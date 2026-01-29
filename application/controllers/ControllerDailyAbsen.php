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
        $this->load->model('Absen_model');
		$this->load->model('Kelas_model'); // Model Rekap Kehadiran
    }

    public function index() {
        $data['siswa'] = $this->DataMaster->get_all_siswa();
		$data['kelas'] = $this->Kelas_model->get_all_active_class();
       // $this->load->view('Layout/head');
      //  $this->load->view('Layout/navbar');
        //$this->load->view('Layout/aside');
        $this->load->view('Content/absen_siswa_harian', $data); // view Ekskul
       // $this->load->view('Layout/footer', $data);
    }
      public function store()
    {
        $no_induk      = $this->input->post('no_induk');
        $id_kelas      = $this->input->post('id_kelas');
        $tanggal       = $this->input->post('tanggal');
        $keterangan    = $this->input->post('keterangan'); // S / I / A
        $tahun_ajaran  = $this->input->post('tahun_ajaran');

        if ($this->Absen_model->exists($no_induk, $tanggal)) {
            $this->session->set_flashdata('error', 'Absen untuk siswa ini pada tanggal tersebut sudah ada.');
            redirect('absen_siswa_harian');
        }

        $data = [
            'no_induk'     => $no_induk,
            'id_kelas'     => $id_kelas,
            'tanggal'      => $tanggal,
            'keterangan'   => $keterangan,
            'tahun_ajaran' => $tahun_ajaran,
        ];

        if ($this->Absen_model->insert($data)) {
            $this->session->set_flashdata('success', 'Data absen berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('failed', 'Gagal menambahkan data absen.');
        }

        redirect('absen_siswa_harian');
    }

    public function update($id)
    {
        $data = [
            'no_induk'     => $this->input->post('no_induk'),
            'id_kelas'     => $this->input->post('id_kelas'),
            'tanggal'      => $this->input->post('tanggal'),
            'keterangan'   => $this->input->post('keterangan'),
            'tahun_ajaran' => $this->input->post('tahun_ajaran'),
        ];

        if ($this->Absen_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Data absen berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('failed', 'Gagal memperbarui data absen.');
        }

        redirect('absen_siswa_harian');
    }

    public function delete($id)
    {
        if ($this->Absen_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data absen berhasil dihapus.');
        } else {
            $this->session->set_flashdata('failed', 'Gagal menghapus data absen.');
        }

        redirect('absen_siswa_harian');
    }
}

