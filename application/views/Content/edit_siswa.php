<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Siswa</li>
                    </ol>
                </div>
            </div>

            <?php if ($this->session->userdata('success')) { ?>
                <div class="alert alert-success alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    <?= $this->session->userdata('success') ?>
                </div>
            <?php } ?>

        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->   
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="far fa-edit"></i> Update Data Siswa</h3>
                        </div>           
                       <form role="form" action="<?= base_url('siswa/update_siswa/' . $siswa->nisn) ?>" method="POST">
                            <div class="card-body">
                                <!-- Row 1: NISN & Nama Siswa -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>NISN Siswa</label>
                                            <input type="text" name="nisn" value="<?= $siswa->nisn ?>" class="form-control" readonly>
                                            <?= form_error('nisn', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama Siswa</label>
                                            <input type="text" name="nama" value="<?= $siswa->nama_siswa ?>" class="form-control" placeholder="Masukkan Nama Siswa">
                                            <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 2: No Induk & Gender -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>No Induk</label>
                                            <input type="number" name="no_induk" value="<?= $siswa->no_induk ?>" class="form-control" placeholder="Masukkan Nomor Induk" readonly>
                                            <?= form_error('no_induk', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-select form-control">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-Laki" <?= $siswa->gender == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                                                <option value="Perempuan" <?= $siswa->gender == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                            <?= form_error('gender', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 3: Agama & Tempat Lahir -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Agama</label>
                                            <select name="agama" class="form-select form-control">
                                                <option value="">-- Pilih Agama --</option>
                                                <option value="Islam" <?= $siswa->agama == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                                <option value="Kristen Protestan" <?= $siswa->agama == 'Kristen Protestan' ? 'selected' : '' ?>>Kristen Protestan</option>
                                                <option value="Katolik" <?= $siswa->agama == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                                <option value="Hindu" <?= $siswa->agama == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                                <option value="Buddha" <?= $siswa->agama == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                                <option value="Konghucu" <?= $siswa->agama == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                                            </select>
                                            <?= form_error('agama', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tempat Lahir</label>
                                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?= $siswa->tempat_lahir ?>" class="form-control" placeholder="Masukkan Tempat Lahir">
                                            <?= form_error('tempat_lahir', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 4: Tanggal Lahir & Nama Ibu -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" name="tgl_lahir" value="<?= $siswa->tgl_lahir ?>" class="form-control">
                                            <?= form_error('tgl_lahir', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama Ibu</label>
                                            <input type="text" name="nama_ibu" value="<?= $siswa->nama_ibu ?>" class="form-control" placeholder="Masukkan Nama Ibu">
                                            <?= form_error('nama_ibu', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 5: Nama Ayah & Alamat -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama Ayah</label>
                                            <input type="text" name="nama_ayah" value="<?= $siswa->nama_ayah ?>" class="form-control" placeholder="Masukkan Nama Ayah">
                                            <?= form_error('nama_ayah', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                         <div class="form-group">
                                            <label>Sekolah Asal</label>
                                            <input type="text" name="sekolah_asal" value="<?= $siswa->sekolah_asal ?>" class="form-control" placeholder="Masukkan Sekolah Asal">
                                            <?= form_error('sekolah_asal', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Row 6: Sekolah Asal & Tanggal Diterima -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan Alamat"><?= $siswa->alamat ?></textarea>
                                            <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tanggal Diterima</label>
                                            <input type="date" name="tgl_diterima" value="<?= $siswa->tgl_diterima ?>" class="form-control">
                                            <?= form_error('tgl_diterima', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <a href="<?= base_url('siswa') ?>" class="btn btn-secondary mright-10">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
 