<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerDataAbsen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Absensi_model');
        $this->load->model('Kelas_model');
    }

    public function index()
    {
        $data['title'] = 'Data Absensi';

        $data['absensi'] = $this->Absensi_model->get_all();
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/data_absensi_harian', $data);
        $this->load->view('Layout/footer');
    }

    public function edit($id_absensi)
    {
        $data['absensi'] = $this->Absensi_model->get_absensi($id_absensi);
        $data['detail'] = $this->Absensi_model->get_detail_absensi($id_absensi);
        $data['kelas'] = $this->Kelas_model->get_all_active_class();

    
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/edit_absensi', $data);
        $this->load->view('Layout/footer');
    }

   public function update()
    {
        $id_absensi = $this->input->post('id_absensi');

        $header = [

            'tgl' => $this->input->post('tgl'),
            'id_kelas' => $this->input->post('id_kelas'),
            'status_kelas' => $this->input->post('status_kelas'),
            'semester' => $this->input->post('semester'),
            'tahun_ajaran' => $this->input->post('tahun_ajaran')

        ];

        $this->Absensi_model->update_absensi($id_absensi,$header);

        $this->session->set_flashdata(
            'success',
            'Data berhasil diupdate.'
        );

        redirect('data_absensi_daily');
    }

    public function detail($id_absensi)
    {
        $data['title'] = 'Detail Absensi';
        $data['absensi'] = $this->Absensi_model->get_absensi_by_id($id_absensi);
        $data['detail'] = $this->Absensi_model->get_detail_absensi($id_absensi);

        if (!$data['absensi']) {
            show_404();
        }
        
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/absensi_detail_daily', $data);
        $this->load->view('Layout/footer');
    }
    
    public function delete($id_absensi)
    {
        if ($this->Absensi_model->delete($id_absensi)) {

            $this->session->set_flashdata(
                'success',
                'Data absensi berhasil dihapus.'
            );

        } else {

            $this->session->set_flashdata(
                'error',
                'Data absensi gagal dihapus.'
            );

        }

        redirect('data_absensi_daily');
    }
}