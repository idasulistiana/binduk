```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-calendar-check"></i>
                        Detail Absensi
                    </h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('dashboard') ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('data_absensi') ?>">
                                Rekap Kehadiran
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            Detail
                        </li>
                    </ol>
                </div>

            </div>

        </div>
    </section>

    <section class="content">

        <div class="container-fluid">

            <!-- Informasi Absensi -->
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Informasi Absensi
                    </h3>
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th width="250">Tanggal</th>
                            <td><?= date('d-m-Y', strtotime($absensi->tgl)); ?></td>
                        </tr>

                        <tr>
                            <th>Kelas</th>
                            <td><?= $absensi->nama_kelas; ?></td>
                        </tr>

                        <tr>
                            <th>Semester</th>
                            <td><?= $absensi->semester; ?></td>
                        </tr>

                        <tr>
                            <th>Tahun Ajaran</th>
                            <td><?= $absensi->tahun_ajaran; ?></td>
                        </tr>

                        <tr>
                            <th>Status Kelas</th>
                            <td>

                                <?php if($absensi->status_kelas == 1): ?>

                                    <span class="badge badge-success">
                                        Semua Siswa Hadir
                                    </span>

                                <?php else: ?>

                                    <span class="badge badge-danger">
                                        Ada Siswa Tidak Hadir
                                    </span>

                                <?php endif; ?>

                            </td>
                        </tr>

                    </table>

                </div>

            </div>


            <?php if($absensi->status_kelas == 1): ?>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Semua siswa hadir pada tanggal ini.
                </div>

            <?php else: ?>

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Daftar Siswa Tidak Hadir
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>No Induk</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php $no = 1; foreach ($detail as $d): ?>

                                <tr class="text-center">
                                    <td><?= $no++; ?></td>

                                    <td><?= $d->no_induk; ?></td>

                                    <td><?= $d->nama_siswa; ?></td>

                                    <td><?= $d->nama_kelas; ?></td>

                                    <td><?= date('d-m-Y', strtotime($d->tgl)); ?></td>

                                    <td>
                                        <?php
                                        switch ($d->keterangan) {
                                            case 'S':
                                                echo '<span class="badge badge-warning">Sakit</span>';
                                                break;
                                            case 'I':
                                                echo '<span class="badge badge-primary">Izin</span>';
                                                break;
                                            case 'A':
                                                echo '<span class="badge badge-danger">Alfa</span>';
                                                break;
                                            default:
                                                echo '-';
                                        }
                                        ?>
                                    </td>

                                    <td><?= !empty($d->catatan) ? $d->catatan : '-'; ?></td>
                                </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>

                </div>

            <?php endif; ?>

            <a href="<?= base_url('data_absensi_daily'); ?>" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i>
                Kembali
            </a>

        </div>

    </section>

</div>
```
