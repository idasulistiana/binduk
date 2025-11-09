<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class ControllerBukuIndukSiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('DataMaster');
        $this->load->model('Buku_induk_siswa_model');
        $this->load->model('Nilai_model');
        $this->load->model('Ekskul_model');
        $this->load->model('Kelas_model');
        $this->load->model('Klapper_model');
        $this->load->model('Rekap_kehadiran_model'); 
        $this->load->library('Pdf');
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }

    }

    // ====================== INDEX (DAFTAR SISWA) ======================
    public function index() {
        $data['siswa'] = $this->DataMaster->get_all_siswa();
        $data['kelas'] = $this->Kelas_model->get_all_active_class();
      
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/buku_induk_siswa', $data);
        $this->load->view('Layout/footer');
    }
    public function get_siswa()
	{
		
		$kelas = $this->input->post('kelas'); // ambil dari filter dropdown
		$data = $this->DataMaster->get_siswa_fornilai($kelas);

		echo json_encode(['data' => $data]); // DataTables biasanya pakai key 'data'
	}
    // ====================== DETAIL SISWA ======================
    public function detail($no_induk = null)
    {
        // --- Ambil data utama siswa ---
        $data['kelas'] = $this->Kelas_model->get_all_active_class();
        $data['siswa'] = $this->Buku_induk_siswa_model->get_siswa_by_no_induk($no_induk);

        if (!$data['siswa']) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan');
            redirect('bukuinduk');
        }

        // --- Ambil semua kelas siswa ---
        $kelas_all = $this->Kelas_model->get_all();
        $kelas_nilai = [];

        foreach ($kelas_all as $k) {
            $semester_data = [];
            $kelas_punya_nilai_mapel = false; // hanya untuk nilai mapel

            for ($sem = 1; $sem <= 2; $sem++) {
                // Ambil nilai mapel per semester
                $nilai_mapel = $this->Nilai_model->get_all_mapel_with_nilai($no_induk, $k->id_kelas, $sem);
                // Ambil nilai ekskul
                $nilai_ekskul = $this->Ekskul_model->get_nilai_ekskul_siswa($no_induk, $k->id_kelas, $sem);
                // Ambil rekap kehadiran
                $rekap_kehadiran = $this->Rekap_kehadiran_model->get_rekap_kehadiran($no_induk, $k->id_kelas, $sem);
                //ambil rekap klapper
                $data_klapper = $this->Klapper_model->get_by_id($no_induk);


                // Simpan data per semester
                $semester_data[$sem] = [
                    'mapel'     => $nilai_mapel,
                    'ekskul'    => $nilai_ekskul,
                    'kehadiran' => $rekap_kehadiran,
                    'klapper'    => $data_klapper
                ];

                // Cek apakah semester ini punya nilai mapel
                if (!empty($nilai_mapel)) {
                    $kelas_punya_nilai_mapel = true;
                }
                 // Tambahkan deskripsi capaian pembelajaran (mapel)
                foreach ($nilai_mapel as &$n) {
                    if (!empty($n->nilai_akhir)) {
                        $n->deskripsi_capaian = $this->get_deskripsi_capaian($n->nilai_akhir);
                    } else {
                        $n->deskripsi_capaian = "-";
                    }
                }
                   // Tambahkan deskripsi capaian pembelajaran (ekskul)
                foreach ($nilai_ekskul as &$e) {
                    if (!empty($e->nilai)) {
                        $e->deskripsi_ekskul = $this->get_deskripsi_ekskul($e->nilai);
                    } else {
                        $e->deskripsi_ekskul = "-";
                    }
                }
            }

            // Hanya tambahkan kelas jika punya nilai mapel minimal di 1 semester
            if ($kelas_punya_nilai_mapel) {
                $kelas_nilai[$k->id_kelas] = [
                    'nama_kelas' => $k->nama_kelas,
                    'semester'   => $semester_data
                ];
            }
        }

        $data['kelas_nilai'] = $kelas_nilai;
        $data['klapper'] = $this->Klapper_model->get_by_no_induk($no_induk);
        // --- Tampilkan ke view ---
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/buku_induk_siswa_detail', $data);
        $this->load->view('Layout/footer');
    }

     /**
     * Fungsi untuk menghasilkan deskripsi capaian pembelajaran berdasarkan nilai akhir
     */
    private function get_deskripsi_capaian($nilai) {
       if ($nilai >= 90) {
            return "A (Sangat Baik/Istimewa) - Peserta didik menunjukkan penguasaan materi yang komprehensif dan kemampuan aplikasi yang luar biasa. Hasil belajar jauh melebihi standar ketuntasan.";
        } elseif ($nilai >= 80) {
            return "B (Baik) - Peserta didik menunjukkan penguasaan materi yang kuat dan mampu mengaplikasikannya dengan baik. Telah mencapai standar ketuntasan dengan hasil yang memuaskan.";
        } elseif ($nilai >= 70) {
            return "C (Cukup/Memuaskan) - Peserta didik menunjukkan penguasaan materi yang memadai dan telah mencapai batas minimal standar ketuntasan. Mungkin masih memerlukan sedikit peningkatan di beberapa area.";
        } elseif ($nilai >= 60) {
            return "D (Kurang) - Peserta didik menunjukkan penguasaan materi yang belum memadai atau masih di bawah standar ketuntasan minimal. Diperlukan perbaikan dan dukungan belajar yang intensif.";
        } else {
            return "E (Sangat Kurang)- Peserta didik belum menunjukkan penguasaan terhadap materi pelajaran. Membutuhkan bimbingan dan pendampingan yang lebih intensif untuk mencapai kompetensi dasar.";
        }
    }
    

    /**
 * Fungsi untuk menghasilkan deskripsi capaian pembelajaran EKSKUL
 */
    private function get_deskripsi_ekskul($nilai) {
        $nilai = strtoupper(trim($nilai)); // ubah ke huruf besar agar aman

        switch ($nilai) {
            case 'A':
                return "Peserta didik sangat aktif dan menunjukkan prestasi tinggi dalam kegiatan ekstrakurikuler. Ia memiliki semangat, disiplin, dan kerja sama yang luar biasa.";
            case 'B':
                return "Peserta didik aktif mengikuti kegiatan ekstrakurikuler dengan semangat dan tanggung jawab yang baik.";
            case 'C':
                return "Peserta didik cukup aktif dalam kegiatan ekstrakurikuler, namun masih perlu meningkatkan partisipasi dan kedisiplinan.";
            case 'D':
                return "Peserta didik kurang aktif dalam kegiatan ekstrakurikuler dan perlu bimbingan lebih lanjut untuk meningkatkan keterlibatan.";
            default:
                return "-";
        }
    }

// ====================== PRINT BUKU INDUK ======================
    public function download_data()
    {
        if (ob_get_length()) { ob_end_clean(); }
        ob_start();
        error_reporting(0);
        ini_set('display_errors', 0);
        header_remove();

        $this->load->library('tcpdf');
        $selected = $this->input->post('data') ?? [];
        $no_induk = $this->input->post('no_induk');

        if (empty($selected) || !$no_induk) {
            ob_end_clean();
            $this->session->set_flashdata('error', 'Pilih data yang ingin diunduh.');
            redirect('bukuinduk/detail/' . $no_induk);
            exit;
        }

        $siswa = $this->Buku_induk_siswa_model->get_siswa_by_no_induk($no_induk);
        if (!$siswa) {
            $this->session->set_flashdata('error', 'Data siswa tidak ditemukan.');
            redirect('bukuinduk');
            exit;
        }

        $kelas_all = $this->Kelas_model->get_all();
        $klapper = $this->Klapper_model->get_by_no_induk($no_induk);

        $content = '<h2 style="text-align:center;">BUKU INDUK SISWA <br>SD NEGERI TEGAL ALUR 04 PAGI</h2><hr>';

        // ---------- BIODATA ----------
        if (in_array('biodata', $selected)) {
            $content .= '<h3>Biodata Siswa</h3>
            <table border="1" cellpadding="6" cellspacing="0" width="100%" style="border-collapse:collapse;">
                <tr><td style="border:1px solid black;"><b>No Induk</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->no_induk ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Nama Siswa</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->nama_siswa ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Gender</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->gender ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Tempat, Tanggal Lahir</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->tempat_lahir ?? '-') . ', ' . htmlspecialchars(!empty($siswa->tgl_lahir) ? date('d-m-Y', strtotime($siswa->tgl_lahir)) : '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Agama</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->agama ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Alamat</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->alamat ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Nama Ayah</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->nama_ayah ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Nama Ibu</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->nama_ibu ?? '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Tanggal Diterima</b></td><td style="border:1px solid black;">' . htmlspecialchars(!empty($siswa->tgl_diterima) ? date('d-m-Y', strtotime($siswa->tgl_diterima)) : '-') . '</td></tr>
                <tr><td style="border:1px solid black;"><b>Sekolah Asal</b></td><td style="border:1px solid black;">' . htmlspecialchars($siswa->sekolah_asal ?? '-') . '</td></tr>
            </table><br>';
        }

        // ---------- NILAI RAPOT ----------
        $kelas_nilai = [];
        foreach ($kelas_all as $k) {
            $semester_data = [];
            $kelas_punya_nilai_mapel = false;

            for ($sem = 1; $sem <= 2; $sem++) {
                $nilai_mapel = $this->Nilai_model->get_all_mapel_with_nilai($no_induk, $k->id_kelas, $sem);
                $nilai_ekskul = $this->Ekskul_model->get_nilai_ekskul_siswa($no_induk, $k->id_kelas, $sem);
                $rekap_kehadiran = $this->Rekap_kehadiran_model->get_rekap_kehadiran($no_induk, $k->id_kelas, $sem);

                $semester_data[$sem] = [
                    'mapel'     => $nilai_mapel,
                    'ekskul'    => $nilai_ekskul,
                    'kehadiran' => $rekap_kehadiran
                ];

                if (!empty($nilai_mapel)) {
                    $kelas_punya_nilai_mapel = true;
                }
            }

            if ($kelas_punya_nilai_mapel) {
                $kelas_nilai[$k->id_kelas] = [
                    'nama_kelas' => $k->nama_kelas,
                    'semester'   => $semester_data
                ];
            }
        }

        // ---------- GENERATE TABEL NILAI PER KELAS ----------
        foreach ($kelas_nilai as $id_kelas => $kelas) {
            $kelas_checkbox = 'kelas_' . $id_kelas;
            if (!in_array('checkall', $selected) && !in_array($kelas_checkbox, $selected)) {
                continue;
            }

            $kelas_html = '';
            $ada_nilai_kelas = false;

            foreach ($kelas['semester'] as $sem => $data_sem) {
                $nilai_mapel = $data_sem['mapel'] ?? [];
                $nilai_ekskul = $data_sem['ekskul'] ?? [];
                $rekap = $data_sem['kehadiran'] ?? null;

                $has_value = false;
                foreach ($nilai_mapel as $m) {
                    $val = $m->nilai_akhir ?? $m->nilai ?? null;
                    if ($val !== null) { $has_value = true; break; }
                }
                if (!$has_value) continue;

                $ada_nilai_kelas = true;
                preg_match('/\d+/', $kelas['nama_kelas'], $matches);
                $kelas_number = isset($matches[0]) ? intval($matches[0]) : 0;

                $kelas_html .= '<h4>Semester ' . ($sem == 1 ? 'Ganjil' : 'Genap') . '</h4>';

                // ---------- TABEL MAPEL ----------
                $mapel_table_html = '<table border="1" cellpadding="5" cellspacing="0" width="100%" style="border-collapse:collapse;">
                    <thead>
                        <tr style="background-color:#d9d9d9;font-weight:bold;border:1px solid black; page-break-inside:avoid;">
                            <th style="border:1px solid black;width:20%;">Mata Pelajaran</th>
                            <th style="border:1px solid black;width:10%;">Nilai Akhir</th>
                            <th style="border:1px solid black;width:70%;">Capaian Pembelajaran</th>
                        </tr>
                    </thead>
                    <tbody>';

                $total = 0; $count = 0; $mulok_rows = [];

                foreach ($nilai_mapel as $n) {
                    $nilai = $n->nilai_akhir ?? $n->nilai ?? null;
                    if ($nilai !== null) { $total += $nilai; $count++; }
                    $deskripsi = $this->get_deskripsi_capaian($nilai);
                    // Ambil angka awal dari nama_kelas
                    preg_match('/^\d+/', $kelas['nama_kelas'], $matches);
                    $kelas_digit = isset($matches[0]) ? intval($matches[0]) : 0;

                    // Tentukan apakah mapel ini termasuk MULOK atau mapel khusus BING/IPADSI
                    $is_mulok = ($n->kode_mapel == 'PLBJ');

                    // Tambahan: BING & IPADSI hanya untuk kelas 3 ke atas
                    if (in_array($n->kode_mapel, ['BING'])) {
                        if ($kelas_digit >= 4) {
                            $is_mulok = true; // dianggap MULOK untuk tampil di tabel MULOK
                        } else {
                            continue; // jika kelas 1 atau 2, langsung skip (tidak tampil & tidak dihitung)
                        }
                    }
                    // Tambahkan kondisi untuk IPADSI
                    if ($n->kode_mapel === 'IPADSI') {
                        if ($kelas_digit < 3) {
                            continue; // kelas 1 atau 2, langsung skip
                        }
                        // untuk kelas 3 ke atas, tampilkan, tapi tidak dianggap MULOK
                    }

                    if ($is_mulok) { 
                        $mulok_rows[] = [
                            'nama' => $n->nama_mapel, 
                            'nilai' => $nilai, 
                            'deskripsi' => $deskripsi
                        ]; 
                    } else {
                        $mapel_table_html .= '<tr style="border:1px solid black; page-break-inside:avoid;" >
                            <td style="border:1px solid black;width:20%;">' . htmlspecialchars($n->nama_mapel) . '</td>
                            <td style="border:1px solid black;width:10%;">' . htmlspecialchars($nilai ?? '-') . '</td>
                            <td style="border:1px solid black;width:70%;">' . htmlspecialchars($deskripsi ?? '-') . '</td>
                        </tr>';
                    }

                }

                // MULOK
                if (!empty($mulok_rows)) {
                    $mapel_table_html .= '<tr style="font-weight:bold; text-align:center; background-color:#f2f2f2; border:1px solid black; page-break-inside:avoid;">
                        <th colspan="3" style="border:1px solid black;">MULOK</th>
                    </tr>';
                    foreach ($mulok_rows as $m) {
                        $mapel_table_html .= '<tr>
                            <td style="border:1px solid black;width:20%;">' . htmlspecialchars($m['nama']) . '</td>
                            <td style="border:1px solid black;width:10%;">' . htmlspecialchars($m['nilai'] ?? '-') . '</td>
                            <td style="border:1px solid black;width:70%;">' . htmlspecialchars($m['deskripsi'] ?? '-') . '</td>
                        </tr>';
                    }
                }

                $mapel_table_html .= '<tr>
                    <th  style="border:1px solid black;">Jumlah Nilai</th>
                    <th style="border:1px solid black;">' . $total . '</th>
                </tr>
                <tr style="border:1px solid black; page-break-inside:avoid;">
                    <th  style="border:1px solid black;">Rata-rata Nilai</th>
                    <th style="border:1px solid black;">' . ($count > 0 ? round($total / $count, 2) : 0) . '</th>
                </tr>
                </tbody></table><br>';

                $kelas_html .= $mapel_table_html;

                // ---------- EKSTRAKURIKULER ----------
                $ekskul_exist = array_filter($nilai_ekskul, function($e) { 
                    return isset($e->nilai) && $e->nilai !== null && trim($e->nilai) !== '' && trim($e->nilai) !== '-';
                });
                if (!empty($ekskul_exist)) {
                    $kelas_html .= '<h5>Ekstrakurikuler</h5>
                    <table border="1" cellpadding="5" cellspacing="0" width="100%" style="border-collapse:collapse;">
                        <thead>
                            <tr style="background-color:#d9d9d9;font-weight:bold; border:1px solid black; page-break-inside:avoid;">
                                <th style="border:1px solid black;width:20%;">Nama Ekskul</th>
                                <th style="border:1px solid black;width:10%;">Nilai</th>
                                <th style="border:1px solid black;width:70%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>';
                    foreach ($ekskul_exist as $e) {
                        $keterangan = $this->get_deskripsi_ekskul($e->nilai);
                        $kelas_html .= '<tr style="border:1px solid black; page-break-inside:avoid;">
                            <td style="border:1px solid black;width:20%;">' . htmlspecialchars($e->nama_ekskul) . '</td>
                            <td style="border:1px solid black;width:10%;">' . htmlspecialchars($e->nilai ?? '-') . '</td>
                            <td style="border:1px solid black;width:70%;">' . htmlspecialchars($keterangan ?? '-') . '</td>
                        </tr>';
                    }
                    $kelas_html .= '</tbody></table>';
                }

                // ---------- KEHADIRAN ----------
                $kelas_html .= '<h5>Rekap Kehadiran</h5>
                <table border="1" cellpadding="5" cellspacing="0" width="50%" style="border-collapse:collapse;">
                    <tr style="font-weight:bold"><td style="border:1px solid black;">Sakit</td><td style="border:1px solid black;">' . htmlspecialchars($rekap->sakit ?? 0) . '</td></tr>
                    <tr style="font-weight:bold"><td style="border:1px solid black;">Izin</td><td style="border:1px solid black;">' . htmlspecialchars($rekap->izin ?? 0) . '</td></tr>
                    <tr style="font-weight:bold"><td style="border:1px solid black;">Tanpa Keterangan</td><td style="border:1px solid black;">' . htmlspecialchars($rekap->tanpa_keterangan ?? 0) . '</td></tr>
                </table><br>';
            }

            if ($ada_nilai_kelas) {
                $content .= '<h3>Nilai Rapot - ' . htmlspecialchars($kelas['nama_kelas']) . '</h3>';
                $content .= $kelas_html;
            }
        }

        // ---------- DATA KENAIKAN ----------
        if (in_array('kenaikan', $selected) && !empty($klapper)) {
            $content .= '<h3>Data Kenaikan Kelas</h3>
            <table border="1" cellpadding="6" cellspacing="0" width="100%" style="border-collapse:collapse;">
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Kelas 1</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->kelas_1 ?? '-') . '</td></tr>
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Kelas 2</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->kelas_2 ?? '-') . '</td></tr>
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Kelas 3</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->kelas_3 ?? '-') . '</td></tr>
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Kelas 4</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->kelas_4 ?? '-') . '</td></tr>
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Kelas 5</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->kelas_5 ?? '-') . '</td></tr>
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Kelas 6</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->kelas_6 ?? '-') . '</td></tr>
                <tr><th style="background-color:#d9d9d9;font-weight:bold;border:1px solid black;">Keterangan</th><td style="border:1px solid black;">' . htmlspecialchars($klapper->keterangan ?? '-') . '</td></tr>
            </table>';
        }

    // ====== GENERATE PDF ======
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Sistem Sekolah');
    $pdf->SetTitle('Buku Induk - ' . $siswa->nama_siswa);
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 10);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // ====== CSS Tambahan untuk mencegah tabel terpotong ======
    $style = '
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            page-break-inside: avoid;
        }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        td, th {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }
        h2, h3, h4, h5 {
            page-break-after: avoid;
        }
    </style>
    ';

    // ====== Gabungkan CSS + konten utama ======
    $html = $style . $content;

    // ====== Cetak ke PDF dengan writeHTMLCell (lebih stabil dari writeHTML) ======
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    // ====== Output PDF ======
    ob_end_clean();
    $filename = 'Buku_Induk_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $siswa->nama_siswa) . '.pdf';
    $pdf->Output($filename, 'D');
    exit;
    }



}
