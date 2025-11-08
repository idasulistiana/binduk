<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Buku Induk Siswa : <?= $siswa->no_induk ?> - <?= $siswa->nama_siswa ?></h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- ================== BIODATA SISWA ================== -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Identitas Siswa</h3>
                      <button class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#downloadModal">
                        <i class="fas fa-download"></i> Download
                      </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th style="width:20%">No Induk</th><td><?= $siswa->no_induk ?></td></tr>
                        <tr><th>Nama Siswa</th><td><?= $siswa->nama_siswa ?></td></tr>
                        <tr><th>Gender</th><td><?= $siswa->gender ?></td></tr>
                        <tr><th>Tempat Lahir</th><td><?= $siswa->tempat_lahir ?></td></tr>
                        <tr><th>Tanggal Lahir</th><td><?= $siswa->tgl_lahir ?></td></tr>
                        <tr><th>Agama</th><td><?= $siswa->agama ?></td></tr>
                        <tr><th>Alamat</th><td><?= $siswa->alamat ?></td></tr>
                        <tr><th>Nama Ayah</th><td><?= $siswa->nama_ayah ?></td></tr>
                        <tr><th>Nama Ibu</th><td><?= $siswa->nama_ibu ?></td></tr>
                        <tr><th>Tanggal Diterima</th><td><?= date('d-m-Y', strtotime($siswa->tgl_diterima)) ?></td></tr>
                    </table>
                </div>
            </div>

            <!-- ================== NILAI SISWA ================== -->
            <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Nilai Rapot</h3>
    </div>
    <div class="card-body">
        <div class="mt-4">
            <?php
            // Fungsi cek apakah mapel memiliki nilai
            function mapel_memiliki_nilai($mapel_list) {
                if (empty($mapel_list)) return false;
                foreach ($mapel_list as $m) {
                    $cols = ['nilai', 'nilai_akhir', 'nilai_final', 'nilai_rata'];
                    foreach ($cols as $col) {
                        if (isset($m->$col) && $m->$col !== null && $m->$col !== '') return true;
                    }
                }
                return false;
            }

            if (!empty($kelas_nilai)):
                $kelas_ada_nilai = [];
                foreach ($kelas_nilai as $id_kelas => $k) {
                    $ada = false;
                    for ($sem = 1; $sem <= 2; $sem++) {
                        $mapel = $k['semester'][$sem]['mapel'] ?? [];
                        if (mapel_memiliki_nilai($mapel)) { $ada = true; break; }
                    }
                    if ($ada) $kelas_ada_nilai[$id_kelas] = $k;
                }
            ?>

            <?php if (!empty($kelas_ada_nilai)): ?>
                <!-- Tabs Kelas -->
                <ul class="nav nav-tabs flex-nowrap" id="kelasTab" role="tablist" style="overflow-x:auto; white-space:nowrap;">
                    <?php $first = true; foreach ($kelas_ada_nilai as $id_kelas => $k): ?>
                        <li class="nav-item" style="display:inline-block; margin-right:5px;">
                            <a class="nav-link <?= $first ? 'active' : '' ?>"
                               id="tab-<?= $id_kelas ?>"
                               data-toggle="tab"
                               href="#kelas-<?= $id_kelas ?>"
                               role="tab"
                               aria-controls="kelas-<?= $id_kelas ?>"
                               aria-selected="<?= $first ? 'true' : 'false' ?>">
                               Kelas <?= htmlspecialchars($k['nama_kelas']) ?>
                            </a>
                        </li>
                    <?php $first = false; endforeach; ?>
                </ul>

                <div class="tab-content mt-3" id="kelasTabContent">
                    <?php $first_tab = true; foreach ($kelas_ada_nilai as $id_kelas => $k): ?>
                        <div class="tab-pane fade <?= $first_tab ? 'show active' : '' ?>" id="kelas-<?= $id_kelas ?>" role="tabpanel">
                            <?php $first_tab = false; ?>

                            <!-- Pilih Semester -->
                            <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                                <h5>Pilih Semester</h5>
                                <select class="form-control semester-select" data-kelas="<?= $id_kelas ?>" id="semesterSelect-<?= $id_kelas ?>" style="width:auto;">
                                    <option value="1" selected>Ganjil</option>
                                    <option value="2">Genap</option>
                                </select>
                            </div>

                            <?php for ($sem = 1; $sem <= 2; $sem++): 
                                $data_semester = $k['semester'][$sem] ?? [];
                                $nilai_mapel = $data_semester['mapel'] ?? [];
                                $data_ekskul = $data_semester['ekskul'] ?? [];
                                $rekap_kehadiran = $data_semester['kehadiran'] ?? null;

                                if (!mapel_memiliki_nilai($nilai_mapel)) continue;
                            ?>
                            <div class="semester-content" id="semester-<?= $id_kelas ?>-<?= $sem ?>" style="<?= $sem == 1 ? '' : 'display:none;' ?>">
                                <h5>Semester <?= $sem == 1 ? 'Ganjil' : 'Genap' ?></h5>

                                <table class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nama Mapel</th>
                                            <th class="text-center">Nilai Akhir</th>
                                            <th>Capaian Pembelajaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $total_nilai = 0; 
                                            $count_nilai = 0; 
                                            $mulok_rows = [];

                                            preg_match('/\d+/', $k['nama_kelas'], $matches);
                                            $kelas_number = isset($matches[0]) ? intval($matches[0]) : 0;

                                            foreach ($nilai_mapel as $n):
                                                $nilai = $n->nilai_akhir ?? $n->nilai ?? null;

                                                // Tentukan apakah mapel masuk MULOK
                                                $is_mulok = false;
                                                if ($n->kode_mapel == 'PLBJ' || ($n->kode_mapel == 'BING' && $kelas_number >= 3)) {
                                                    $is_mulok = true;
                                                    $mulok_rows[] = $n;
                                                }

                                                // Total & rata-rata dihitung semua nilai termasuk MULOK
                                                if ($nilai !== null) { 
                                                    $total_nilai += $nilai; 
                                                    $count_nilai++; 
                                                }

                                                // Mapel biasa ditampilkan jika bukan MULOK dan bukan BING
                                                if (
                                                    !$is_mulok &&
                                                    $n->kode_mapel != 'BING' &&
                                                    !($n->kode_mapel == 'IPADSI' && in_array($kelas_number, [1, 2, 3, 4]))
                                                ): ?>
                                                    <tr>
                                                        <td><?= $n->nama_mapel ?></td>
                                                        <td class="text-center"><?= $nilai ?? '-' ?></td>
                                                        <td><?= $n->deskripsi_capaian ?? '-' ?></td>
                                                    </tr>
                                                <?php endif; 
                                            endforeach;
                                        // Tampilkan MULOK
                                        if (!empty($mulok_rows)): ?>
                                            <tr class="table-secondary text-center" style="font-weight:bold;"><th colspan="3">MULOK</th></tr>
                                            <?php foreach ($mulok_rows as $n):
                                                $nilai = $n->nilai_akhir ?? $n->nilai ?? null;
                                            ?>
                                                <tr>
                                                    <td><?= $n->nama_mapel ?></td>
                                                    <td class="text-center"><?= $nilai ?? '-' ?></td>
                                                    <td><?= $n->deskripsi_capaian ?? '-' ?></td>
                                                </tr>
                                            <?php endforeach; 
                                        endif; ?>

                                        <tr>
                                            <th class="text-right bg-light text-dark">Jumlah Nilai</th>
                                            <th class="text-center bg-light text-dark"><?= $total_nilai ?></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th class="text-right bg-light text-dark">Rata-rata Nilai</th>
                                            <th class="text-center bg-light text-dark"><?= $count_nilai > 0 ? round($total_nilai / $count_nilai, 2) : 0 ?></th>
                                            <th></th>
                                        </tr>

                                                    <!-- ================= EKSKUL ================= -->
                                                   <tr class="table-secondary text-center" style="font-weight:bold;"><th colspan="3">Ekstrakurikuler</th></tr>
                                                        <?php
                                                        // Filter hanya yang punya nilai
                                                        $ekskul_exist = array_filter($data_ekskul, function($e) { 
                                                            return isset($e->nilai) && $e->nilai !== null && trim($e->nilai) !== '' && trim($e->nilai) !== '-';
                                                        });

                                                        // Pisahkan Pramuka dari yang lain
                                                        $pramuka = [];
                                                        $lainnya = [];

                                                        foreach ($ekskul_exist as $e) {
                                                            if (stripos($e->nama_ekskul, 'pramuka') !== false) {
                                                                $pramuka[] = $e;
                                                            } else {
                                                                $lainnya[] = $e;
                                                            }
                                                        }

                                                        $tampil_ekskul = array_merge($pramuka, $lainnya);
                                                        ?>

                                                        <?php if (!empty($tampil_ekskul)): ?>
                                                            <?php foreach ($tampil_ekskul as $e): ?>
                                                                <tr>
                                                                    <td><?= $e->nama_ekskul ?></td>
                                                                    <td><?= $e->nilai ?? '-' ?></td>
                                                                    <td><?= $e->deskripsi_ekskul ?? '-' ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr><td colspan="3" class="text-center">Belum ada nilai ekskul</td></tr>
                                                        <?php endif; ?>


                                                    <!-- ================= KEHADIRAN ================= -->
                                                    <tr class="table-secondary text-center" style="font-weight:bold;"><th colspan="3">Rekap Kehadiran</th></tr>
                                                    <tr>
                                                        <td>Sakit</td>
                                                        <td colspan="2"><?= $rekap_kehadiran->sakit ?? 0 ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Izin</td>
                                                        <td colspan="2"><?= $rekap_kehadiran->izin ?? 0 ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tanpa Keterangan</td>
                                                        <td colspan="2"><?= $rekap_kehadiran->tanpa_keterangan ?? 0 ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mt-3">Tidak ada kelas yang memiliki nilai mapel.</div>
                        <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-warning">Belum ada data nilai untuk siswa ini.</div>
                        <?php endif; ?>
                    </div>
                </div>
                    <!-- Modal Popup -->
                    <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="downloadModalLabel">Pilih Data yang Akan Didownload</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form id="downloadForm" action="<?= base_url('ControllerBukuIndukSiswa/download_data'); ?>" method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="no_induk" value="<?= $siswa->no_induk ?>">
                                <div class="form-group">
                                    <label><strong>Data yang ingin disertakan:</strong></label>
                                    <div class="ml-3">
                                    <!-- Biodata -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="data[]" value="biodata" id="biodata">
                                        <label class="form-check-label" for="biodata">Biodata Siswa</label>
                                    </div>

                                    <hr>

                                    <!-- Nilai Rapot -->
                                    <label><strong>Nilai Rapot</strong></label>

                                    <!-- Checklist All -->
                                    <div class="form-check ml-3 mb-2">
                                        <input class="form-check-input" type="checkbox" id="checkAllKelas">
                                        <label class="form-check-label font-weight-bold text-primary" for="checkAllKelas">Checklist All</label>
                                    </div>

                                    <!-- Daftar kelas -->
                                    <?php if (!empty($kelas_ada_nilai)): ?>
                                        <?php foreach ($kelas_ada_nilai as $id_kelas => $k): ?>
                                            <div class="form-check ml-3">
                                                <input class="form-check-input kelas-checkbox" 
                                                    type="checkbox" 
                                                    name="data[]" 
                                                    value="kelas_<?= $id_kelas ?>" 
                                                    id="kelas_<?= $id_kelas ?>">
                                                <label class="form-check-label" for="kelas_<?= $id_kelas ?>">
                                                    Kelas <?= htmlspecialchars($k['nama_kelas']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="ml-3 text-muted">Tidak ada kelas yang memiliki nilai mapel.</p>
                                    <?php endif; ?>
                                    <hr>
                                    <!-- Data kenaikan -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="data[]" value="kenaikan" id="kenaikan">
                                        <label class="form-check-label" for="kenaikan">Data Kenaikan Kelas</label>
                                    </div>
                                    </div>
                                </div>
                                </div>

                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success"  >Download</button>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>
            </div>

            <!-- ================== DATA TAHUN KENAIKAN ================== -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Tahun Kenaikan Kelas Siswa</h3>
                </div>
                    <div class="card-body">
                    <?php if (!empty($klapper)): ?>
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Kelas 1</th>
                                    <th>Kelas 2</th>
                                    <th>Kelas 3</th>
                                    <th>Kelas 4</th>
                                    <th>Kelas 5</th>
                                    <th>Kelas 6</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= $klapper->kelas_1 ?? '-' ?></td>
                                    <td><?= $klapper->kelas_2 ?? '-' ?></td>
                                    <td><?= $klapper->kelas_3 ?? '-' ?></td>
                                    <td><?= $klapper->kelas_4 ?? '-' ?></td>
                                    <td><?= $klapper->kelas_5 ?? '-' ?></td>
                                    <td><?= $klapper->kelas_6 ?? '-' ?></td>
                                    <td><?= $klapper->keterangan ?? '-' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning">Data tahun kenaikan belum tersedia.</div>
                    <?php endif; ?>
                </div>
            </div>
            </div>    
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="semesterSelect-"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const idKelas = this.id.replace('semesterSelect-', '');
            const selected = this.value;
            document.querySelectorAll(`#semester-${idKelas}-1, #semester-${idKelas}-2`).forEach(el => el.style.display = 'none');
            document.querySelector(`#semester-${idKelas}-${selected}`).style.display = 'block';
        });
    });
});

  document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAllKelas');
    const kelasCheckboxes = document.querySelectorAll('.kelas-checkbox');

    checkAll.addEventListener('change', function () {
      kelasCheckboxes.forEach(cb => cb.checked = this.checked);
    });

    // Jika semua kelas dicentang/dihapus manual, update status "Checklist All"
    kelasCheckboxes.forEach(cb => {
      cb.addEventListener('change', function () {
        const allChecked = Array.from(kelasCheckboxes).every(checkbox => checkbox.checked);
        checkAll.checked = allChecked;
      });
    });
  });

  // 🔹 Jalankan fungsi download, lalu tutup modal
  document.getElementById('downloadForm').addEventListener('submit', function() {
    // Beri jeda sedikit agar proses download dimulai
    setTimeout(function() {
      $('#downloadModal').modal('hide'); // pakai jQuery Bootstrap 4
    }, 800); // 0.8 detik
  });

</script>
