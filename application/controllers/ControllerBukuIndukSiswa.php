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
        $data['siswa'] = $this->DataMaster->select_siswa();

        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/buku_induk_siswa', $data);
        $this->load->view('Layout/footer');
    }
    // ====================== DETAIL SISWA ======================
    public function detail($no_induk = null)
    {
        // --- Ambil data utama siswa ---
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
        if ($nilai >= 91) {
            return "<b> Sangat Baik (SB) </b> - Peserta didik sangat menguasai konsep, keterampilan, dan sikap dalam pembelajaran. Mampu menjelaskan, menerapkan, serta menyelesaikan masalah secara mandiri dan kreatif.";
        } elseif ($nilai >= 81) {
            return "<b> Baik (B) </b>  - Peserta didik menguasai sebagian besar materi dan keterampilan dengan baik. Dapat menerapkan pengetahuan dalam konteks sederhana serta menunjukkan kemandirian dan tanggung jawab dalam belajar.";
        } elseif ($nilai >= 71) {
            return "<b> Cukup (C) </b> - Peserta didik memahami sebagian konsep dasar namun masih perlu bimbingan dalam penerapan dan pengembangan kemampuan berpikir kritis atau kreatif.";
        } else {
            return "<b> Perlu Bimbingan (PB) </b>  - Peserta didik belum menunjukkan penguasaan yang memadai terhadap materi dan keterampilan. Perlu pendampingan intensif dan latihan tambahan untuk mencapai kompetensi yang diharapkan.";
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
    // ====== MATIKAN OUTPUT & ERROR ======
    if (ob_get_length()) { @ob_end_clean(); }
    ob_start();
    error_reporting(0);
    ini_set('display_errors', 0);
    header_remove();

    // ====== LOAD LIBRARY & AMBIL INPUT ======
    $this->load->library('tcpdf');
    $selected = $this->input->post('data') ?? [];
    $no_induk = $this->input->post('no_induk');

    if (empty($selected) || !$no_induk) {
        @ob_end_clean();
        $this->session->set_flashdata('error', 'Pilih data yang ingin diunduh.');
        redirect('bukuinduk/detail/' . $no_induk);
        exit;
    }

    // ====== AMBIL DATA UTAMA ======
    $siswa = $this->Buku_induk_siswa_model->get_siswa_by_no_induk($no_induk);
    $kelas_all = $this->Kelas_model->get_all();
    $klapper = $this->Klapper_model->get_by_no_induk($no_induk);

    $content = '<h2 style="text-align:center;">BUKU INDUK SISWA <br>SD NEGERI TEGAL ALUR 04 PAGI</h2><hr>';

    // ---------- BIODATA ----------
    if (in_array('biodata', $selected)) {
        $content .= '
        <h3>Biodata Siswa</h3>
        <table border="1" cellspacing="0" cellpadding="6" width="100%">
            <tr><td><b>No Induk</b></td><td>' . htmlspecialchars($siswa->no_induk) . '</td></tr>
            <tr><td><b>Nama Siswa</b></td><td>' . htmlspecialchars($siswa->nama_siswa) . '</td></tr>
            <tr><td><b>Gender</b></td><td>' . htmlspecialchars($siswa->gender) . '</td></tr>
            <tr><td><b>Tempat, Tanggal Lahir</b></td><td>' . htmlspecialchars($siswa->tempat_lahir) . ', ' . htmlspecialchars($siswa->tgl_lahir) . '</td></tr>
            <tr><td><b>Agama</b></td><td>' . htmlspecialchars($siswa->agama) . '</td></tr>
            <tr><td><b>Alamat</b></td><td>' . htmlspecialchars($siswa->alamat) . '</td></tr>
            <tr><td><b>Nama Ayah</b></td><td>' . htmlspecialchars($siswa->nama_ayah) . '</td></tr>
            <tr><td><b>Nama Ibu</b></td><td>' . htmlspecialchars($siswa->nama_ibu) . '</td></tr>
            <tr><td><b>Tanggal Diterima</b></td><td>' . htmlspecialchars($siswa->tgl_diterima) . '</td></tr>
            <tr><td><b>Sekolah Asal</b></td><td>' . htmlspecialchars($siswa->sekolah_asal) . '</td></tr>
        </table><br>';
    }

    // ---------- NILAI RAPOT SESUAI CHECKLIST ----------
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

    foreach ($kelas_nilai as $id_kelas => $kelas) {
        $kelas_checkbox = 'kelas_' . $id_kelas;

        // Tampilkan kelas hanya jika dicentang atau checkall dicentang
        if (!in_array('checkall', $selected) && !in_array($kelas_checkbox, $selected)) {
            continue;
        }

        $kelas_html = '';
        $ada_nilai_kelas = false;

        foreach ($kelas['semester'] as $sem => $data_sem) {
            $nilai_mapel = $data_sem['mapel'];
            $nilai_ekskul = $data_sem['ekskul'];
            $rekap = $data_sem['kehadiran'];

            $ada_nilai_sem = false;
            foreach ($nilai_mapel as $m) {
                if ((isset($m->nilai) && $m->nilai !== null) || (isset($m->nilai_akhir) && $m->nilai_akhir !== null)) {
                    $ada_nilai_sem = true;
                    break;
                }
            }
            if (!$ada_nilai_sem) continue;

            $ada_nilai_kelas = true;
            $kelas_number = intval(filter_var($kelas['nama_kelas'], FILTER_SANITIZE_NUMBER_INT));

            $kelas_html .= '<h4>Semester ' . ($sem == 1 ? 'Ganjil' : 'Genap') . '</h4>';

            // ---------- TABEL MAPEL ----------
            $mapel_table_html = '<table border="1" cellspacing="0" cellpadding="5" width="100%">
                <thead>
                    <tr style="background-color:#d9d9d9; font-weight:bold">
                        <th>Mata Pelajaran</th>
                        <th>Nilai Akhir</th>
                        <th>Capaian Pembelajaran</th>
                    </tr>
                </thead><tbody>';

            $total = 0; $count = 0; $mulok_rows = [];
            foreach ($nilai_mapel as $n) {
                $nilai = $n->nilai_akhir ?? $n->nilai ?? null;
                if (is_numeric($nilai)) { $total += $nilai; $count++; }

                $is_mulok = ($n->nama_mapel === 'PLBJ' || ($n->nama_mapel === 'Bahasa Inggris' && $kelas_number >= 3));
                if ($is_mulok) { $mulok_rows[] = $n; continue; }

                $mapel_table_html .= '<tr>
                    <td>' . htmlspecialchars($n->nama_mapel) . '</td>
                    <td>' . htmlspecialchars($nilai ?? '-') . '</td>
                    <td>' . htmlspecialchars($n->capaian_pembelajaran ?? '-') . '</td>
                </tr>';
            }

            if (!empty($mulok_rows)) {
                $mapel_table_html .= '<tr style="font-weight:bold; text-align:center; background-color:#f2f2f2;"><th colspan="3">MULOK</th></tr>';
                foreach ($mulok_rows as $n) {
                    $mapel_table_html .= '<tr>
                        <td>' . htmlspecialchars($n->nama_mapel) . '</td>
                        <td>' . htmlspecialchars($n->nilai_akhir ?? '-') . '</td>
                        <td>' . htmlspecialchars($n->capaian_pembelajaran ?? '-') . '</td>
                    </tr>';
                }
            }

            $mapel_table_html .= '<tr><th colspan="2">Jumlah Nilai</th><th>' . $total . '</th></tr>';
            $mapel_table_html .= '<tr><th colspan="2">Rata-rata Nilai</th><th>' . ($count > 0 ? round($total / $count, 2) : 0) . '</th></tr>';
            $mapel_table_html .= '</tbody></table><br><br><br>';
            $kelas_html .= $mapel_table_html;

            // ---------- EKSTRAKURIKULER ----------
            $ekskul_exist = $ekskul_exist = array_filter($nilai_ekskul, function($e) {
                return $e->nilai !== null;
            });
            if (!empty($ekskul_exist)) {
                $kelas_html .= '<h5>Ekstrakurikuler</h5>
                <table border="1" cellspacing="0" cellpadding="5" width="100%">
                    <thead>
                        <tr style="background-color:#d9d9d9;">
                            <th>Nama Ekskul</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>';
                foreach ($ekskul_exist as $e) {
                    $kelas_html .= '<tr>
                        <td>' . htmlspecialchars($e->nama_ekskul) . '</td>
                        <td>' . htmlspecialchars($e->nilai) . '</td>
                        <td>' . htmlspecialchars($e->keterangan ?? '-') . '</td>
                    </tr>';
                }
                $kelas_html .= '</tbody></table><br><br>';
            }

            // ---------- KEHADIRAN ----------
            $kelas_html .= '<h5>Rekap Kehadiran</h5>
            <table border="1" cellspacing="0" cellpadding="5" width="50%">
                <tr style="font-weight:bold"><td>Sakit</td><td>' . htmlspecialchars($rekap->sakit ?? 0) . '</td></tr>
                <tr style="font-weight:bold"><td>Izin</td><td>' . htmlspecialchars($rekap->izin ?? 0) . '</td></tr>
                <tr style="font-weight:bold"><td>Tanpa Keterangan</td><td>' . htmlspecialchars($rekap->tanpa_keterangan ?? 0) . '</td></tr>
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
        <table border="1" cellspacing="0" cellpadding="6" width="100%">
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Tanggal Masuk</th><td>' . date('d-m-Y', strtotime($klapper->tgl_masuk)) . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Kelas 1</th><td>' . htmlspecialchars($klapper->kelas_1 ?? '-') . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Kelas 2</th><td>' . htmlspecialchars($klapper->kelas_2 ?? '-') . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Kelas 3</th><td>' . htmlspecialchars($klapper->kelas_3 ?? '-') . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Kelas 4</th><td>' . htmlspecialchars($klapper->kelas_4 ?? '-') . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Kelas 5</th><td>' . htmlspecialchars($klapper->kelas_5 ?? '-') . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Kelas 6</th><td>' . htmlspecialchars($klapper->kelas_6 ?? '-') . '</td></tr>
            <tr><th style="background-color:#d9d9d9;font-weight:bold">Keterangan</th><td>' . htmlspecialchars($klapper->keterangan ?? '-') . '</td></tr>
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
    $pdf->writeHTML($content, true, false, true, false, '');

    @ob_end_clean();
    $filename = 'Buku_Induk_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $siswa->nama_siswa) . '.pdf';
    $pdf->Output($filename, 'D');
    exit;
}

}
