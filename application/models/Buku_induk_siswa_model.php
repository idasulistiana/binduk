
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#[\AllowDynamicProperties]
class Buku_induk_siswa_model extends CI_Model {

    // Ambil data siswa berdasarkan no_induk
    public function get_siswa_by_no_induk($no_induk) {
        return $this->db->get_where('siswa', ['no_induk' => $no_induk])->row();
    }

    /**
     * Ambil semua nilai mapel per kelas dan semester
     */
    public function get_all_mapel_with_nilai($no_induk, $id_kelas, $semester)
    {
        $this->db->select('m.nama_mapel, n.nilai, n.keterangan');
        $this->db->from('nilai n');
        $this->db->join('mapel m', 'm.id_mapel = n.id_mapel', 'left');
        $this->db->where('n.no_induk', $no_induk);
        $this->db->where('n.id_kelas', $id_kelas);
        $this->db->where('n.semester', $semester);
        $this->db->order_by('m.nama_mapel', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Ambil daftar kelas yang punya nilai + data nilai, ekskul, dan kehadiran
     */
    public function get_kelas_dan_nilai($no_induk)
    {
        $result = [];

        // Ambil daftar kelas yang siswa ini punya data nilai (distinct)
        $this->db->select('DISTINCT n.id_kelas, k.nama_kelas');
        $this->db->from('nilai n');
        $this->db->join('kelas k', 'k.id_kelas = n.id_kelas', 'left');
        $this->db->where('n.no_induk', $no_induk);
        $kelas_list = $this->db->get()->result();

        foreach ($kelas_list as $k) {
            $id_kelas = $k->id_kelas;
            $nama_kelas = $k->nama_kelas;

            $semester_data = [];
            $punya_nilai_mapel = false;

            // Cek semester 1 dan 2
            for ($semester = 1; $semester <= 2; $semester++) {
                // --- Ambil nilai mapel ---
                $this->db->select('m.nama_mapel, n.nilai, n.keterangan');
                $this->db->from('nilai n');
                $this->db->join('mapel m', 'm.id_mapel = n.id_mapel', 'left');
                $this->db->where([
                    'n.no_induk' => $no_induk,
                    'n.id_kelas' => $id_kelas,
                    'n.semester' => $semester
                ]);
                $nilai_mapel = $this->db->get()->result();

                // --- Ambil data ekskul ---
                $this->db->select('*');
                $this->db->from('nilai_ekskul');
                $this->db->where([
                    'no_induk' => $no_induk,
                    'id_kelas' => $id_kelas,
                    'semester' => $semester
                ]);
                $data_ekskul = $this->db->get()->result();

                // --- Ambil rekap kehadiran ---
                $rekap = $this->db->get_where('rekap_kehadiran', [
                    'no_induk' => $no_induk,
                    'id_kelas' => $id_kelas,
                    'semester' => $semester
                ])->row();

                // Simpan per semester
                $semester_data[$semester] = [
                    'mapel' => $nilai_mapel,
                    'ekskul' => $data_ekskul,
                    'kehadiran' => $rekap
                ];

                // Cek apakah semester ini punya nilai mapel (tidak kosong dan nilainya terisi)
                if (!empty($nilai_mapel)) {
                    // pastikan minimal satu mapel punya nilai bukan null / kosong
                    foreach ($nilai_mapel as $n) {
                        if (!empty($n->nilai)) {
                            $punya_nilai_mapel = true;
                            break;
                        }
                    }
                }
            }

            // Hanya tambahkan kelas jika benar-benar punya nilai mapel
            if ($punya_nilai_mapel) {
                $result[$id_kelas] = [
                    'nama_kelas' => $nama_kelas,
                    'semester'   => $semester_data
                ];
            }
        }

        return $result;
    }


}
