<?php

defined('BASEPATH') or exit('No direct script access allowed');
// Autoload PhpSpreadsheet
// require APPPATH.'../vendor/autoload.php'; // path ke autoload Composer
// use PhpOffice\PhpSpreadsheet\IOFactory;
#[\AllowDynamicProperties]
class ControllerDataMaster extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('DataMaster');	
		$this->load->library('form_validation'); // ← ini wajib	
		$this->load->library('pdf');
	  	$this->load->library('session');
        $this->load->helper(['url', 'form']);
		$this->load->model('Kelas_model'); // Model Rekap Kehadiran
        // ✅ Proteksi agar tidak bisa akses tanpa login
        if (!$this->session->userdata('logged_in')) {
			   redirect('login');
        }
           
	}
	public function get_siswa()
	{
		
		$kelas = $this->input->post('kelas'); // ambil dari filter dropdown
		$data = $this->DataMaster->get_siswa($kelas);

		echo json_encode(['data' => $data]); // DataTables biasanya pakai key 'data'
	}

	public function index() {
        $data['siswa'] = $this->DataMaster->get_all_siswa();
		$data['kelas'] = $this->Kelas_model->get_all();
        $data['level_user'] = $this->session->userdata('level_user');
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/siswa', $data); // view Ekskul
        $this->load->view('Layout/footer', $data);
    }

	public function add_siswa()
	{
		// Validasi input sesuai dengan name di form
		$this->form_validation->set_rules('nisn', 'NISN Siswa', 'required');
		$this->form_validation->set_rules('nama', 'Nama Siswa', 'required');
		$this->form_validation->set_rules('no_induk', 'Nomor Induk', 'required|numeric');
		$this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');
		$this->form_validation->set_rules('agama', 'Agama', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
		$this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('nama_ibu', 'Nama Ibu', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('nama_ayah', 'Nama Ayah', 'required');
		$this->form_validation->set_rules('tgl_diterima', 'Tanggal Diterima', 'required');
		$this->form_validation->set_rules('kelas', 'Rombel Saat Ini', 'required');
		$data['level_user'] = $this->session->userdata('level_user');

		if ($this->form_validation->run() == FALSE) {
			$data = array(
				'siswa' => $this->DataMaster->select_siswa()
			);
			echo validation_errors();
			$this->load->view('Layout/head');
			$this->load->view('Layout/navbar');
			$this->load->view('Layout/aside');
			$this->load->view('Content/siswa', $data);
			$this->load->view('Layout/footer');
		} else {
			$nisn = $this->input->post('nisn');
			$no_induk = $this->input->post('no_induk');

			// ✅ Cek duplicate NISN
			if ($this->DataMaster->cek_nisn($nisn)) {
				$this->session->set_flashdata('error', 'NISN ' . $nisn . ' sudah ada di database!');
				redirect('siswa');
			}

			// ✅ Cek duplicate Nomor Induk
			if ($this->DataMaster->cek_no_induk($no_induk)) {
				$this->session->set_flashdata('error', 'Nomor Induk ' . $no_induk . ' sudah ada di database!');
				redirect('siswa');
			}

			// Jika tidak duplicate, lanjut insert
			$data = array(
				'nisn' => $nisn,
				'nama_siswa' => $this->input->post('nama'),
				'no_induk' => $no_induk,
				'gender' => $this->input->post('gender'),
				'agama' => $this->input->post('agama'),
				'alamat' => $this->input->post('alamat'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'nama_ibu' => $this->input->post('nama_ibu'),
				'nama_ayah' => $this->input->post('nama_ayah'),
				'tgl_diterima' => $this->input->post('tgl_diterima'),
				'kelas' => $this->input->post('kelas')
			);

			$this->DataMaster->insert_siswa($data);
			$this->session->set_flashdata('success', 'Data Siswa Berhasil Ditambahkan!');
			redirect('siswa');
		}
	}

	public function delete_siswa($id)
	{
		$this->DataMaster->delete_siswa($id);
		$this->session->set_flashdata('success', 'Data Siswa Berhasil Dihapus!');
		redirect('siswa');
	}
	public function update_siswa($id)
{
    // Validasi form
    $this->form_validation->set_rules('no_induk', 'No Induk Siswa', 'required|trim');
    $this->form_validation->set_rules('nama', 'Nama Siswa', 'required|trim');
    $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required');
    $this->form_validation->set_rules('agama', 'Agama', 'required|trim');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required|trim');
    $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
    $this->form_validation->set_rules('nama_ibu', 'Nama Ibu', 'required|trim');
    $this->form_validation->set_rules('nama_ayah', 'Nama Ayah', 'required|trim');
    $this->form_validation->set_rules('tgl_diterima', 'Tanggal Diterima', 'required');
    $this->form_validation->set_rules('kelas', 'Rombel Saat Ini', 'required'); // name sesuai form

    if ($this->form_validation->run() == FALSE) {
        $data = array(
            'siswa' => $this->DataMaster->edit_siswa($id),
            'kelas' => $this->Kelas_model->get_all() 
        );
        $this->load->view('Layout/head');
        $this->load->view('Layout/navbar');
        $this->load->view('Layout/aside');
        $this->load->view('Content/edit_siswa', $data);
        $this->load->view('Layout/footer');
    } else {
        // Ambil data dari form
        $data = array(
            'no_induk' => htmlspecialchars(trim($this->input->post('no_induk'))),
            'nama_siswa' => htmlspecialchars(trim($this->input->post('nama'))),
            'gender' => $this->input->post('gender'),
            'agama' => htmlspecialchars(trim($this->input->post('agama'))),
            'alamat' => htmlspecialchars(trim($this->input->post('alamat'))),
            'tempat_lahir' => htmlspecialchars(trim($this->input->post('tempat_lahir'))),
            'tgl_lahir' => $this->input->post('tgl_lahir'),
            'nama_ibu' => htmlspecialchars(trim($this->input->post('nama_ibu'))),
            'nama_ayah' => htmlspecialchars(trim($this->input->post('nama_ayah'))),
            'tgl_diterima' => $this->input->post('tgl_diterima'),
            'kelas' => $this->input->post('kelas') // ini id_kelas
        );

        // Update data siswa
        $this->DataMaster->update_siswa($id, $data);

        $this->session->set_flashdata('success', 'Data Siswa Berhasil Diperbaharui!');
        redirect('siswa');
    }
}


	public function import_siswa()
{
    if (isset($_FILES['file_csv']['name'])) {
        $file_mimes = ['text/csv', 'application/csv', 'application/vnd.ms-excel'];

        if (in_array($_FILES['file_csv']['type'], $file_mimes)) {
            $file = $_FILES['file_csv']['tmp_name'];

            if (($handle = fopen($file, "r")) !== FALSE) {
                $row = 0;
                $insert_count = 0;
                $update_count = 0;
                $failed_kelas = [];

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($row == 0) { 
                        $row++; // lewati baris header CSV
                        continue; 
                    }

                    // urutan kolom CSV:
                    // 0=nisn, 1=no_induk, 2=nama_siswa, 3=gender, 4=tgl_lahir,
                    // 5=tempat_lahir, 6=agama, 7=alamat, 8=nama_ayah, 9=nama_ibu,
                    // 10=tgl_diterima, 11=kelas (nama_kelas di CSV)
                    $nisn = trim($data[0]);
                    $nama_kelas = trim($data[11]);

                    // 🔹 Cari id_kelas berdasarkan nama_kelas
                    $kelas_row = $this->db->get_where('kelas', ['nama_kelas' => $nama_kelas])->row();
                    if ($kelas_row) {
                        $id_kelas = $kelas_row->id_kelas;
                    } else {
                        // Jika nama kelas tidak ditemukan, catat dan lewati baris ini
                        $failed_kelas[] = $nama_kelas;
                        $row++;
                        continue;
                    }

                    // 🔹 Data siswa yang akan disimpan
                    $data_siswa = [
                        'nisn' => $nisn,
                        'no_induk' => trim($data[1]),
                        'nama_siswa' => trim($data[2]),
                        'gender' => trim($data[3]),
                        'tgl_lahir' => trim($data[4]),
                        'tempat_lahir' => trim($data[5]),
                        'agama' => trim($data[6]),
                        'alamat' => trim($data[7]),
                        'nama_ayah' => trim($data[8]),
                        'nama_ibu' => trim($data[9]),
                        'tgl_diterima' => trim($data[10]),
                        'kelas' => $id_kelas // ← simpan id_kelas ke kolom 'kelas' di tabel siswa
                    ];

                    // 🔹 Cek apakah NISN sudah ada
                    $existing = $this->db->get_where('siswa', ['nisn' => $nisn])->row();

                    if ($existing) {
                        // Update data lama
                        $this->db->where('nisn', $nisn);
                        $this->db->update('siswa', $data_siswa);
                        $update_count++;
                    } else {
                        // Tambah data baru
                        $this->db->insert('siswa', $data_siswa);
                        $insert_count++;
                    }

                    $row++;
                }

                fclose($handle);

                // 🔹 Buat pesan hasil
                $msg = [];
                if ($insert_count > 0) $msg[] = "$insert_count siswa baru berhasil ditambahkan";
                if ($update_count > 0) $msg[] = "$update_count siswa berhasil diperbarui";
                if (!empty($failed_kelas)) $msg[] = "Kelas tidak ditemukan: " . implode(', ', array_unique($failed_kelas));

                $this->session->set_flashdata('success', implode('. ', $msg) . '.');
                redirect('siswa');
            }
        } else {
            $this->session->set_flashdata('error', 'File yang diunggah bukan CSV!');
            redirect('siswa');
        }
    } else {
        $this->session->set_flashdata('error', 'File CSV belum diunggah!');
        redirect('siswa');
    }
}


	public function download_siswa() {
		// Ambil semua data siswa
		$siswa = $this->DataMaster->get_all_siswa(); // Pastikan ada method ini
		// Urutkan berdasarkan no_induk kecil ke besar
		usort($siswa, function($a, $b) {
			return $a->no_induk <=> $b->no_induk;
		});

		// Buat objek TCPDF baru
		$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Atur informasi dokumen
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Sekolah');
		$pdf->SetTitle('Data Siswa');
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
		$html = '<h2>Data Identitas Siswa</h2>';
		$html .= '<table border="1" cellpadding="5">
			<tr style="background-color:#f2f2f2; text-align:center;">
				<th width="30">No</th>
				<th>NISN</th>
				<th width="40">No Induk</th>
				<th>Nama Siswa</th>
				<th>Gender</th>
				<th>Tanggal Lahir</th>
				<th>Tempat Lahir</th>
				<th width="40">Agama</th>
				<th width="100">Alamat</th>
				<th>Nama Ayah</th>
				<th>Nama Ibu</th>
				<th>Tanggal Diterima</th>
				<th>Kelas</th>
			</tr>';

		// Isi data siswa
		 $no = 1;
		foreach($siswa as $s) {
			$html .= '<tr style="text-align:center;">
				<td>'.$no.'</td>
				<td>'.$s->nisn.'</td>
				<td>'.$s->no_induk.'</td>
				<td>'.$s->nama_siswa.'</td>
				<td>'.$s->gender.'</td>
				<td>'.$s->tgl_lahir.'</td>
				<td>'.$s->tempat_lahir.'</td>
				<td>'.$s->agama.'</td>
				<td>'.$s->alamat.'</td>
				<td>'.$s->nama_ayah.'</td>
				<td>'.$s->nama_ibu.'</td>
				<td>'.$s->tgl_diterima.'</td>
				<td>'.$s->nama_kelas.'</td>
				
			</tr>';
			$no++;
		}

		$html .= '</table>';

		// Tulis HTML ke PDF
		$pdf->writeHTML($html, true, false, true, false, '');

		// Output PDF ke browser
		$pdf->Output('Data_Siswa.pdf', 'D'); // 'D' = download
	}



}
        
