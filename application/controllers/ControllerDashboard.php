<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControllerDashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model', 'dashboard');
        $this->load->helper('url');
        $this->load->library(['session', 'form_validation']);
        // Cek apakah sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('controllerLogin');
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard Buku Induk Siswa';
        $data['total_siswa'] = $this->dashboard->get_total_siswa();
        $data['siswa_laki'] = $this->dashboard->get_siswa_laki();
        $data['siswa_perempuan'] = $this->dashboard->get_siswa_perempuan();
        $data['total_kelas'] = $this->dashboard->get_total_kelas();
        $data['identitas'] = $this->dashboard->get_identitas_sekolah();
        $data['jumlah_siswa'] = $this->dashboard->getJumlahSiswaPerRombel();
        $data['total_per_kelas'] = $this->dashboard-> getTotalPerKelas();
        $data['total_keseluruhan'] = $this->dashboard->getTotalKeseluruhan();
	    $data['level_user'] = $this->session->userdata('level_user');

        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/dashboard_view', $data);
        $this->load->view('Layout/footer');
        $this->load->view('Layout/head');
    }
    
}
