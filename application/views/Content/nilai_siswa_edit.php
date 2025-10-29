<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Nilai Siswa</h1>
            <p><strong><?= $siswa->nama_siswa ?> (<?= $siswa->no_induk ?>)</strong></p>
        </div>
    </section>

    <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
    <input type="hidden" name="semester" value="<?= $semester ?>">

    <section class="content">
        <div class="container-fluid">

            <!-- Alert Success/Error -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>

            <div class="card card-primary">
                <div class="card-header bg-dark text-light">
                    <h3 class="card-title">Pilih Kelas & Semester</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('nilai/edit_siswa/'.$siswa->no_induk) ?>" method="POST">
                        <div class="form-group">
                            <label>Pilih Kelas</label>
                            <select name="id_kelas" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php foreach($kelas as $k): ?>
                                    <option value="<?= $k->id_kelas ?>" <?= (isset($id_kelas) && $id_kelas == $k->id_kelas) ? 'selected' : '' ?>>
                                        <?= $k->nama_kelas ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pilih Semester</label>
                            <select name="semester" class="form-control" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="1" <?= (isset($semester) && $semester == 1) ? 'selected' : '' ?>>Semester 1</option>
                                <option value="2" <?= (isset($semester) && $semester == 2) ? 'selected' : '' ?>>Semester 2</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Tampilkan Nilai</button>
                    </form>

                    <div class="resultscore">
                        <?php if (!empty($submitted)): ?>
                            <hr>
                            <h4>
                                <?= !empty($nama_kelas) ? ' Kelas '.$nama_kelas : '' ?>
                                <?= !empty($semester) ? ' - Semester '.$semester : '' ?>
                            </h4>
<?php if (!empty($nilai)): ?>

    <!-- TABEL MATA PELAJARAN -->
    <h5><strong>Nilai Mata Pelajaran</strong></h5>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Nama Mapel</th>
                <th>Nilai Akhir</th>
                <th>Capaian Pembelajaran</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($nilai as $n): ?>
                <?php 
                    // Mata pelajaran umum, kecuali PLBJ dan Bahasa Inggris
                    if (!in_array($n->nama_mapel, ['Pendidikan Lingkungan dan Budaya Jakarta', 'Bahasa Inggris'])):
                ?>
                    <tr>
                        <td><?= $n->nama_mapel ?></td>
                        <td><?= (empty($n->nilai_akhir) || floatval($n->nilai_akhir) == 0) ? '-' : $n->nilai_akhir ?></td>
                        <td><?= !empty($n->capaian_pembelajaran) ? $n->capaian_pembelajaran : '-' ?></td>
                        <td>
                            <?php if (empty($n->id_nilai)): ?>
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addModal<?= $n->id_mapel ?>">Tambah</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $n->id_nilai ?>">Edit</button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- MODAL TAMBAH -->
                    <?php if (empty($n->id_nilai)): ?>
                        <div class="modal fade" id="addModal<?= $n->id_mapel ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?= base_url('nilai/store_nilai/'.$siswa->no_induk) ?>" method="POST">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Tambah Nilai - <?= $n->nama_mapel ?></h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_mapel" value="<?= $n->id_mapel ?>">
                                            <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
                                            <input type="hidden" name="semester" value="<?= $semester ?>">
                                            <div class="form-group">
                                                <label>Nilai Akhir</label>
                                                <input type="number" class="form-control" name="nilai_akhir" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Capaian Pembelajaran</label>
                                                <input type="text" class="form-control" name="capaian_pembelajaran">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- MODAL EDIT -->
                    <?php if (!empty($n->id_nilai)): ?>
                        <div class="modal fade" id="editModal<?= $n->id_nilai ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?= base_url('nilai/update_nilai/'.$siswa->no_induk) ?>" method="POST">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">Edit Nilai - <?= $n->nama_mapel ?></h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_nilai" value="<?= $n->id_nilai ?>">
                                            <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
                                            <input type="hidden" name="semester" value="<?= $semester ?>">
                                            <div class="form-group">
                                                <label>Nilai Akhir</label>
                                                <input type="number" class="form-control" name="nilai_akhir" value="<?= $n->nilai_akhir ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Capaian Pembelajaran</label>
                                                <input type="text" class="form-control" name="capaian_pembelajaran" value="<?= $n->capaian_pembelajaran ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- TABEL MULOK -->
    <!-- TABEL MULOK -->
<h5 class="mt-4"><strong>Nilai Muatan Lokal (MULOK)</strong></h5>
<table class="table table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Nama Mapel</th>
            <th>Nilai Akhir</th>
            <th>Capaian Pembelajaran</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $angka_kelas = intval($nama_kelas ?? 0);
            foreach($nilai as $n): 
                // ✅ PLBJ selalu tampil
                // ✅ Bahasa Inggris hanya tampil untuk kelas 3–6
                if (
                    $n->nama_mapel == 'Pendidikan Lingkungan dan Budaya Jakarta' ||
                    ($n->nama_mapel == 'Bahasa Inggris' && $angka_kelas >= 3 && $angka_kelas <= 6)
                ):
        ?>
            <tr>
                <td><?= $n->nama_mapel ?></td>
                <td><?= (empty($n->nilai_akhir) || floatval($n->nilai_akhir) == 0) ? '-' : $n->nilai_akhir ?></td>
                <td><?= !empty($n->capaian_pembelajaran) ? $n->capaian_pembelajaran : '-' ?></td>
                <td>
                    <?php if (empty($n->id_nilai)): ?>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addModalMulok<?= $n->id_mapel ?>">Tambah</button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModalMulok<?= $n->id_nilai ?>">Edit</button>
                    <?php endif; ?>
                </td>
            </tr>

            <!-- MODAL TAMBAH MULOK -->
            <?php if (empty($n->id_nilai)): ?>
                <div class="modal fade" id="addModalMulok<?= $n->id_mapel ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('nilai/store_nilai/'.$siswa->no_induk) ?>" method="POST">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Tambah Nilai - <?= $n->nama_mapel ?></h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_mapel" value="<?= $n->id_mapel ?>">
                                    <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
                                    <input type="hidden" name="semester" value="<?= $semester ?>">
                                    <div class="form-group">
                                        <label>Nilai Akhir</label>
                                        <input type="number" class="form-control" name="nilai_akhir" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Capaian Pembelajaran</label>
                                        <input type="text" class="form-control" name="capaian_pembelajaran">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- MODAL EDIT MULOK -->
            <?php if (!empty($n->id_nilai)): ?>
                <div class="modal fade" id="editModalMulok<?= $n->id_nilai ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('nilai/update_nilai/'.$siswa->no_induk) ?>" method="POST">
                                <div class="modal-header bg-warning text-dark">
                                    <h5 class="modal-title">Edit Nilai - <?= $n->nama_mapel ?></h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_nilai" value="<?= $n->id_nilai ?>">
                                    <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
                                    <input type="hidden" name="semester" value="<?= $semester ?>">
                                    <div class="form-group">
                                        <label>Nilai Akhir</label>
                                        <input type="number" class="form-control" name="nilai_akhir" value="<?= $n->nilai_akhir ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Capaian Pembelajaran</label>
                                        <input type="text" class="form-control" name="capaian_pembelajaran" value="<?= $n->capaian_pembelajaran ?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php endif; endforeach; ?>
    </tbody>
</table>

<?php endif; ?>



                            <!-- ==================== TABEL EKSKUL ==================== -->
                            <?php if (!empty($nilai_ekskul)): ?>
                                <h5 class="mt-4"><strong>Nilai Ekstrakurikuler</strong></h5>
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nama Ekskul</th>
                                            <th>Nilai</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($nilai_ekskul as $e): ?>
                                            <tr>
                                                <td><?= $e->nama_ekskul ?></td>
                                                <td><?= !empty($e->nilai) ? $e->nilai : '-' ?></td>
                                                <td><?= !empty($e->keterangan) ? $e->keterangan : '-' ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editEkskul<?= $e->id_ekskul ?>">Edit</button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="editEkskul<?= $e->id_ekskul ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="<?= base_url('nilai/update_nilai_ekskul/'.$siswa->no_induk) ?>" method="POST">
                                                            <div class="modal-header bg-warning text-dark">
                                                                <h5 class="modal-title">Edit Nilai - <?= $e->nama_ekskul ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_nilai_ekskul" value="<?= $e->id_nilai_ekskul ?>">
                                                                <input type="hidden" name="id_ekskul" value="<?= $e->id_ekskul ?>">
                                                                <input type="hidden" name="id_kelas" value="<?= $id_kelas ?>">
                                                                <input type="hidden" name="semester" value="<?= $semester ?>">
                                                                <div class="form-group">
                                                                    <label>Nilai</label>
                                                                    <input type="text" class="form-control" name="nilai" value="<?= $e->nilai ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Keterangan</label>
                                                                    <input type="text" class="form-control" name="keterangan" value="<?= $e->keterangan ?>">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
