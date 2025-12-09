<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class ControllerNilaiSiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Nilai_model');      // Model Nilai
        $this->load->model('Rekap_kehadiran_model');
        $this->load->model('DataMaster');       // Model siswa
        $this->load->model('Klapper_model');       // Model siswa
        $this->load->model('Mapel_model');      // Model mata pelajaran
        $this->load->model('Kelas_model');      // model kelas
        $this->load->model('Ekskul_model');      //model ekskul 
        $this->load->library('form_validation');
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('nilai');
        }
    }

    // Tampilkan daftar nilai untuk semua siswa
    public function index() {
        $data['siswa'] = $this->DataMaster->get_siswa_fornilai();
        $data['kelas'] = $this->Kelas_model->get_all_active_class();
        $data['level_user'] = $this->session->userdata('level_user');

        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/nilai_siswa_list', $data); // view daftar siswa
        $this->load->view('Layout/footer',  $data);
    }
    public function get_siswa()
	{
		
		$kelas = $this->input->post('kelas'); // ambil dari filter dropdown
		$data = $this->DataMaster->get_siswa_fornilai($kelas);

		echo json_encode(['data' => $data]); // DataTables biasanya pakai key 'data'
	}

   public function edit_siswa($no_induk) {
        $id_kelas = $this->input->post('id_kelas') ?? $this->input->get('id_kelas');
        $semester = $this->input->post('semester') ?? $this->input->get('semester');

        $data['siswa']    = $this->DataMaster->get_siswa_by_no_induk($no_induk);
        $data['kelas']    = $this->Kelas_model->get_all_active_class();
        $kelas_row = $this->Kelas_model->get_by_id($id_kelas); // ini sudah single object
        $data['kelas_spesifik'] = $kelas_row;
        $data['mapel']    = $this->Mapel_model->get_all();
        $data['ekskul']   = $this->Ekskul_model->get_all(); // ambil semua ekskul
        $data['id_kelas'] = $id_kelas;
        $data['semester'] = $semester;

        $data['submitted'] = ($this->input->server('REQUEST_METHOD') === 'POST');
        $data['show_table'] = false;
        $data['nilai'] = [];
        $data['nilai_ekskul'] = [];

        if (!empty($id_kelas) && !empty($semester)) {
            $data['submitted'] = true;

            $kelas_row = $this->Kelas_model->get_by_id($id_kelas);
            $data['nama_kelas'] = $kelas_row ? $kelas_row->nama_kelas : '';

            // ambil semua mapel + nilai
            $mapel_nilai = $this->Nilai_model->get_all_mapel_with_nilai($no_induk, $id_kelas, $semester);
        if (!empty($mapel_nilai)) {
            $data['nilai'] = $mapel_nilai;
            $data['show_table'] = true;
        }

        // ambil nilai ekskul siswa yang sudah ada
        $nilai_ekskul_siswa = $this->Ekskul_model->get_nilai_ekskul_siswa($no_induk, $id_kelas, $semester);

        // index nilai ekskul berdasarkan id_ekskul
        $nilai_index = [];
        foreach ($nilai_ekskul_siswa as $n) {
            $nilai_index[$n->id_ekskul] = $n;
        }

        // gabungkan semua ekskul
        $nilai_ekskul = [];
        foreach ($data['ekskul'] as $e) {
            if (isset($nilai_index[$e->id_ekskul])) {
                $row = $nilai_index[$e->id_ekskul];
                $row->deskripsi_ekskul = !empty($row->nilai) ? $this->get_deskripsi_ekskul($row->nilai) : "-";
            } else {
                // jika belum ada nilai, buat dummy entry
                $row = (object)[
                    'id_nilai_ekskul' => null,
                    'id_ekskul' => $e->id_ekskul,
                    'nama_ekskul' => $e->nama_ekskul,
                    'nilai' => null,
                    'deskripsi_ekskul' => "-"
                ];
            }
            $nilai_ekskul[] = $row;
        }

        $data['nilai_ekskul'] = $nilai_ekskul;
    }

    // load view
    $this->load->view('Layout/head');
    $this->load->view('Layout/navbar');
    $this->load->view('Layout/aside');
    $this->load->view('Content/nilai_siswa_edit', $data);
    $this->load->view('Layout/footer');
}



    // Simpan nilai banyak mapel
    public function add_nilai($no_induk) {
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('semester', 'Semester', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['siswa'] = $this->DataMaster->get_siswa_by_no_induk($no_induk);
            $data['mapel'] = $this->Mapel_model->get_all();
            $data['kelas'] = $this->Kelas_model->get_all();
            $data['nilai'] = $this->Nilai_model->get_nilai_by_no_induk($no_induk);

            $this->load->view('Layout/head');
            $this->load->view('Layout/navbar');
            $this->load->view('Layout/aside');
            $this->load->view('Content/nilai_siswa_edit', $data);
            $this->load->view('Layout/footer');
        } else {
            $id_kelas = $this->input->post('id_kelas');
            $semester = $this->input->post('semester');
            $mapel    = $this->input->post('mapel');    // array [id_mapel => nilai]

            foreach ($mapel as $id_mapel => $nilai_akhir) {
                if ($nilai_akhir !== "") {
                    // Cek apakah nilai sudah ada
                    $cek = $this->Nilai_model->get_nilai_by_no_induk_mapel_kelas_semester(
                        $no_induk, $id_mapel, $id_kelas, $semester
                    );
                    if (!$cek) {
                        $data_insert = [
                            'no_induk' => $no_induk,
                            'id_kelas' => $id_kelas,
                            'id_mapel' => $id_mapel,
                            'semester' => $semester,
                            'nilai_akhir' => $nilai_akhir
                        ];
                        $this->Nilai_model->insert_nilai($data_insert);
                    }
                }
            }

            $this->session->set_flashdata('success', 'Data nilai berhasil ditambahkan');
            redirect('nilai/edit_siswa/'.$no_induk);
        }
    }

   public function all_nilai_siswa($no_induk)
{
    $this->session->set_userdata('previous_url', $_SERVER['HTTP_REFERER'] ?? '');

    // Ambil data siswa
    $data['siswa'] = $this->DataMaster->get_siswa_by_no_induk($no_induk);
    $data['kelas'] = $this->Kelas_model->get_all_active_class();

    if (!$data['siswa']) {
        $this->session->set_flashdata('error', 'Data siswa tidak ditemukan');
        redirect('datasiswa');
    }

    // Ambil semua kelas
    $kelas_all = $this->Kelas_model->get_all();

    // Load model tambahan
    $this->load->model('Rekap_kehadiran_model');
    $this->load->model('Klapper_model');

    $kelas_nilai = [];

    foreach ($kelas_all as $k) {
        $semester_data = [];
        $kelas_memiliki_nilai = false;

        // Loop semester 1 dan 2
        for ($sem = 1; $sem <= 2; $sem++) {

            // Ambil nilai mapel
            $nilai_mapel_raw = $this->Nilai_model->get_all_mapel_with_nilai($no_induk, $k->id_kelas, $sem);

            // Filter mapel yang memiliki nilai
            $nilai_mapel = [];
            foreach ($nilai_mapel_raw as $n) {
                if (!empty($n->nilai_akhir)) {
                    $n->deskripsi_capaian = $this->get_deskripsi_capaian($n->nilai_akhir);
                    $nilai_mapel[] = $n; // hanya masukkan mapel bernilai
                    $kelas_memiliki_nilai = true;
                }
            }

            // Ambil nilai ekskul
            $nilai_ekskul_raw = $this->Ekskul_model->get_nilai_ekskul_siswa($no_induk, $k->id_kelas, $sem);

            // Filter ekskul yang memiliki nilai
            $nilai_ekskul = [];
            foreach ($nilai_ekskul_raw as $e) {
                if (!empty($e->nilai)) {
                    $e->deskripsi_ekskul = $this->get_deskripsi_ekskul($e->nilai);
                    $nilai_ekskul[] = $e; // masukkan hanya ekskul bernilai
                    $kelas_memiliki_nilai = true;
                }
            }

            // Ambil kehadiran
            $rekap_kehadiran = $this->Rekap_kehadiran_model->get_rekap_kehadiran($no_induk, $k->id_kelas, $sem);

            // Ambil data klapper
            $data_klapper = $this->Klapper_model->get_by_id($no_induk);

            // Simpan semester
            $semester_data[$sem] = [
                'mapel'      => $nilai_mapel,      // sudah difilter
                'ekskul'     => $nilai_ekskul,     // sudah difilter
                'kehadiran'  => $rekap_kehadiran,
                'klapper'    => $data_klapper
            ];
        }

        // Masukkan hanya kelas yang punya nilai
        if ($kelas_memiliki_nilai) {
            $kelas_nilai[$k->id_kelas] = [
                'nama_kelas' => $k->nama_kelas,
                'semester'   => $semester_data
            ];
        }
    }

    $data['kelas_nilai'] = $kelas_nilai;
    $data['klapper'] = $this->Klapper_model->get_by_no_induk($no_induk);

    // Load view
    $this->load->view('Layout/head', $data);
    $this->load->view('Layout/navbar', $data);
    $this->load->view('Layout/aside', $data);
    $this->load->view('Content/nilai_siswa_detail', $data);
    $this->load->view('Layout/footer', $data);
}



    /**
     * Fungsi untuk menghasilkan deskripsi capaian pembelajaran berdasarkan nilai akhir
     */
    private function get_deskripsi_capaian($nilai) {
      if ($nilai >= 90) {
            return "<b>A (Sangat Baik/Istimewa)</b> - Peserta didik menunjukkan penguasaan materi yang komprehensif dan kemampuan aplikasi yang luar biasa. Hasil belajar jauh melebihi standar ketuntasan.";
        } elseif ($nilai >= 80) {
            return "<b>B (Baik)</b> - Peserta didik menunjukkan penguasaan materi yang kuat dan mampu mengaplikasikannya dengan baik. Telah mencapai standar ketuntasan dengan hasil yang memuaskan.";
        } elseif ($nilai >= 70) {
            return "<b>C (Cukup/Memuaskan)</b> - Peserta didik menunjukkan penguasaan materi yang memadai dan telah mencapai batas minimal standar ketuntasan. Mungkin masih memerlukan sedikit peningkatan di beberapa area.";
        } elseif ($nilai >= 60) {
            return "<b>D (Kurang)</b> - Peserta didik menunjukkan penguasaan materi yang belum memadai atau masih di bawah standar ketuntasan minimal. Diperlukan perbaikan dan dukungan belajar yang intensif.";
        } else {
            return "<b>E (Sangat Kurang)</b> - Peserta didik belum menunjukkan penguasaan terhadap materi pelajaran. Membutuhkan bimbingan dan pendampingan yang lebih intensif untuk mencapai kompetensi dasar.";
        }

    }

    /**
 * Fungsi untuk menghasilkan deskripsi capaian pembelajaran EKSKUL
 */
    private function get_deskripsi_ekskul($nilai) {
        $nilai = strtoupper(trim($nilai)); // ubah ke huruf besar agar aman

        switch ($nilai) {
            case 'SB':
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

    // update nilai ekskul
    public function update_nilai_ekskul($no_induk)
{
    $id_nilai_ekskul = $this->input->post('id_nilai_ekskul');
    $id_ekskul       = $this->input->post('id_ekskul');
    $id_kelas        = $this->input->post('id_kelas'); // pastikan konsisten dgn form
    $semester        = $this->input->post('semester');
    $nilai           = $this->input->post('nilai');



    if (!empty($id_nilai_ekskul)) {
        // === UPDATE ===
        $this->db->where('id_nilai_ekskul', $id_nilai_ekskul);
        $this->db->update('nilai_ekskul', [
            'nilai' => $nilai
        ]);
        $this->session->set_flashdata('success', 'Nilai ekstrakurikuler berhasil diperbarui!');
    } else {
        // === INSERT BARU ===
        $data = [
            'no_induk'  => $no_induk,
            'id_ekskul' => $id_ekskul,
            'id_kelas'  => $id_kelas,
            'semester'  => $semester,
            'nilai'     => $nilai
        ];
        $this->db->insert('nilai_ekskul', $data);
        $this->session->set_flashdata('success', 'Nilai ekstrakurikuler baru berhasil ditambahkan!');
    }

    redirect('nilai/edit_siswa/' . $no_induk . '?id_kelas=' . $id_kelas . '&semester=' . $semester);
}


    // Tambah nilai (dari modal)
    public function store_nilai($no_induk) {
         $id_kelas   = $this->input->post('id_kelas');
         $semester   = $this->input->post('semester');
        $data = [
            'no_induk'              => $no_induk,
            'id_mapel'              => $this->input->post('id_mapel'),
            'id_kelas'              => $this->input->post('id_kelas'),
            'semester'              => $this->input->post('semester'),
            'nilai_akhir'           => $this->input->post('nilai_akhir')
        ];

        if ($this->Nilai_model->insert_nilai($data)) {
            $this->session->set_flashdata('success', 'Nilai berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan nilai.');
        }

            redirect('nilai/edit_siswa/'.$no_induk.'?id_kelas='.$id_kelas.'&semester='.$semester);
    }
    public function store_nilai_ekskul($no_induk)
    {
        $id_ekskul  = $this->input->post('id_ekskul');
        $id_kelas   = $this->input->post('id_kelas');
        $semester   = $this->input->post('semester');
        $nilai = $this->input->post('nilai');

        $data = [
            'no_induk'  => $no_induk,
            'id_ekskul' => $id_ekskul,
            'id_kelas'  => $id_kelas,
            'semester'  => $semester,
            'nilai'     => $nilai
        ];
     
          
    $result = $this->Nilai_model->insert_nilai_ekskul($data);

    if ($result) {
        $this->session->set_flashdata('success', 'Nilai ekstrakurikuler berhasil ditambahkan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menambahkan nilai ekstrakurikuler: ' . $this->db->error()['message']);
    }

        redirect('nilai/edit_siswa/' . $no_induk . '?id_kelas=' . $id_kelas . '&semester=' . $semester);
    }

    // Update nilai
    public function update_nilai($no_induk) {
        $id_nilai = $this->input->post('id_nilai');
        $id_kelas = $this->input->post('id_kelas');
        $semester = $this->input->post('semester');

        $data = [
            'nilai_akhir'           => $this->input->post('nilai_akhir')
        ];

        if ($this->Nilai_model->update($id_nilai, $data)) {
            $this->session->set_flashdata('success', 'Nilai berhasil diupdate.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate nilai.');
        }

        redirect('nilai/edit_siswa/'.$no_induk.'?id_kelas='.$id_kelas.'&semester='.$semester);
    }

    // Hapus nilai ekskul dan mapel
    public function delete_nilai($no_induk) {
        $id_nilai = $this->input->post('id_nilai') ?: $this->input->post('id_nilai_ekskul');
        $id_kelas = $this->input->post('id_kelas') ?: $this->input->post('id_kelas_ekskul');
        $semester = $this->input->post('semester');
        $jenis    = $this->input->post('jenis'); // "mapel" atau "ekskul"

        if ($this->Nilai_model->delete($id_nilai, $jenis)) {
            $pesan = ($jenis == 'ekskul')
                ? 'Nilai ekstrakurikuler berhasil dihapus.'
                : 'Nilai berhasil dihapus.';
            $this->session->set_flashdata('success', $pesan);
        } else {
            $pesan = ($jenis == 'ekskul')
                ? 'Gagal menghapus nilai ekstrakurikuler.'
                : 'Gagal menghapus nilai.';
            $this->session->set_flashdata('error', $pesan);
        }

        // Arahkan kembali ke bagian tabel sesuai jenis
        $anchor = ($jenis == 'ekskul') ? '#tabel-ekskul' : '';
        redirect('nilai/edit_siswa/' . $no_induk . '?id_kelas=' . $id_kelas . '&semester=' . $semester . $anchor);
    }


    //update nilai mulok
    public function update_nilai_mulok($no_induk)
    {
        $nama_mulok = $this->input->post('nama_mulok');
        $nilai = $this->input->post('nilai');
        $keterangan = $this->input->post('keterangan');
        $id_kelas = $this->input->post('id_kelas');
        $semester = $this->input->post('semester');

        $this->Nilai_model->update_mulok($no_induk, $nama_mulok, $nilai, $keterangan, $id_kelas, $semester);

        $this->session->set_flashdata('success', 'Nilai MULOK berhasil diperbarui.');
        redirect('nilai/edit_siswa/'.$no_induk);
    }
  

public function import_nilai()
{
    if (empty($_FILES['file_csv']['name'])) {
        $this->session->set_flashdata('error', 'File CSV belum diunggah!');
        redirect('nilai');
    }

    $file_mimes = [
        'text/csv',
        'application/csv',
        'text/plain',
        'application/octet-stream',
        'application/vnd.ms-excel'
    ];

    if (!in_array($_FILES['file_csv']['type'], $file_mimes)) {
        $this->session->set_flashdata('error', 'File bukan format CSV!');
        redirect('nilai');
    }

    $file = $_FILES['file_csv']['tmp_name'];
    if (($handle = fopen($file, "r")) === FALSE) {
        $this->session->set_flashdata('error', 'Gagal membuka file CSV!');
        redirect('nilai');
    }

    $row = 0;
    $header = [];
    $index  = [];

    $success_count = 0;

    while (($data = fgetcsv($handle, 2500, ",")) !== FALSE) {

        // Skip judul
        if ($row == 0) {
            $row++;
            continue;
        }

        // Header sesungguhnya
        if ($row == 1) {
            $header = array_map('trim', $data);
            $index = array_flip($header);

            // Cek kolom wajib
            $required = ['no_induk','nama_kelas','semester'];
            foreach ($required as $col) {
                if (!isset($index[$col])) {
                    $this->session->set_flashdata('error', "Kolom '$col' tidak ditemukan di header CSV!");
                    redirect('nilai');
                }
            }

            $row++;
            continue;
        }

        // Data utama
        $no_induk   = $data[$index['no_induk']]   ?? '';
        $nama_kelas = $data[$index['nama_kelas']] ?? '';
        $semester   = $data[$index['semester']]   ?? '';

        // Validasi kelas
        $kelas = $this->DataMaster->get_kelas_by_nama($nama_kelas);
        if (!$kelas) {
            $this->session->set_flashdata('error', "Kelas '$nama_kelas' tidak ditemukan di database!");
            redirect('nilai');
        }

        // Validasi siswa
        $siswa = $this->DataMaster->get_siswa_by_no_induk($no_induk);
        if (!$siswa) {
            $this->session->set_flashdata('error', "Siswa dengan no_induk '$no_induk' tidak ditemukan!");
            redirect('nilai');
        }

        $id_kelas = $kelas->id_kelas;

        // Mapel
        $list_mapel = ['PAIDBP','PAKDBP','PPDK','BI','MTK','IPADSI','PJODK','SNMS','BING','PLBJ'];

        foreach ($list_mapel as $mp) {

            if (!isset($index[$mp])) continue;

            $nilai_akhir = $data[$index[$mp]] ?? null;

            if ($nilai_akhir === null || $nilai_akhir === '') continue;

            $mapel = $this->Nilai_model->get_mapel_by_kode($mp);

            if (!$mapel) {
                $this->session->set_flashdata('error', "Mapel '$mp' tidak ditemukan di database!");
                redirect('nilai');
            }

            $data_nilai = [
                'no_induk'    => $no_induk,
                'id_kelas'    => $id_kelas,
                'semester'    => $semester,
                'id_mapel'    => $mapel->id_mapel,
                'nilai_akhir' => $nilai_akhir
            ];

            // Cek apakah nilai sudah ada
            $exist = $this->Nilai_model->get_nilai_by_mapel($no_induk, $id_kelas, $semester, $mapel->id_mapel);

            if ($exist) {
                $this->Nilai_model->update_nilai($no_induk, $id_kelas, $semester, $mapel->id_mapel, $data_nilai);
            } else {
                $this->Nilai_model->insert_nilai($data_nilai);
            }
        }

        $success_count++;
        $row++;
    }

    fclose($handle);

    $this->session->set_flashdata('success', "Import selesai! Total berhasil: $success_count");
    redirect('nilai');
}


}
