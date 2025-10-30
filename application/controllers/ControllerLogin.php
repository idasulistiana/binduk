<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControllerLogin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login');
        $this->load->library(['session', 'form_validation']);
        // 🔒 Mencegah cache (agar tombol back tidak bisa ke halaman login)
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function index()
    {
        // 🔒 Cegah akses halaman login jika sudah login
        if ($this->session->userdata('logged_in')) {
            redirect(base_url('dashboard'));
        }

        // Validasi form login
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('content/login');
        } else {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);

            // 🔍 Cek ke model Login
            $login = $this->Login->auth($username, $password);


            if ($login) {
                // Ambil data dari hasil query model
                $id_user  = $login->id_user;
                $username = $login->username;
                $level    = $login->level_user; // pastikan nama kolom di DB adalah 'level_user'

                // 💾 Simpan data session
                $this->session->set_userdata([
                    'id_user'    => $id_user,
                    'username'   => $username,
                    'level_user' => $level,
                    'logged_in'  => TRUE
                ]);

                // 🔔 Pesan sambutan
                $this->session->set_flashdata('success', 'Selamat Datang, ' . $username . '!');

                // 🔁 Arahkan ke dashboard
                redirect(base_url('dashboard'));
            } else {
                // ❌ Jika username / password salah
                $this->session->set_flashdata('error', 'Username atau Password salah!');
                redirect(base_url('login'));
            }
        }
    }

    public function logout()
    {
        // 🔐 Hapus semua session
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Anda berhasil logout!');
        redirect('login');
    }

    // 🔒 Fungsi proteksi (optional)
    public function protect()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Anda belum login!');
            redirect('login');
        }
    }
}
