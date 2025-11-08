<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Alumni</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Alumni</li>
                    </ol>
                </div>
            </div>

            <?php if ($this->session->userdata('success')) { ?>
                <div class="alert alert-success alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                    <?= $this->session->userdata('success') ?>
                </div>
            <?php } ?>

        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="far fa-edit"></i> Update Data Alumni</h3>
                        </div>

                        <form role="form" action="<?= base_url('alumni/update_alumni/' . $alumni->nisn) ?>" method="POST">
                            <div class="card-body">
                                <!-- Row 1: NISN & Nama Alumni -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>NISN Alumni</label>
                                            <input type="text" name="nisn" value="<?= $alumni->nisn ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama Alumni</label>
                                            <input type="text" name="nama_siswa" value="<?= $alumni->nama_siswa ?>" class="form-control" placeholder="Masukkan Nama Alumni" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: No Induk & Gender -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>No Induk</label>
                                            <input type="text" name="no_induk" value="<?= $alumni->no_induk ?>" class="form-control" placeholder="Masukkan Nomor Induk">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki" <?= strtolower($alumni->gender) == 'laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                                <option value="Perempuan" <?= strtolower($alumni->gender) == 'perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <!-- Row 3: Tempat Lahir & Tanggal Lahir -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" value="<?= $alumni->tempat_lahir ?>" class="form-control" placeholder="Masukkan Tempat Lahir">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" name="tgl_lahir" value="<?= $alumni->tgl_lahir ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 4: Agama & Tahun Lulus -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Agama</label>
                                            <select name="agama" class="form-control">
                                                <option value="">-- Pilih Agama --</option>
                                                <option value="Islam" <?= $alumni->agama == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                                <option value="Kristen Protestan" <?= $alumni->agama == 'Kristen Protestan' ? 'selected' : '' ?>>Kristen Protestan</option>
                                                <option value="Katolik" <?= $alumni->agama == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                                <option value="Hindu" <?= $alumni->agama == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                                <option value="Buddha" <?= $alumni->agama == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                                <option value="Konghucu" <?= $alumni->agama == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tahun Lulus</label>
                                            <input type="text" name="tahun_lulus" value="<?= $alumni->tahun_lulus ?>" class="form-control" placeholder="Masukkan Tahun Lulus">
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 5: Nama Ayah & Nama Ibu -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama Ayah</label>
                                            <input type="text" name="nama_ayah" value="<?= $alumni->nama_ayah ?>" class="form-control" placeholder="Masukkan Nama Ayah">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama Ibu</label>
                                            <input type="text" name="nama_ibu" value="<?= $alumni->nama_ibu ?>" class="form-control" placeholder="Masukkan Nama Ibu">
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 6: Alamat -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan Alamat"><?= $alumni->alamat ?></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <a href="<?= base_url('alumni') ?>" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>

                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>
