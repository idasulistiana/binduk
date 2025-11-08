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
                    <h1><i class="fas fa-user-check"></i> Data Kehadiran Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Kehadiran Siswa</li>
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
                            <h3 class="card-title p-3">Informasi Kehadiran</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <?php if ($level_user != 2): ?>
                                    <li class="nav-item">
                                        <button class="btn btn-success nav-link text-white" href="#tab_1" data-toggle="tab" style="margin-right: 10px">
                                            <i class="fas fa-users"></i> Daftar Siswa
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-primary nav-link text-white" href="#tab_2" data-toggle="tab" style="margin-right: 10px" id="btnTambahRekap">
                                            <i class="fas fa-plus"></i> Tambah Kehadiran
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-warning nav-link text-white" data-toggle="modal" data-target="#importCsvModal" style="margin-right: 10px">
                                            <i class="fas fa-plus"></i> Import Data
                                        </button>
                                    
                                        <!-- Modal Upload CSV -->
                                        <div class="modal fade" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="importCsvModalLabel">Import Data Kehadiran Siswa</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="<?= site_url('kehadiran/import_rekap_kehadiran') ?>" method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="file_csv">Pilih file CSV</label>
                                                                <input type="file" class="form-control-file" id="file_csv" name="file_csv" accept=".csv" required>
                                                                <small class="form-text text-muted">File CSV harus memiliki no_induk, kelas, semester, sakit, izin, tanpa_keterangan, tahun_ajaran</small>
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
                                <?php endif; ?>
                                <li class="nav-item">
                                
                                    <button class="btn btn-dark nav-link text-white" data-toggle="modal" data-target="#downloadModal" style="margin-right: 10px">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                

                                    <!-- Modal Download CSV -->
                                    <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="downloadModalLabel">Pilih Data yang Akan Didownload</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="downloadForm" action="<?= base_url('kehadiran/download_rekap_kehadiran'); ?>" method="post">
                                                    <div class="modal-body">

                                                    <!-- Pilih Kelas -->
                                                        <div class="form-group">
                                                            <label><strong>Pilih Kelas:</strong></label>

                                                            <!-- Checkbox Check All -->
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox" id="checkAllKelas">
                                                                <label class="form-check-label font-weight-bold text-primary" for="checkAllKelas">Checklist All</label>
                                                            </div>

                                                            <!-- Daftar kelas 2 kolom -->
                                                            <div class="row">
                                                                <?php foreach($kelas as $index => $k): ?>
                                                                    <div class="col-6">
                                                                        <div class="form-check ml-3">
                                                                            <input class="form-check-input kelas-checkbox" type="checkbox" name="kelas[]" value="<?=  $k->id_kelas ?>" id="kelas_<?= $k->id_kelas ?>">
                                                                            <label class="form-check-label" for="kelas_<?= $k->id_kelas ?>">Kelas <?= htmlspecialchars($k->nama_kelas) ?></label>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <!-- Pilih Semester -->
                                                        <div class="form-group">
                                                            <label><strong>Pilih Semester:</strong></label>

                                                            <!-- Checkbox Semester -->
                                                            <div class="form-check ml-3">
                                                                <input class="form-check-input semester-checkbox" type="checkbox" name="semester[]" value="1" id="semester_ganjil">
                                                                <label class="form-check-label" for="semester_ganjil">Ganjil</label>
                                                            </div>
                                                            <div class="form-check ml-3">
                                                                <input class="form-check-input semester-checkbox" type="checkbox" name="semester[]" value="2" id="semester_genap">
                                                                <label class="form-check-label" for="semester_genap">Genap</label>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Download</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content">

                                <!-- TAB 1: DAFTAR KEHADIRAN -->
                                <div class="tab-pane active" id="tab_1">
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped table-rekapkehadiran-siswa">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">No Induk</th>
                                                    <th class="text-center">Nama Siswa</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Semester</th>
                                                    <th class="text-center">Sakit</th>
                                                    <th class="text-center">Izin</th>
                                                    <th class="text-center">Tanpa Keterangan</th>
                                                    <th class="text-center">Tahun Ajaran</th>
                                                    <?php if ($level_user != 2): ?>
                                                        <th class="text-center">Aksi</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; foreach($rekap as $r): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center"><?= $r->no_induk ?></td>
                                                    <td class="text-center"><?= $r->nama_siswa ?></td>
                                                    <td class="text-center"><?= $r->nama_kelas ?></td>
                                                    <td class="text-center">
                                                        <?php 
                                                            if ($r->semester == 1) {
                                                                echo "Ganjil";
                                                            } elseif ($r->semester == 2) {
                                                                echo "Genap";
                                                            } else {
                                                                echo "-";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="text-center"><?= $r->sakit ?></td>
                                                    <td class="text-center"><?= $r->izin ?></td>
                                                    <td class="text-center"><?= $r->tanpa_keterangan ?></td>
                                                    <td class="text-center"><?= $r->tahun_ajaran ?></td>
                                                    <?php if ($level_user != 2): ?>
                                                        <td class="text-center">
                                                            <a href="<?= base_url('kehadiran/edit_siswa/'.$r->id_rekap) ?>" class="btn btn-success btn-sm">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <?php if ($level_user != 2): ?>
                                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $r->id_rekap ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>

                                                <!-- Modal Delete -->
                                                <div class="modal fade" id="deleteModal<?= $r->id_rekap ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $r->id_rekap ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus data kehadiran siswa dengan No Induk <strong><?= $r->no_induk ?></strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <a href="<?= base_url('kehadiran/delete_rekap/'.$r->id_rekap) ?>" class="btn btn-danger">Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- TAB 2: TAMBAH KEHADIRAN -->
                                <div class="tab-pane" id="tab_2">
                                    <form action="<?= base_url('kehadiran/add_rekap') ?>" method="POST">
                                    <div class="row">
                                        <!-- BARIS 1 -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No Induk</label>
                                                    <input type="text" id="no_induk_siswa" name="no_induk" class="form-control" placeholder="Ketik No Induk atau Nama Siswa" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kelas</label>
                                                    <select name="id_kelas" class="form-control" required>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        <?php foreach ($kelas as $k) : ?>
                                                            <option value="<?= $k->id_kelas; ?>"><?= $k->nama_kelas; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Semester</label>
                                                    <select name="semester" class="form-control" required>
                                                        <option value="1">Semester 1 (Ganjil)</option>
                                                        <option value="2">Semester 2 (Genap)</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tahun Ajaran</label>
                                                    <input type="text" id="tahun_ajaran" name="tahun_ajaran" class="form-control" placeholder="Ketik Tahun Ajaran (contoh: 2024/2025)" required>
                                                </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Sakit</label>
                                                <input type="number" name="sakit" class="form-control" placeholder="Jumlah absen sakit" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Izin</label>
                                                <input type="number" name="izin" class="form-control" placeholder="Jumlah absen izin" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Tanpa Keterangan</label>
                                                <input type="number" name="tanpa_keterangan" class="form-control" placeholder="Jumlah absen tanpa keterangan" required>
                                            </div>
                                        </div>
                                        </div>
                                            <div class="card-footer text-end mt-3 text-right">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="<?= base_url('kehadiran') ?>" class="btn btn-secondary">Cancel</a>
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

<script>
    // Fungsi Check All untuk kelas
    document.getElementById('checkAllKelas').addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.kelas-checkbox').forEach(function(cb) {
            cb.checked = checked;
        });
    });
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const autoAdd = urlParams.get('auto_add');

    // Jika datang dari halaman Edit Kehadiran
    if (autoAdd === '1') {
        // Jika pakai tab dengan id #tab_2, aktifkan dulu tab-nya
        if (window.location.hash === '#tab_2') {
            $('a[href="#tab_2"]').tab('show');
        }

        // Klik tombol tambah siswa setelah tab tampil
        setTimeout(function() {
            $('#btnTambahRekap').trigger('click');
        }, 500);
    }
});

</script>