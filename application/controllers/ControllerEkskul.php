<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class ControllerEkskul extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Ekskul_model'); // Model Ekskul
        $this->load->library('form_validation');
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }
    }

    // Halaman utama daftar ekskul
    public function index() {
        $data['ekskul'] = $this->Ekskul_model->get_all();
        $data['level_user'] = $this->session->userdata('level_user');
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/ekskul_view', $data); // view Ekskul
        $this->load->view('Layout/footer');
    }

    // Menambah ekskul baru
    public function add_ekskul() {
        $this->form_validation->set_rules('nama_ekskul', 'Nama Ekskul', 'required');
        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['ekskul'] = $this->Ekskul_model->get_all();
            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/ekskul_view', $data);
            $this->load->view('Layout/footer');
            echo validation_errors();
        } else {
            $nama_ekskul = $this->input->post('nama_ekskul');
            $nama_pj = $this->input->post('nama_pj');

            // Cek apakah ekskul sudah ada
            $cek = $this->Ekskul_model->get_by_name($nama_ekskul);
            if ($cek) {
                $this->session->set_flashdata('error', 'Ekskul ' . $nama_ekskul . ' sudah ada.');
                redirect('ekskul');
                return;
            }

            $data_insert = [
                'nama_ekskul' => $nama_ekskul,
                'nama_pj'     => $nama_pj
            ];

            $this->Ekskul_model->insert_ekskul($data_insert);

            $this->session->set_flashdata('success', 'Data ekskul berhasil ditambahkan');
            redirect('ekskul');
        }
    }

    // Form edit ekskul
    public function edit_ekskul($id) {
        $data['ekskul'] = $this->Ekskul_model->get_by_id($id);
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/ekskul_edit', $data);
        $this->load->view('Layout/footer');
    }

    // Update ekskul
    public function update_ekskul($id) {
        $this->form_validation->set_rules('nama_ekskul', 'Nama Ekskul', 'required');
        $this->form_validation->set_rules('nama_pj', 'Nama Penanggung Jawab', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_ekskul($id);
            echo validation_errors();
        } else {
            $nama_ekskul = $this->input->post('nama_ekskul');
            $nama_pj     = $this->input->post('nama_pj');

            $data_update = [
                'nama_ekskul' => $nama_ekskul,
                'nama_pj'     => $nama_pj
            ];

            $this->Ekskul_model->update_ekskul($id, $data_update);

            $this->session->set_flashdata('success', 'Data ekskul berhasil diupdate');
            redirect('ekskul');
        }
    }

    // Hapus ekskul
    public function delete_ekskul($id) {
        $this->Ekskul_model->delete_ekskul($id);
        $this->session->set_flashdata('success', 'Data ekskul berhasil dihapus');
        redirect('ekskul');
    }
}
