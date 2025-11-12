<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// Ambil session dari CodeIgniter
$CI =& get_instance();
$level_user = $CI->session->userdata('level_user');
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-book"></i> Data Mata Pelajaran</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Mata Pelajaran</li>
                    </ol>
                </div>
            </div>

            <!-- Alert Success -->
            <?php if ($this->session->userdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    <?= $this->session->userdata('success'); ?>
                </div>
            <?php endif; ?>

            <!-- Alert Error -->
            <?php if ($this->session->userdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
                    <?= $this->session->userdata('error'); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header d-flex p-0 align-items-center">
                            <h3 class="card-title p-3">Informasi Mata Pelajaran</h3>

                            <?php if ($level_user != 2): ?>
                                <ul class="nav nav-pills ml-auto p-2">
                                    <li class="nav-item">
                                        <a class="btn btn-success text-white active" data-toggle="tab" href="#tab_1" style="margin-right:10px">
                                            <i class="fas fa-list"></i> Daftar Mata Pelajaran
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-primary text-white" data-toggle="tab" href="#tab_2">
                                            <i class="fas fa-plus"></i> Tambah Mata Pelajaran
                                        </a>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">

                                <!-- Tab Daftar Mata Pelajaran -->
                                <div class="tab-pane fade show active" id="tab_1">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mata Pelajaran</th>
                                                    <?php if ($level_user != 2): ?>
                                                        <th>Action</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; foreach($mapel as $m): ?>
                                                    <tr class="text-center">
                                                        <td><?= $no++; ?></td>
                                                        <td><?= htmlspecialchars($m->nama_mapel); ?></td>
                                                        <?php if ($level_user != 2): ?>
                                                            <td>
                                                                <a href="<?= base_url('mapel/edit_mapel/'.$m->id_mapel); ?>" class="btn btn-success btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $m->id_mapel; ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>

                                                    <!-- Modal Delete -->
                                                    <div class="modal fade" id="deleteModal<?= $m->id_mapel; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $m->id_mapel; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel<?= $m->id_mapel; ?>">Konfirmasi Hapus</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menghapus mata pelajaran 
                                                                    <strong><?= htmlspecialchars($m->nama_mapel); ?></strong>?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <a href="<?= base_url('mapel/delete_mapel/'.$m->id_mapel); ?>" class="btn btn-danger">Hapus</a>
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
                                <div class="tab-pane fade" id="tab_2">
                                    <form action="<?= base_url('mapel/add_mapel'); ?>" method="POST">
                                        <div class="form-group">
                                            <label for="nama_mapel">Nama Mata Pelajaran</label>
                                            <input type="text" name="nama_mapel" id="nama_mapel" class="form-control" placeholder="Masukkan Nama Mata Pelajaran" required>
                                        </div>
                                        <div class="text-right mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="<?= base_url('mapel'); ?>" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>

                            </div> <!-- end tab-content -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                </div>
            </div>
        </div>
    </section>
</div>
