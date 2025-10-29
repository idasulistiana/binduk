<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerRekapKehadiran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Rekap_kehadiran_model'); // Model Rekap Kehadiran
        $this->load->model('Kelas_model'); // Model Rekap Kehadiran
        $this->load->model('DataMaster');    
        $this->load->library('form_validation');
        $this->load->library('tcpdf');
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }

    }

    // ========================
    // Halaman utama: daftar kehadiran
    // ========================
    public function index() {
        $data['rekap'] = $this->Rekap_kehadiran_model->get_all_rekap();
        $data['siswa'] = $this->Rekap_kehadiran_model->get_all_siswa();
        $data['level_user'] = $this->session->userdata('level_user');
        $data['kelas'] = $this->Kelas_model->get_all();
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/rekap_kehadiran_view', $data); // View utama
        $this->load->view('Layout/footer');
    }

    // ========================
    // Tambah data kehadiran
    // ========================
    public function add_rekap()
    {
        // Validasi form
        $this->form_validation->set_rules('no_induk', 'No Induk', 'required');
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('semester', 'Semester', 'required');
        $this->form_validation->set_rules('sakit', 'Sakit', 'required|numeric');
        $this->form_validation->set_rules('izin', 'Izin', 'required|numeric');
        $this->form_validation->set_rules('tanpa_keterangan', 'Tanpa Keterangan', 'required|numeric');
        $this->form_validation->set_rules('tahun_ajaran', 'Tahun Ajaran', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['rekap'] = $this->Rekap_kehadiran_model->get_all_rekap();
            $data['siswa'] = $this->Rekap_kehadiran_model->get_all_siswa();
            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/rekap_kehadiran_view', $data);
            $this->load->view('Layout/footer');
        } else {
            $no_induk = $this->input->post('no_induk');
            $id_kelas = $this->input->post('id_kelas');
            $semester = $this->input->post('semester');

            // 🔍 Cek duplikat berdasarkan no_induk + kelas + semester
            $cek_duplicate = $this->Rekap_kehadiran_model->check_duplicate($no_induk, $id_kelas, $semester);

            if ($cek_duplicate) {
                $this->session->set_flashdata('error', '❌ Data siswa ini untuk kelas dan semester tersebut sudah ada.');
            } else {
                // Insert data
                $data_insert = [
                    'no_induk'          => $no_induk,
                    'id_kelas'          => $id_kelas,
                    'semester'          => $semester,
                    'sakit'             => $this->input->post('sakit'),
                    'izin'              => $this->input->post('izin'),
                    'tanpa_keterangan'  => $this->input->post('tanpa_keterangan'),
                    'tahun_ajaran'      => $this->input->post('tahun_ajaran')
                ];

                $this->Rekap_kehadiran_model->insert_rekap_absen_siswa($data_insert);
                $this->session->set_flashdata('success', '✅ Data kehadiran berhasil ditambahkan.');
            }
            // ambil URL sebelumnya
               $previous_url = $this->session->userdata('previous_url');

            // Jika tidak ada previous_url, fallback ke halaman all_nilai_siswa dengan $no_induk
            if (!$previous_url) {
                $no_induk = $this->input->post('no_induk'); // ambil dari form
                $previous_url = site_url("nilai/all_nilai_siswa/$no_induk");
            }

            // Redirect
            redirect($previous_url);


        }
    }
    // ========================
    // Form Edit
    // ========================
        public function edit_siswa($id) {

            $data['rekap'] = $this->Rekap_kehadiran_model->get_by_id($id);
            $this->session->set_userdata('previous_url', $_SERVER['HTTP_REFERER']);

            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/edit_rekap_kehadiran', $data);
            $this->load->view('Layout/footer');
        }

        public function update_rekap() {
            $id_rekap = $this->input->post('id_rekap');

            $data_update = [
                'sakit'             => $this->input->post('sakit'),
                'izin'              => $this->input->post('izin'),
                'tanpa_keterangan'  => $this->input->post('tanpa_keterangan'),
                'tahun_ajaran'      => $this->input->post('tahun_ajaran'),
            ];

            $update = $this->Rekap_kehadiran_model->update_kehadiran($id_rekap, $data_update);

            if ($update) {
                $this->session->set_flashdata('success', '✅ Data kehadiran berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', '❌ Gagal memperbarui data kehadiran.');
            }

            
                // ambil URL sebelumnya
                $previous_url = $this->session->userdata('previous_url');
                if ($previous_url) {
                    redirect($previous_url);
                } else {
                    redirect('kehadiran'); // fallback kalau tidak ada
                }
        }
    // ========================
    // Hapus data kehadiran
    // ========================
    public function delete_rekap($id) {
        $this->Rekap_kehadiran_model->delete_rekap($id);
        $this->session->set_flashdata('success', 'Data kehadiran berhasil dihapus');
        redirect('kehadiran');
    }

    public function get_siswa()
    {
        $term = $this->input->get('term');
        $data = $this->Rekap_kehadiran_model->get_siswa_autocomplete($term);

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'label' => $row->no_induk . ' - ' . $row->nama_siswa,
                'value' => $row->no_induk
            ];
        }

        echo json_encode($result);
    }

    public function download_rekap_kehadiran()
    {
        // Ambil input dari form
        $kelas = $this->input->post('kelas');         // array id_kelas
        $semester = $this->input->post('semester');   // array semester (Ganjil/Genap)
        
        if(empty($kelas) || empty($semester)) {
            $this->session->set_flashdata('error', 'Pilih kelas dan semester terlebih dahulu.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Ambil data rekap berdasarkan kelas & semester
        $rekap = $this->Rekap_kehadiran_model->get_rekap_by_kelas_semester($kelas, $semester);

        // Buat PDF
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sekolah');
        $pdf->SetTitle('Rekap Kehadiran Siswa');
        $pdf->SetHeaderData('', 0, 'SDN Tegal Alur 04 PG', 'Rekap Kehadiran Siswa');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(10, 20, 10);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetAutoPageBreak(TRUE, 10);
        $pdf->AddPage();

        // Header tabel
        $html = '<h2 style="text-align:center;">Rekap Kehadiran Siswa</h2>';
        $html .= '<table border="1" cellpadding="5">
            <tr style="background-color:#f2f2f2; text-align:center;">
                <th width="30">No</th>
                <th width="70">No Induk</th>
                <th width="140">Nama Siswa</th>
                <th width="70">Kelas</th>
                <th width="70">Semester</th>
                <th width="70">Sakit</th>
                <th width="70">Izin</th>
                <th width="120">Tanpa Keterangan</th>
                <th width="100">Tahun Ajaran</th>
            </tr>';

        $no = 1;
        foreach($rekap as $r){
            $html .= '<tr style="text-align:center;">
                <td>'.$no.'</td>
                <td>'.$r->no_induk.'</td>
                <td>'.$r->nama_siswa.'</td>
                <td>'.$r->nama_kelas.'</td>
                <td>'.$r->semester.'</td>
                <td>'.$r->sakit.'</td>
                <td>'.$r->izin.'</td>
                <td>'.$r->tanpa_keterangan.'</td>
                <td>'.$r->tahun_ajaran.'</td>
            </tr>';
            $no++;
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('Rekap_Kehadiran_Siswa.pdf','D');
    }

   public function import_rekap_kehadiran()
    {
        if (isset($_FILES['file_csv']['name'])) {
            $file_mimes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];

            if (in_array($_FILES['file_csv']['type'], $file_mimes)) {
                $file = $_FILES['file_csv']['tmp_name'];

                if (($handle = fopen($file, "r")) !== FALSE) {
                    $row = 0;
                    $failed_no_induk = []; // untuk menyimpan data gagal
                    $success_count = 0;

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if ($row == 0) {
                            $row++; // Lewati baris header
                            continue;
                        }

                        $no_induk = $data[0]; // kolom 0 = no_induk

                        // ✅ Pastikan no_induk ada di tabel siswa
                        if (!$this->DataMaster->get_siswa_by_no_induk($no_induk)) {
                            $failed_no_induk[] = $no_induk . " (tidak ditemukan di data siswa)";
                            $row++;
                            continue;
                        }

                        // ✅ Cek duplikasi berdasarkan no_induk + semester + tahun_ajaran
                        if ($this->Rekap_kehadiran_model->cek_duplikat($no_induk, $data[4], $data[8])) {
                            $failed_no_induk[] = $no_induk . " (data semester " . $data[4] . " tahun " . $data[8] . " sudah ada)";
                            $row++;
                            continue;
                        }
                        $id_kelas = $this->Kelas_model->get_id_kelas_by_nama($data[1]); // data[2] = nama_kelas
               
                     

                        // Asumsi urutan kolom CSV:
                        // 0=no_induk, 1=nama_siswa, 2=nama_kelas, 3=semester, 4=sakit, 5=izin, 6=tanpa_keterangan, 7=tahun_ajaran
                        $data_insert = [
                            'no_induk'          => $data[0],
                            'id_kelas'          => $data[1],
                            'semester'          => $data[2],
                            'sakit'             => $data[3] ?: 0,
                            'izin'              => $data[4] ?: 0,
                            'tanpa_keterangan'  => $data[5] ?: 0,
                            'tahun_ajaran'      => $data[6]
                        ];

                        $this->Rekap_kehadiran_model->insert_rekap_absen_siswa($data_insert);
                        $success_count++;
                        $row++;
                    }

                    fclose($handle);

                    // 🔔 Set pesan hasil
                    if (!empty($failed_no_induk)) {
                        $this->session->set_flashdata('failed', 'Beberapa data gagal diimpor: ' . implode(', ', $failed_no_induk));
                    }

                    if ($success_count > 0) {
                        $this->session->set_flashdata('success', "Data Rekap Kehadiran berhasil diimpor ($success_count siswa)!");
                    }

                    redirect('ControllerRekapKehadiran');
                }
            } else {
                $this->session->set_flashdata('error', 'File yang diunggah bukan format CSV!');
                redirect('ControllerRekapKehadiran');
            }
        } else {
            $this->session->set_flashdata('error', 'File CSV belum diunggah!');
            redirect('ControllerRekapKehadiran');
        }
    }




}
