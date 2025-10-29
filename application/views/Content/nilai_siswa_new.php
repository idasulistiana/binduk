<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Input Nilai Siswa</h1>
            <p><strong><?= $siswa->nama_siswa ?> (<?= $siswa->no_induk ?>)</strong></p>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- Alert Success/Error -->
            <?php if ($this->session->userdata('success')): ?>
                <div class="alert alert-success"><?= $this->session->userdata('success') ?></div>
            <?php endif; ?>
            <?php if ($this->session->userdata('error')): ?>
                <div class="alert alert-danger"><?= $this->session->userdata('error') ?></div>
            <?php endif; ?>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Input Nilai</h3>
                </div>
                <form action="<?= base_url('nilai/add_nilai/'.$siswa->no_induk) ?>" method="POST">
                    <div class="form-group">
                        <label>Pilih Kelas</label>
                        <select name="id_kelas" class="form-control" required>
                            <?php foreach($kelas as $k): ?>
                                <option value="<?= $k->id_kelas ?>"><?= $k->nama_kelas ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>

                    <h4>Input Nilai Mata Pelajaran</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Mapel</th>
                                <th>Nilai Akhir</th>
                                <th>Capaian Pembelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($mapel as $m): ?>
                                <tr>
                                    <td><?= $m->nama_mapel ?></td>
                                    <td>
                                        <input type="number" name="mapel[<?= $m->id_mapel ?>]" class="form-control" min="0" max="100">
                                    </td>
                                    <td>
                                        <input type="text" name="capaian[<?= $m->id_mapel ?>]" class="form-control" placeholder="Keterangan capaian">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Simpan Semua Nilai</button>
                </form>

            </div>

        </div>
    </section>
</div>
