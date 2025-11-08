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
                <div class="alert alert-success alert-dismissible fade show"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
        </div>
    </section>

    <div class="col-md-12" style="margin-left:10px">
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
                                    <a href="<?= base_url('alumni/update/'.$a->nisn) ?>" class="btn btn-success btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="<?= site_url('alumni/delete/'.$a->nisn); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data alumni ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

