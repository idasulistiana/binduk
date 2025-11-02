<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
    // Ambil session dari CodeIgniter
    $CI =& get_instance();
    $level_user = $CI->session->userdata('level_user');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Daftar Nilai  Siswa</h1>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">

            <!-- Alert -->
            <?php if ($this->session->userdata('success')): ?>
                <div class="alert alert-success alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-check"></i> <?= $this->session->userdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->userdata('error')): ?>
                <div class="alert alert-danger alert-dismissible mt-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-exclamation-triangle"></i> <?= $this->session->userdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="card">
                 <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Informasi Nilai Siswa</h3>
                     <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item">
                                <?php if ($level_user != 2): ?>
                                    <button class="btn btn-warning nav-link text-white" data-toggle="modal" data-target="#importCsvModal" style="margin-right: 10px">
                                        <i class="fas fa-plus"></i> Import Data
                                    </button>
                                <?php endif; ?>
                                <!-- Modal Upload CSV -->
                                <div class="modal fade" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="importCsvModalLabel">Import Data Nilai Siswa</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="<?= site_url('nilai/import_nilai') ?>" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="file_csv">Pilih file</label>
                                                        <input type="file" class="form-control-file" id="file_csv" name="file_csv" accept=".csv" required>
                                                        <small class="form-text text-muted">File yang di upload berbentuk csv</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Upload CSV</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-list-nilai-siswa">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">No Induk</th>
                                <th class="text-center">Nama Siswa</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($siswa as $s): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= $s->no_induk ?></td>
                                    <td class="text-center"><?= $s->nama_siswa ?></td>
                                    <td class="text-center"><?= $s->gender ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('nilai/edit_siswa/'.$s->no_induk) ?>" class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i> Tambah
                                        </a>
                                          <a href="<?= base_url('nilai/all_nilai_siswa/'.$s->no_induk) ?>" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
