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

            // ================= DATA POST =================
            $id_kelas   = $this->input->post('id_kelas');   
            if (is_array($id_kelas)) {
                $id_kelas = $id_kelas[0];
            }
            $no_induk   = $this->input->post('no_induk');      // array
            $keterangan = $this->input->post('keterangan');   // array

            // ================= AUTO SYSTEM =================
            $tanggal = date('Y-m-d');

            // Logika semester otomatis
            $bulan = date('m');
            $semester = ($bulan >= 7) ? 1 : 2;

            // Tahun ajaran otomatis
            if ($bulan >= 7) {
                $tahun_ajaran = date('Y') . '/' . (date('Y') + 1);
            } else {
                $tahun_ajaran = (date('Y') - 1) . '/' . date('Y');
            }

            // Status kelas otomatis
            $status_kelas = empty($no_induk) || empty($no_induk[0]) ? 1 : 0;

            // ================= INSERT ABSENSI =================
            $absensi = [
                'tgl'           => $tanggal,
                'id_kelas'      => $id_kelas,
                'status_kelas'  => $status_kelas,
                'semester'      => $semester,
                'tahun_ajaran'  => $tahun_ajaran,
                'created'       => date('Y-m-d H:i:s')
            ];

            // DEBUG
echo "<pre>";
var_dump($absensi);
echo "</pre>";
die;
            $id_absensi = $this->Absensi_model->insert($absensi);


            // ================= INSERT DETAIL =================
            if ($status_kelas == 0) {

                foreach ($no_induk as $i => $ni) {

                    if (!empty($ni)) {

                        // Cari id_siswa dari no_induk
                        $siswa = $this->DataMaster->get_by_no_induk($ni);

                        if ($siswa) {

                            $this->Absensi_detail_model->insert([
                                'id_absensi' => $id_absensi,
                                'id_siswa'   => $siswa->id_siswa,
                                'keterangan' => $keterangan[$i]
                            ]);

                        }
                    }
                }
            }

            $this->db->trans_complete();

            // ================= RESULT =================
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Gagal menyimpan absensi');
            } else {
                $this->session->set_flashdata('success', 'Absensi berhasil disimpan');
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

