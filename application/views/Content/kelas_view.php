<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
    // Ambil session dari CodeIgniter
    $CI =& get_instance();
    $level_user = $CI->session->userdata('level_user');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chalkboard"></i> Data Kelas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Kelas</li>
                    </ol>
                </div>
            </div>
            <!-- Alert Success -->
            <?php if ($this->session->userdata('success')) { ?>
                <div class="alert alert-success alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    <?= $this->session->userdata('success') ?>
                </div>
            <?php } ?>

            <!-- Alert Error -->
            <?php if ($this->session->userdata('error')) { ?>
                <div class="alert alert-danger alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
                    <?= $this->session->userdata('error') ?>
                </div>
            <?php } ?>
        </div>
    </section>
    <section class="content">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Informasi Kelas</h3>
                            <?php if ($level_user != 2): ?>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item">
                                        <button class="btn btn-success nav-link text-white active" href="#tab_1" data-toggle="tab" style="margin-right:10px">
                                            <i class="fas fa-list"></i> Daftar Kelas
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-primary nav-link text-white" href="#tab_2" data-toggle="tab">
                                            <i class="fas fa-plus"></i> Tambah Kelas
                                        </button>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Daftar Kelas -->
                                <div class="tab-pane active" id="tab_1">
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Nama Kelas</th>
                                                    <?php if ($level_user != 2): ?>
                                                    <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $no = 1;

                                                    // Jika level_user = 1 → tampilkan semua kelas
                                                    if ($level_user == 1): 
                                                        foreach ($kelas as $k): ?>
                                                            <tr>
                                                                <td class="text-center"><?= $no++ ?></td>
                                                                <td class="text-center"><?= $k->nama_kelas ?></td>
                                                                <td class="text-center"><?= $k->status == 1 ? 'Aktif' : 'Tidak Aktif'; ?></td>
                                                                <td class="text-center">
                                                                    <a href="<?= base_url('kelas/edit_kelas/'.$k->id_kelas) ?>" class="btn btn-success btn-sm">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $k->id_kelas ?>">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>

                                                    <?php 
                                                    // Jika level_user = 2 → hanya tampilkan kelas aktif
                                                    elseif ($level_user == 2): 
                                                        $no = 1;
                                                        foreach ($kelas_aktif as $k): ?>
                                                            <tr>
                                                                <td class="text-center"><?= $no++ ?></td>
                                                                <td class="text-center"><?= $k->nama_kelas ?></td>
                                                            </tr>

                                                    <!-- Modal Delete -->
                                                    <div class="modal fade" id="deleteModal<?= $k->id_kelas ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $k->id_kelas?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menghapus kelas <strong><?= $k->nama_kelas ?></strong>?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <a href="<?= base_url('kelas/delete_kelas/'.$k->id_kelas) ?>" class="btn btn-danger">Hapus</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab Tambah Kelas -->
                                <div class="tab-pane" id="tab_2">
                                    <form action="<?= base_url('kelas/add_kelas') ?>" method="POST">
                                        <div class="form-group">
                                            <label>Nama Kelas</label>
                                            <input type="text" name="nama_kelas" class="form-control" placeholder="Masukkan Nama Kelas" required>
                                        </div>
                                        <div class="card-footer text-end mt-3 text-right">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

