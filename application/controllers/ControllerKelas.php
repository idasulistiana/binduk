<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class ControllerKelas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kelas_model');    // Model Kelas
        $this->load->library('form_validation');
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }
    }

    // Halaman utama daftar kelas
    public function index() {
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['level_user'] = $this->session->userdata('level_user');
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/kelas_view', $data); // view Kelas
        $this->load->view('Layout/footer');
    }

    // Menambah kelas baru
    public function add_kelas() {
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['kelas'] = $this->Kelas_model->get_all();
            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/kelas_view', $data);
            $this->load->view('Layout/footer');
            echo validation_errors();
        } else {
            $nama_kelas = $this->input->post('nama_kelas');

            // Cek apakah kelas sudah ada
            $cek = $this->Kelas_model->get_by_name($nama_kelas);
            if ($cek) {
                $this->session->set_flashdata('error', 'Kelas ' . $nama_kelas . ' sudah ada.');
                redirect('kelas');
                return;
            }

            $data_insert = ['nama_kelas' => $nama_kelas];
            $this->Kelas_model->insert_kelas($data_insert);

            $this->session->set_flashdata('success', 'Data kelas berhasil ditambahkan');
            redirect('kelas');
        }
    }

    // Form edit kelas
    public function edit_kelas($id) {
        $data['kelas'] = $this->Kelas_model->get_by_id($id);
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/edit_kelas', $data);
        $this->load->view('Layout/footer');
    }

    // Update kelas
    public function update_kelas($id) {
        $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_kelas($id);
            echo validation_errors();
        } else {
            $nama_kelas = $this->input->post('nama_kelas');

            $data_update = ['nama_kelas' => $nama_kelas];
            $this->Kelas_model->update_kelas($id, $data_update);

            $this->session->set_flashdata('success', 'Data kelas berhasil diupdate');
            redirect('kelas');
        }
    }

    // Hapus kelas
    public function delete_kelas($id) {
        $this->Kelas_model->delete_kelas($id);
        $this->session->set_flashdata('success', 'Data kelas berhasil dihapus');
        redirect('kelas');
    }
}
