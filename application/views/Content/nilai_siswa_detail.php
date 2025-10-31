<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Nilai Siswa</h1>
            <p><strong><?= $siswa->nama_siswa ?> (<?= $siswa->no_induk ?>)</strong></p>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" style="background-color:#fff; padding:20px; border-radius:5px; box-shadow:0 0 5px rgba(0,0,0,0.1);">

                    <?php if (!empty($kelas_nilai)): ?>
                        <!-- ==================== TAB KELAS ==================== -->
                        <ul class="nav nav-tabs flex-nowrap" id="kelasTab" role="tablist" style="overflow-x:auto; white-space:nowrap; border-bottom:2px solid #dee2e6;">
                            <?php $first = true; foreach ($kelas_nilai as $id_kelas => $k): ?>
                                <li class="nav-item" style="display:inline-block; margin-right:5px;">
                                    <a class="nav-link <?= $first ? 'active' : '' ?>"
                                       id="tab-<?= $id_kelas ?>"
                                       data-toggle="tab"
                                       href="#kelas-<?= $id_kelas ?>"
                                       role="tab"
                                       aria-controls="kelas-<?= $id_kelas ?>"
                                       aria-selected="<?= $first ? 'true' : 'false' ?>">
                                        Kelas <?= $k['nama_kelas'] ?>
                                    </a>
                                </li>
                            <?php $first = false; endforeach; ?>
                        </ul>

                        <!-- ==================== KONTEN TAB ==================== -->
                        <div class="tab-content mt-3" id="kelasTabContent">
                            <?php $first_tab = true; foreach ($kelas_nilai as $id_kelas => $k): ?>
                                <div class="tab-pane fade <?= $first_tab ? 'show active' : '' ?>" id="kelas-<?= $id_kelas ?>" role="tabpanel">
                                    <?php $first_tab = false; ?>

                                    <!-- Select semester -->
                                    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                                        <h5>Pilih Semester</h5>
                                        <select class="form-control" id="semesterSelect-<?= $id_kelas ?>" style="width:auto;">
                                            <option value="1" selected>Ganjil</option>
                                            <option value="2">Genap</option>
                                        </select>
                                    </div>

                                    <?php for ($sem = 1; $sem <= 2; $sem++): ?>
                                        <div class="semester-content" id="semester-<?= $id_kelas ?>-<?= $sem ?>" style="<?= $sem == 1 ? '' : 'display:none;' ?>">
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <h5>Semester <?= $sem == 1 ? 'Ganjil' : 'Genap' ?></h5>
                                                <a href="<?= base_url('nilai/edit_siswa/'.$siswa->no_induk) . '?id_kelas='.$id_kelas.'&semester='.$sem ?>" 
                                                   class="btn btn-warning btn-sm" style= "margin-bottom:5px">Edit Nilai</a>
                                            </div>

                                            <?php 
                                                $data_semester = $k['semester'][$sem] ?? [];
                                                $nilai_mapel = $data_semester['mapel'] ?? [];
                                                $data_ekskul = $data_semester['ekskul'] ?? [];
                                                $rekap_kehadiran = $data_semester['kehadiran'] ?? null;
                                            ?>

                                            <?php if (!empty($nilai_mapel)): ?>
                                                <!-- ===================== TABEL NILAI MAPEL ===================== -->
                                                <table class="table table-bordered table-striped">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Nama Mapel</th>
                                                            <th class='text-center'>Nilai Akhir</th>
                                                            <th class='text-center'>Capaian Pembelajaran</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            $total_nilai = 0; $count_nilai = 0;
                                                            $mulok_rows = []; $kelas_number = 0;

                                                            preg_match('/\d+/', $k['nama_kelas'], $matches);
                                                            $kelas_number = isset($matches[0]) ? intval($matches[0]) : 0;

                                                            foreach ($nilai_mapel as $n) {
                                                                $nilai = $n->nilai_akhir ?? null;
                                                                if ($nilai !== null) { $total_nilai += $nilai; $count_nilai++; }

                                                                $is_mulok = false;
                                                                if ($n->kode_mapel == 'PLBJ' || ($n->kode_mapel == 'BING' && $kelas_number >= 3)) {
                                                                    $is_mulok = true;
                                                                    $mulok_rows[] = $n;
                                                                }

                                                                if (!$is_mulok) {
                                                                    echo "<tr>
                                                                        <td>{$n->nama_mapel}</td>
                                                                        <td class='text-center' >".($nilai ?? '-')."</td>
                                                                        <td class='text-left'>".($n->deskripsi_capaian ?? '-')."</td>
                                                                    </tr>";
                                                                }
                                                            }

                                                            if (!empty($mulok_rows)) {
                                                                echo '<tr class="table-secondary text-center" style="font-weight:bold;"><th colspan="3">MULOK</th></tr>';
                                                                foreach ($mulok_rows as $n) {
                                                                    echo "<tr>
                                                                        <td>{$n->nama_mapel}</td>
                                                                        <td class='text-center'>{$n->nilai_akhir}</td>
                                                                        <td class='text-left'>".($n->deskripsi_capaian ?? '-')."</td>
                                                                    </tr>";
                                                                }
                                                            }

                                                            echo "<tr>
                                                                    <th class='text-center bg-light text-dark'>Jumlah Nilai</th>
                                                                    <th class='text-center bg-light text-dark'>{$total_nilai}</th>
                                                                    <th class='text-center bg-light text-dark'></th>
                                                                  </tr>
                                                                  <tr>
                                                                    <th class='text-center bg-light text-dark' >Rata-rata Nilai</th>
                                                                    <th class='text-center bg-light text-dark'>".(($count_nilai > 0) ? round($total_nilai/$count_nilai, 2) : 0)."</th>
                                                                    <th class='text-center bg-light text-dark'></th>
                                                                  </tr>";
                                                        ?>
                                                        <!-- ===================== NILAI EKSKUL ===================== -->
                                                        <tr class="table-secondary text-center" style="font-weight:bold;"><th colspan="3">Ekstrakurikuler</th></tr>
                                                        <?php if (!empty($data_ekskul)): ?>
                                                            <?php foreach ($data_ekskul as $e): ?>
                                                                <tr>
                                                                    <td><?= $e->nama_ekskul ?></td>
                                                                    <td class='text-center'><?= $e->nilai ?? '-' ?></td>
                                                                    <td class='text-left'><?= $e->deskripsi_ekskul ?? '-' ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr><td colspan="3" class="text-center">Belum ada nilai ekskul</td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                                <!-- ===================== TABEL REKAP KEHADIRAN ===================== -->
                                                <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                                                    <h6><strong>Rekap Kehadiran</strong></h6>

                                                    <?php if (!empty($rekap_kehadiran) && !empty($rekap_kehadiran->id_rekap)): ?>
                                                        <a href="<?= base_url('kehadiran/edit_siswa/' . $rekap_kehadiran->id_rekap) ?>" 
                                                        class="btn btn-warning btn-sm">
                                                        Edit Kehadiran
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="<?= site_url('kehadiran?auto_add=1#tab_2') ?>" 
                                                            class="btn btn-primary"
                                                            onclick="sessionStorage.setItem('previous_url', window.location.href)">
                                                            Tambah Rekap
                                                        </a>
                                                    <?php endif; ?>
                                                </div>

                                                <table class="table table-bordered table-striped">
                                                    <thead class="thead-dark">
                                                        <tr class="text-center">
                                                            <th>Tahun Ajaran</th>
                                                            <th>Sakit</th>
                                                            <th>Izin</th>
                                                            <th>Tanpa Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                            // Gunakan default jika datanya kosong
                                                            $sakit = $rekap_kehadiran->sakit ?? 0;
                                                            $izin  = $rekap_kehadiran->izin ?? 0;
                                                            $tanpa = $rekap_kehadiran->tanpa_keterangan ?? 0;
                                                            $tahun = $rekap_kehadiran->tahun_ajaran ?? '-';
                                                        ?>
                                                        <tr class="text-center">
                                                            <td><?= $tahun ?></td>
                                                            <td><?= $sakit ?></td>
                                                            <td><?= $izin ?></td>
                                                            <td><?= $tanpa ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                              <div class="bg-warning text-dark p-2 rounded"> Belum ada nilai untuk siswa ini.</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-warning text-dark p-2 rounded" style=>Belum ada data nilai untuk siswa ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ==================== SCRIPT UNTUK SELECT SEMESTER ==================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="semesterSelect-"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const idKelas = this.id.replace('semesterSelect-', '');
            const selected = this.value;

            // sembunyikan semua semester
            document.querySelectorAll(`#semester-${idKelas}-1, #semester-${idKelas}-2`).forEach(function(el) {
                el.style.display = 'none';
            });

            // tampilkan semester yang dipilih
            document.querySelector(`#semester-${idKelas}-${selected}`).style.display = 'block';
        });
    });
});

</script>
