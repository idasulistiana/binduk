<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class ControllerKlapper extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Klapper_model');
        $this->load->model('DataMaster');
        $this->load->library('form_validation');
        $this->load->library('Pdf');
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }
    }

    public function index() {
        $data['siswa'] = $this->DataMaster->select_siswa(); 
        $data['klapper'] = $this->Klapper_model->get_all();
        $data['level_user'] = $this->session->userdata('level_user');
        $this->load->view('Layout/head');
		$this->load->view('Layout/navbar');
		$this->load->view('Layout/aside');
		$this->load->view('Content/klapper', $data);
		$this->load->view('Layout/footer');
    }
    public function get_siswa_autocomplete() {
        $term = $this->input->get('term'); // kata yang diketik user
        $this->load->model('DataMaster');
        $siswa = $this->DataMaster->get_siswa_like($term); // ambil data sesuai input

        $result = [];
        foreach ($siswa as $s) {
            $result[] = [
                'label' => $s->no_induk . ' - ' . $s->nama_siswa, // ditampilkan di dropdown
                'value' => $s->no_induk
            ];
        }
        echo json_encode($result);
    }

   public function add_klapper() {
    $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required');
    $this->form_validation->set_rules('no_induk', 'No Induk', 'required');

    if ($this->form_validation->run() == FALSE) {
        $data['siswa']   = $this->DataMaster->select_siswa();
        $data['klapper'] = $this->Klapper_model->get_all();

        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/klapper', $data);
        $this->load->view('Layout/footer');
        echo validation_errors();
    } else {
        $no_induk = $this->input->post('no_induk');

        // ✅ Validasi: pastikan no_induk ada di tabel siswa
        $cek_siswa = $this->DataMaster->get_siswa_by_no_induk($no_induk);
        if (!$cek_siswa) {
            $this->session->set_flashdata('error', 'No Induk ' . $no_induk . ' tidak ditemukan di data siswa.');
            redirect('riwayatkelas/add_klapper');
            return;
        }

        // ✅ Cek apakah no_induk sudah ada di tabel klapper
        $cek_klapper = $this->Klapper_model->get_by_no_induk($no_induk);
        if ($cek_klapper) {
            $this->session->set_flashdata('error', 'Siswa dengan No Induk ' . $no_induk . ' sudah ada di klapper.');
            redirect('riwayatkelas/add_klapper');
            return;
        }

        // inisialisasi semua kelas null
        $kelas_db = [];
        for ($i = 1; $i <= 6; $i++) {
            $input_name = 'kelas' . $i;
            $db_name    = 'kelas_' . $i;  // nama kolom di DB
            $kelas_db[$db_name] = $this->input->post($input_name) ?: NULL;
        }

        $data = array(
            'no_induk'          => $no_induk,
            'tgl_masuk'         => $this->input->post('tgl_masuk'),
            'keterangan' => $this->input->post('keterangan')
        );

        // gabungkan data kelas
        $data_insert = array_merge($data, $kelas_db);

        $this->Klapper_model->insert_klapper($data_insert);

        $this->session->set_flashdata('success', 'Data klapper berhasil ditambahkan');
        redirect('riwayatkelas');
        }
    }


    public function update_klapper($id_klapper)
    {
        // Validasi form
        $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Load data lama untuk form edit
            $data['klapper'] = $this->Klapper_model->get_by_id($id_klapper);
            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/edit_klapper', $data);
            $this->load->view('Layout/footer');
            echo validation_errors();
        } else {
            // Ambil data kelas dari POST
            $kelas_db = [];
            for ($i = 1; $i <= 6; $i++) {
                $input_name = 'kelas' . $i;
                $db_name = 'kelas_' . $i; // nama kolom di DB
                $kelas_db[$db_name] = $this->input->post($input_name) ?: NULL;
            }

            // Data utama
            $data_update = array(
                'tgl_masuk' => $this->input->post('tgl_masuk'),
                'keterangan' => $this->input->post('keterangan')
            );

            // Gabungkan data kelas
            $data_update = array_merge($data_update, $kelas_db);

            // Panggil model update
            $this->Klapper_model->update_klapper($id_klapper, $data_update);

            $this->session->set_flashdata('success', 'Data klapper berhasil diupdate');
            redirect('riwayatkelas');
        }
    }

    public function delete_klapper($no_induk) {
        $this->Klapper_model->delete_klapper($no_induk);
        $this->session->set_flashdata('success', 'Data klapper berhasil dihapus');
        redirect('riwayatkelas');
    }

    public function download_kelas() {
        // Ambil semua data klapper beserta data siswa
        $klapper = $this->Klapper_model->get_all(); // Pastikan method ini ada di model

        // Buat objek TCPDF baru
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Atur informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sekolah');
        $pdf->SetTitle('Data Kelas Siswa');
        $pdf->SetHeaderData('', 0, 'SDN Tegal Alur 04 PG', '');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(10, 20, 10);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();

        // Buat header tabel
        $html = '<h2>Data Kelas Siswa</h2>';
        $html .= '<table border="1" cellpadding="5">
            <tr style="background-color:#f2f2f2; text-align:center;">
                <th>No</th>
                <th>No Induk</th>
                <th>Nama Siswa</th>
                <th>Tanggal Masuk</th>
                <th>Keterangan</th>
                <th>Kelas 1</th>
                <th>Kelas 2</th>
                <th>Kelas 3</th>
                <th>Kelas 4</th>
                <th>Kelas 5</th>
                <th>Kelas 6</th>
            </tr>';

        // Isi data klapper
        $no = 1;
        foreach($klapper as $k) {
            $html .= '<tr style="text-align:center;">
                <td>'.$no.'</td>
                <td>'.$k->no_induk.'</td>
                <td>'.$k->nama_siswa.'</td>
                <td>'.$k->tgl_masuk.'</td>
                <td>'.($k->keterangan ? $k->keterangan : '-').'</td>
                <td>'.($k->kelas_1 ? $k->kelas_1 : '-').'</td>
                <td>'.($k->kelas_2 ? $k->kelas_2 : '-').'</td>
                <td>'.($k->kelas_3 ? $k->kelas_3 : '-').'</td>
                <td>'.($k->kelas_4 ? $k->kelas_4 : '-').'</td>
                <td>'.($k->kelas_5 ? $k->kelas_5 : '-').'</td>
                <td>'.($k->kelas_6 ? $k->kelas_6 : '-').'</td>
            </tr>';
            $no++;
        }

        $html .= '</table>';

        // Tulis HTML ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF ke browser
        $pdf->Output('Data_Kelas_Siswa.pdf', 'D'); // 'D' = download
    }
    public function import_kelas()
    {
        if(isset($_FILES['file_csv']['name'])){
            $file_mimes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];

            if(in_array($_FILES['file_csv']['type'], $file_mimes)){
                $file = $_FILES['file_csv']['tmp_name'];

                if (($handle = fopen($file, "r")) !== FALSE) {
                    $row = 0;
                    $failed_no_induk = []; // untuk menyimpan no_induk yang gagal
                    $success_count = 0;

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if($row == 0){ 
                            $row++; // skip header
                            continue; 
                        }

                        $no_induk = $data[0]; // kolom 0 = no_induk

                        // ✅ Pastikan no_induk ada di tabel siswa
                        if(!$this->DataMaster->get_siswa_by_no_induk($no_induk)){
                            $failed_no_induk[] = $no_induk . " (tidak ditemukan di data siswa)";
                            $row++;
                            continue;
                        }

                        // ✅ Cek duplicate di tabel klapper
                        if($this->Klapper_model->get_by_no_induk($no_induk)){
                            $failed_no_induk[] = $no_induk . " (sudah ada di klapper)";
                            $row++;
                            continue;
                        }

                        // Ambil data dari CSV (asumsi urutan kolom: no_induk, tgl_masuk, keterangan, kelas1..kelas6)
                        $data_insert = [
                            'no_induk'  => $data[0],
                            'tgl_masuk' => $data[1],
                            'keterangan'=> $data[2],
                            'kelas_1'   => $data[3] ?: NULL,
                            'kelas_2'   => $data[4] ?: NULL,
                            'kelas_3'   => $data[5] ?: NULL,
                            'kelas_4'   => $data[6] ?: NULL,
                            'kelas_5'   => $data[7] ?: NULL,
                            'kelas_6'   => $data[8] ?: NULL
                        ];

                        $this->Klapper_model->insert_klapper($data_insert);
                        $success_count++;
                        $row++;
                    }

                    fclose($handle);

                    // Set flashdata
                    if(!empty($failed_no_induk)){
                        $this->session->set_flashdata('failed', 'Beberapa No Induk gagal diimpor: '.implode(', ', $failed_no_induk));
                    }

                    if($success_count > 0){
                        $this->session->set_flashdata('success', "Data Kelas Berhasil Diimpor ($success_count siswa)!");
                    }

                    redirect('riwayatkelas/add_klapper');
                }
            } else {
                $this->session->set_flashdata('error', 'File yang diunggah bukan CSV!');
                redirect('riwayatkelas/add_klapper');
            }
        } else {
            $this->session->set_flashdata('error', 'File CSV belum diunggah!');
            redirect('riwayatkelas/add_klapper');
        }
    }


}
