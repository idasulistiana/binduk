<!-- jQuery dulu, baru Popper.js, baru Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
    // Ambil session dari CodeIgniter
    $CI =& get_instance();
    $level_user = $CI->session->userdata('level_user');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-book"></i> Data Identitas Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pembantu Induk</li>
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

        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="col-md-12" >
            <div class="row">
                <div class="col-12 padding-left20">
                    <!-- Custom Tabs -->
                    <div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Informasi Siswa</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <?php $level_user = $this->session->userdata('level_user'); ?>
                                <?php if ($level_user != 2): ?>
                                    <li class="nav-item">
                                        <button class="btn btn-success nav-link text-white" href="#tab_1" data-toggle="tab" style="margin-right: 10px">
                                            <i class="fas fa-users"></i> Daftar Siswa
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="btn btn-primary nav-link text-white" href="#tab_2" data-toggle="tab" style="margin-right: 10px">
                                            <i class="fas fa-plus"></i> Tambah Siswa
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
                                                        <h5 class="modal-title" id="importCsvModalLabel">Import Data Siswa</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="<?= site_url('siswa/import_siswa') ?>" method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="file_csv">Pilih file CSV</label>
                                                                <input type="file" class="form-control-file" id="file_csv" name="file_csv" accept=".csv" required>
                                                                <small class="form-text text-muted">File CSV harus memiliki header: NISN, Nama Siswa, No Induk, Gender, Agama, Tempat Lahir, Tanggal Lahir, Nama Ibu, Alamat, Nama Ayah, Tanggal Diterima, Sekolah Asal</small>
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
                                    <a href="<?= site_url('siswa/download_siswa') ?>" class="btn btn-dark nav-link text-white">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane <?php if (form_error('nama') == '') {
                                                            echo 'active';
                                                        } ?>" id="tab_1">
                                    <div class="card-body">   
                                    <div style="margin-bottom: 10px;">
                                        <label>Kelas:</label>
                                        <select id="filterKelas" name="kelas" class="form-control" style="width: 150px; display: inline-block;">
                                            <option value="">Semua Kelas</option>
                                            <?php foreach ($kelas as $k) : ?>
                                                <option value="<?= $k->id_kelas; ?>"><?= $k->nama_kelas; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- Loading Spinner -->
                                        <table id="tableSiswa" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">NISN</th>
                                                    <th class="text-center">No Induk</th>
                                                    <th class="text-center">Kelas</th>
                                                    <th class="text-center">Nama Siswa</th>
                                                    <th class="text-center">Gender</th>
                                                    <th class="text-center">TTL</th>
                                                    <th class="text-center">Agama</th>
                                                    <th class="text-center">Alamat</th>
                                                    <th class="text-center">Nama Ayah</th>
                                                    <th class="text-center">Nama Ibu</th>
                                                    <th class="text-center">Diterima</th>

                                                    <!-- Sembunyikan kolom Action jika level_user = 2 -->
                                                    <?php if ($level_user != 2): ?>
                                                        <th class="text-center">Action</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                foreach ($siswa as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center"><?= $no++ ?></td>
                                                        <td class="text-center"><strong><?= !empty($value->nisn) ? $value->nisn : '-' ?></strong></td>
                                                        <td class="text-center"><?= !empty($value->no_induk) ? $value->no_induk : '-' ?></td>
                                                        <td class="text-center"><strong><?= !empty($value->nama_kelas) ?  $value->nama_kelas : '-' ?></strong></td>
                                                        <td class="text-center"><strong><?= !empty($value->nama_siswa) ? $value->nama_siswa : '-' ?></strong></td>
                                                        <td class="text-center"><?= !empty($value->gender) ? $value->gender : '-' ?></td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning"></span>
                                                            <?= !empty($value->tempat_lahir) ? $value->tempat_lahir : '-' ?><br>
                                                            <?= !empty($value->tgl_lahir) ? date('d-m-Y', strtotime($value->tgl_lahir)) : '-' ?>
                                                        </td>
                                                        <td class="text-center"><?= !empty($value->agama) ? $value->agama : '-' ?></td>
                                                        <td class="text-center"><?= !empty($value->alamat) ? $value->alamat : '-' ?></td>
                                                        <td class="text-center"><?= !empty($value->nama_ayah) ? $value->nama_ayah : '-' ?></td>
                                                        <td class="text-center"><?= !empty($value->nama_ibu) ? $value->nama_ibu : '-' ?></td>
                                                        <td class="text-center"><?= !empty($value->tgl_diterima) ? date('d-m-Y', strtotime($value->tgl_diterima)) : '-' ?></td>


                                                        <!-- Hanya tampilkan tombol aksi jika level_user bukan 2 -->
                                                        <?php if ($level_user != 2): ?>
                                                            <td class="text-center">
                                                                <!-- Tombol Delete -->
                                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $value->nisn ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                                <!-- Tombol Edit -->
                                                                <a href="<?= base_url('siswa/update_siswa/' . $value->nisn) ?>" class="btn btn-success btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>

                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                
                            <!-- Spinner di tengah tabel -->
                            <div id="tableLoadingSpinner" 
                                style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);
                                        text-align:center; z-index:10;">
                                <div class="spinner-border text-primary" style="width:3rem; height:3rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2 mb-0 text-primary font-weight-bold">Memuat data...</p>
                            </div>

                                <!-- Modal Konfirmasi Delete -->
                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus data siswa <strong id="namaSiswaHapus"></strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <a href="#" id="btnDeleteConfirm" class="btn btn-danger">Hapus</a>
                                </div>
                                </div>
                            </div>
                            </div>
                            <!-- /.tab-pane -->
                                <div class="tab-pane <?php if (form_error('nama') != '') {
                                                            echo 'active';
                                                        } ?>" id="tab_2">
                                    <form role="form" action="<?= base_url('siswa/add_siswa') ?>" method="POST">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>NISN Siswa</label>
                                                    <input type="number" name="nisn" value="<?= set_value('nisn') ?>" class="form-control" placeholder="Masukkan NISN" required>
                                                    <?= form_error('nisn', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Nama Siswa</label>
                                                    <input type="text" name="nama" value="<?= set_value('nama') ?>" class="form-control" placeholder="Masukkan Nama Siswa" required>
                                                    <?= form_error('nama', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>No Induk</label>
                                                    <input type="number" name="no_induk" value="<?= set_value('no_induk') ?>" class="form-control" placeholder="Masukkan Nomor Induk" required>
                                                    <?= form_error('no_induk', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-select form-control" required>
                                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                                        <option value="Laki-Laki" <?= set_value('gender') == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                                                        <option value="Perempuan" <?= set_value('gender') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                                    </select>
                                                    <?= form_error('gender', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Agama</label>
                                                    <select name="agama" class="form-select form-control" required>
                                                        <option value="">-- Pilih Agama --</option>
                                                        <option value="Islam" <?= set_value('agama') == 'Islam' ? 'selected' : '' ?>>Islam</option>
                                                        <option value="Kristen Protestan" <?= set_value('agama') == 'Kristen Protestan' ? 'selected' : '' ?>>Kristen Protestan</option>
                                                        <option value="Katolik" <?= set_value('agama') == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                                        <option value="Hindu" <?= set_value('agama') == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                                        <option value="Buddha" <?= set_value('agama') == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                                        <option value="Konghucu" <?= set_value('agama') == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
                                                    </select>
                                                    <?= form_error('agama', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Tempat Lahir</label>
                                                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?= set_value('tempat_lahir') ?>" class="form-control" placeholder="Masukkan Tempat Lahir" required>
                                                    <?= form_error('tempat_lahir', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Tanggal Diterima</label>
                                                    <input type="date" name="tgl_diterima" value="<?= set_value('tgl_diterima') ?>" class="form-control">
                                                    <?= form_error('tgl_diterima', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6"> 
                                                <div class="form-group">
                                                <label>Kelas</label>
                                                <select name="kelas" class="form-control" required> <option value="">-- Pilih Kelas --</option> 
                                                    <?php foreach ($kelas as $k) : ?> <option value="<?= $k->id_kelas; ?>"><?= $k->nama_kelas; ?></option>
                                                        <?php endforeach; ?> 
                                                </select> </div> </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Tanggal Lahir</label>
                                                    <input type="date" name="tgl_lahir" value="<?= set_value('tgl_lahir') ?>" class="form-control" required>
                                                    <?= form_error('tgl_lahir', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Nama Ibu</label>
                                                    <input type="text" name="nama_ibu" value="<?= set_value('nama_ibu') ?>" class="form-control" placeholder="Masukkan Nama Ibu" required>
                                                    <?= form_error('nama_ibu', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Nama Ayah</label>
                                                    <input type="text" name="nama_ayah" value="<?= set_value('nama_ayah') ?>" class="form-control" placeholder="Masukkan Nama Ayah" required>
                                                    <?= form_error('nama_ayah', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                              <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-select form-control" required>
                                                        <option value="">-- Status --</option>
                                                        <option value="aktif" <?= set_value('status') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                        <option value="lulus" <?= set_value('status') == 'lulus' ? 'selected' : '' ?>>Lulus</option>
                                                    </select>
                                                    <?= form_error('status', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Alamat</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan Alamat"><?= set_value('alamat') ?></textarea>
                                                    <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="card-footer text-end mt-3">
                                            <div class="d-flex justify-content-end">
                                                <a href="<?= base_url('siswa') ?>" class="btn btn-secondary me-2" onclick="window.history.back();" style="margin-right: 5px">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <!-- /.tab-pane -->
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
</div>
 <script>
 // Ambil nilai level_user dari PHP
 
$(document).ready(function() {

    // --- Siapkan kolom dasar (tanpa kolom aksi) ---
    var columns = [
        { "data": null }, // nomor urut
        { "data": "nisn" },
        { "data": "no_induk" },
        { "data": "nama_kelas" },
        { "data": "nama_siswa" },
        { "data": "gender" },
        { 
            "data": null,
            "render": function(data, type, row) {
                let tempat = row.tempat_lahir ? row.tempat_lahir : '-';
                let tanggal = row.tgl_lahir 
                    ? new Date(row.tgl_lahir).toLocaleDateString('id-ID') 
                    : '-';
                return tempat + ', ' + tanggal;
            }
        },
        { "data": "agama" },
        { "data": "alamat" },
        { "data": "nama_ayah" },
        { "data": "nama_ibu" },
        { 
            "data": "tgl_diterima",
            "render": function(data) {
                return data ? new Date(data).toLocaleDateString('id-ID') : '-';
            }
        }

        <?php if ($level_user == 1): ?>, // tambahkan koma karena ini bagian JS array
        {
            "data": null,
            "orderable": false,
            "render": function(data, type, row) {
                return `
                    <div class="text-center">
                        <a href="<?= base_url('siswa/update_siswa/') ?>${row.nisn}" 
                           class="btn btn-success btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm delete-siswa" 
                                data-nisn="${row.nisn}" 
                                data-nama="${row.nama_siswa}"
                                title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;
            }
        }
        <?php endif; ?>
    ];

    // --- Inisialisasi DataTable ---
    var table = $('#tableSiswa').DataTable({
        "processing": true,
        "columns": columns,
        "columnDefs": [
            {
                "targets": 0,
                "render": function(data, type, row, meta){
                    return meta.row + 1; // nomor urut
                }
            }
        ]
    });

    // --- Fungsi load data via AJAX ---
    function loadSiswa(kelas_id = '') {
        $('#tableLoadingSpinner').show();

        $.ajax({
            url: "<?= site_url('ControllerDataMaster/get_siswa') ?>",
            type: "POST",
            data: { kelas: kelas_id },
            dataType: "json",
            success: function(response) {
                let data = response.data || [];
                table.clear();

                if (data.length > 0) {
                    table.rows.add(data);
                }

                table.draw();
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", error);
            },
            complete: function() {
                $('#tableLoadingSpinner').hide();
            }
        });
    }

    // --- Event filter kelas ---
    $('#filterKelas').change(function() {
        let kelas_id = $(this).val();
        loadSiswa(kelas_id);
    });

    // --- Load data pertama kali ---
    loadSiswa();
});
</script>