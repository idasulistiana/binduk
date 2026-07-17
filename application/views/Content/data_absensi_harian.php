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
                    <h1>
                        <i class="fas fa-calendar-check"></i> Data Absensi Siswa
                    </h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Absensi
                        </li>
                    </ol>
                </div>

            </div>


            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $this->session->flashdata('success'); ?>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $this->session->flashdata('error'); ?>
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>


        </div>
    </section>


    <section class="content">

        <div class="col-md-12">

            <div class="card">

                <div class="card-body">


                    <table id="tableAbsensi" class="table table-bordered table-striped">

                        <thead>

                            <tr class="text-center">

                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Semester</th>
                                <th>Tahun Ajaran</th>
                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php $no=1; foreach($absensi as $a): ?>

                            <tr class="text-center">


                                <td>
                                    <?= $no++; ?>
                                </td>


                                <td>
                                    <?= date('d-m-Y', strtotime($a->tgl)); ?>
                                </td>


                                <td>
                                    <?= $a->nama_kelas; ?>
                                </td>


                                <td>

                                    <?php if($a->status_kelas == 1): ?>

                                        <span class="badge badge-success">
                                            Semua Hadir
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-danger">
                                            Ada Tidak Hadir
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?= $a->semester; ?>
                                </td>


                                <td>
                                    <?= $a->tahun_ajaran; ?>
                                </td>


                                <td>


                                    <!-- Detail -->
                                    <a href="<?= base_url('ControllerDataAbsen/detail/'.$a->id_absensi) ?>"
                                       class="btn btn-info btn-sm">

                                        <i class="fa fa-eye"></i>

                                    </a>


                                    <!-- Edit -->
                                    <?php if($level_user != 2): ?>

                                    <a href="<?= base_url('ControllerDataAbsen/edit/'.$a->id_absensi) ?>"
                                       class="btn btn-success btn-sm">

                                        <i class="fa fa-edit"></i>

                                    </a>



                                    <!-- Delete -->

                                    <button 
                                        class="btn btn-danger btn-sm"
                                        data-toggle="modal"
                                        data-target="#deleteModal<?= $a->id_absensi ?>">

                                        <i class="fa fa-trash"></i>

                                    </button>



                                    <!-- Modal Delete -->

                                    <div class="modal fade"
                                         id="deleteModal<?= $a->id_absensi ?>">

                                        <div class="modal-dialog">

                                            <div class="modal-content">


                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Konfirmasi Hapus
                                                    </h5>

                                                    <button class="close"
                                                            data-dismiss="modal">
                                                        &times;
                                                    </button>

                                                </div>



                                                <div class="modal-body">

                                                    Apakah yakin ingin menghapus absensi kelas 
                                                    <strong>
                                                        <?= $a->nama_kelas ?>
                                                    </strong>
                                                    tanggal
                                                    <strong>
                                                        <?= date('d-m-Y', strtotime($a->tgl)); ?>
                                                    </strong> ?

                                                </div>



                                                <div class="modal-footer">

                                                    <button 
                                                        class="btn btn-secondary"
                                                        data-dismiss="modal">
                                                        Batal
                                                    </button>


                                                    <a href="<?= base_url('data_absensi/delete/'.$a->id_absensi) ?>"
                                                       class="btn btn-danger">

                                                        Hapus

                                                    </a>

                                                </div>


                                            </div>

                                        </div>

                                    </div>


                                    <?php endif; ?>

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
<script>
$(function(){

    $("#tableAbsensi").DataTable({
        "responsive": true,
        "autoWidth": false,
    });

});
</script>