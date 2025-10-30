<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class ControllerMapel extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mapel_model');    // Model Mapel
        $this->load->library('form_validation');
         // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }
    }

    // Halaman utama daftar mapel
    public function index() {
        $data['mapel'] = $this->Mapel_model->get_all();
        $data['level_user'] = $this->session->userdata('level_user');
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/mapel_view', $data); // view Mapel
        $this->load->view('Layout/footer');
    }

    // Menambah mapel baru
    public function add_mapel() {
        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['mapel'] = $this->Mapel_model->get_all();
            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/mapel_view', $data);
            $this->load->view('Layout/footer');
            echo validation_errors();
        } else {
            $nama_mapel = $this->input->post('nama_mapel');

            // Cek apakah mapel sudah ada
            $cek = $this->Mapel_model->get_by_name($nama_mapel);
            if ($cek) {
                $this->session->set_flashdata('error', 'Mata Pelajaran ' . $nama_mapel . ' sudah ada.');
                redirect('mapel');
                return;
            }

            $data_insert = ['nama_mapel' => $nama_mapel];
            $this->Mapel_model->insert_mapel($data_insert);

            $this->session->set_flashdata('success', 'Data mata pelajaran berhasil ditambahkan');
            redirect('mapel');
        }
    }

    // Form edit mapel
    public function edit_mapel($id) {
        $data['mapel'] = $this->Mapel_model->get_by_id($id);
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/edit_mapel', $data);
        $this->load->view('Layout/footer');
    }

    // Update mapel
    public function update_mapel($id) {
        $this->form_validation->set_rules('nama_mapel', 'Nama Mata Pelajaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_mapel($id);
            echo validation_errors();
        } else {
            $nama_mapel = $this->input->post('nama_mapel');

            $data_update = ['nama_mapel' => $nama_mapel];
            $this->Mapel_model->update_mapel($id, $data_update);

            $this->session->set_flashdata('success', 'Data mata pelajaran berhasil diupdate');
            redirect('mapel');
        }
    }

    // Hapus mapel
    public function delete_mapel($id) {
        $this->Mapel_model->delete_mapel($id);
        $this->session->set_flashdata('success', 'Data mata pelajaran berhasil dihapus');
        redirect('mapel');
    }
}
