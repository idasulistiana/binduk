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
                    <h1><i class="fas fa-book"></i> Data Ekstrakurikuler</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Mata Pelajaran</li>
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

    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex p-0">
                        <h3 class="card-title p-3">Informasi Ekstrakurikuler</h3>
                        <?php if ($level_user != 2): ?>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <button class="btn btn-success nav-link text-white active" href="#tab_1" data-toggle="tab" style="margin-right:10px">
                                        <i class="fas fa-list"></i> Daftar Ekstrakurikuler
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="btn btn-primary nav-link text-white" href="#tab_2" data-toggle="tab">
                                        <i class="fas fa-plus"></i> Tambah Ekstrakurikuler
                                    </button>
                                </li>
                            </ul>
                        <?php endif;?>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Tab Daftar Mata Pelajaran -->
                            <div class="tab-pane active" id="tab_1">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Ekstrakurikuler</th>
                                                <th class="text-center">Penanggung Jawab</th>
                                                <?php if ($level_user != 2): ?>
                                                    <th class="text-center">Action</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no=1; foreach($ekskul as $m): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center"><?= $m->nama_ekskul ?></td>
                                                    <th class="text-center"><?= $m->nama_pj ?></th>
                                                     <?php if ($level_user != 2): ?>
                                                        <td class="text-center">
                                                            <a href="<?= base_url('ekskul/edit_ekskul/'.$m->id_ekskul) ?>" class="btn btn-success btn-sm">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $m->id_ekskul ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                     <?php endif; ?>
                                                </tr>

                                                <!-- Modal Delete -->
                                                <div class="modal fade" id="deleteModal<?= $m->id_ekskul ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $m->id_ekskul ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus Ekstrakurikuler <strong><?= $m->nama_ekskul ?></strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <a href="<?= base_url('ekskul/delete_ekskul/'.$m->id_ekskul) ?>" class="btn btn-danger">Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Tambah Mata Pelajaran -->
                            <div class="tab-pane" id="tab_2">
                                <form action="<?= base_url('ekskul/add_ekskul') ?>" method="POST">
                                    <div class="form-group">
                                        <label>Nama Ekstrakurikuler</label>
                                        <input type="text" name="nama_ekskul" class="form-control" placeholder="Masukkan Nama Ekstrakurikuler" required>
                                    </div>
                                     <div class="form-group">
                                        <label>Nama Penanggung jawab Ekstrakurikuler</label>
                                        <input type="text" name="nama_pj" class="form-control" placeholder="Masukkan Nama Penanggung Jawab" required>
                                    </div>
                                    <div class="card-footer text-end mt-3 text-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="<?= base_url('ekskul') ?>" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

