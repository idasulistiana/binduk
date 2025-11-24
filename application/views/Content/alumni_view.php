<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$level_user = $CI->session->userdata('level_user');
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-graduate"></i> Data Alumni</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Alumni</li>
                    </ol>
                </div>
            </div>

              <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('failed')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('failed'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="content">          
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="tableAlumni" class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>NISN</th>
                                <th>No Induk</th>
                                <th>Kelas Terakhir</th>
                                <th>Nama Alumni</th>
                                <th>Gender</th>
                                <th>TTL</th>
                                <th>Agama</th>
                                <th>Alamat</th>
                                <th>Nama Ayah</th>
                                <th>Nama Ibu</th>
                                <th>Tahun Lulus</th>
                                <?php if ($level_user != 2): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($alumni as $a): ?>
                            <tr class="text-center">
                                <td><?= $no++; ?></td>
                                <td><?= $a->nisn; ?></td>
                                <td><?= $a->no_induk; ?></td>
                                <td><?= $a->nama_kelas; ?></td>
                                <td><?= $a->nama_siswa; ?></td>
                                <td><?= $a->gender; ?></td>
                                <td><?= $a->tempat_lahir; ?>, <?= date('d-m-Y', strtotime($a->tgl_lahir)); ?></td>
                                <td><?= $a->agama; ?></td>
                                <td><?= $a->alamat; ?></td>
                                <td><?= $a->nama_ayah; ?></td>
                                <td><?= $a->nama_ibu; ?></td>
                                <td><?= !empty($a->tahun_lulus) ? $a->tahun_lulus : '-'; ?></td>
                                <?php if ($level_user != 2): ?>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <a href="<?= base_url('alumni/update/'.$a->no_induk) ?>" class="btn btn-success btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                       
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $a->no_induk ?>">
                                             <i class="fa fa-trash"></i>
                                        </button>
                                            <!-- Modal Konfirmasi Delete -->
                                            <!-- Modal Delete -->
                                                    <div class="modal fade" id="deleteModal<?= $a->no_induk ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $a->no_induk?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menghapus siswa <strong><?= $a->nama_siswa ?></strong>?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <a href="<?= base_url('alumni/delete/'.$a->no_induk) ?>" class="btn btn-danger">Hapus</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                    </td>

                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

