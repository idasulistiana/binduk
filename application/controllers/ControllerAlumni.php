<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerAlumni extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Alumni_model');
        $this->load->model('Kelas_model'); // untuk dropdown kelas
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = 'Data Alumni';
        $data['kelas'] = $this->Kelas_model->get_all(); 
        $data['alumni'] = $this->Alumni_model->get_all_alumni();
        $data['level_user'] = $this->session->userdata('level_user');
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/alumni_view', $data); // view Ekskul
        $this->load->view('Layout/footer', $data);
    }
    

    // Get alumni via AJAX (filter by kelas)
    public function get_alumni() {
        $kelas_id = $this->input->post('kelas');
        $data = $this->Alumni_model->get_all_alumni();
        echo json_encode(['data' => $data]);
    }

    // Hapus data alumni
    public function delete($no_induk)
    {
        // daftar tabel yang dicek
        $tabel_cek = ['rekap_kehadiran', 'klapper', 'nilai', 'nilai_ekskul'];
        $tabel_terkait = [];

        foreach ($tabel_cek as $tabel) {
            $this->db->where('no_induk', $no_induk);
            $cek = $this->db->get($tabel)->num_rows();
            if ($cek > 0) {
                $tabel_terkait[] = $tabel;
            }
        }

        if (!empty($tabel_terkait)) {
            $nama_tabel = implode(', ', $tabel_terkait);
            $this->session->set_flashdata('error', 'Data siswa tidak bisa dihapus karena masih memiliki data terkait di tabel: ' . $nama_tabel);
            redirect('alumni');
        } else {
            // hapus siswa
            $this->db->where('no_induk', $no_induk);
            $this->db->delete('siswa');
            $this->session->set_flashdata('success', 'Data siswa berhasil dihapus.');
            redirect('alumni');
        }
    }



     // Tampilkan form edit
    public function update($no_induk) {
        $data['alumni'] = $this->Alumni_model->get_by_no_induk($no_induk);
        if (!$data['alumni']) {
            show_404();
        }

        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/edit_alumni_view', $data); // view Ekskul
        $this->load->view('Layout/footer', $data);
    }

    // Proses update data alumni
    public function update_alumni() {
        $no_induk = $this->input->post('no_induk');
        $data_siswa = [
            'nisn'      => $this->input->post('nisn'),
            'nama_siswa'    => $this->input->post('nama_siswa'),
            'gender'        => $this->input->post('gender'),
            'tempat_lahir'  => $this->input->post('tempat_lahir'),
            'tgl_lahir'     => $this->input->post('tgl_lahir'),
            'agama'         => $this->input->post('agama'),
            'alamat'        => $this->input->post('alamat'),
            'nama_ayah'     => $this->input->post('nama_ayah'),
            'nama_ibu'      => $this->input->post('nama_ibu'),
            'status'        => $this->input->post('status')
        ];

    $tahun_lulus = $this->input->post('tahun_lulus');

    // update data siswa
    $this->Alumni_model->update_alumni($no_induk, $data_siswa);

    // update tahun lulus ke tabel klapper
    $this->Alumni_model->save_tahun_lulus($no_induk, $tahun_lulus);

        $this->session->set_flashdata('success', 'Data alumni berhasil diperbarui.');
        redirect('alumni');
    }
  
}
