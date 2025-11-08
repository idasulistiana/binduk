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
                       <div style="margin-bottom: 10px;">
                            <label>Kelas:</label>
                            <select id="filterKelas" name="kelas" class="form-control" style="width: 150px; display: inline-block;">
                                <option value="">Semua Kelas</option>
                                <?php foreach ($kelas as $k) : ?>
                                    <option value="<?= $k->id_kelas; ?>"><?= $k->nama_kelas; ?></option>
                                <?php endforeach; ?>
                                <option value="lulus">Lulus</option>
                            </select>
                        </div>
                    <table class="table table-bordered table-striped table-list-nilai-siswa">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">No Induk</th>
                                <th class="text-center">Nama Siswa</th>
                                <th class="text-center">Status</th>
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
                                     <td class="text-center"><?= $s->nama_kelas ?></td>
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
<script>
$(document).ready(function() {

    // --- Siapkan kolom untuk DataTable ---
    var columns = [
        { "data": null }, // nomor urut
        { "data": "no_induk" },
        { "data": "nama_siswa" },
        {
            "data": "nama_kelas",
            "render": function(data, type, row) {
                // Jika status siswa 'Lulus', tampilkan 'Lulus'
                if (row.status && row.status.toLowerCase() === 'lulus') {
                    return 'Lulus';
                } else {
                    return row.nama_kelas || '-'; // kalau aktif, tampilkan kelas
                }
            }
        },
        { "data": "gender" },
        <?php if ($level_user != 2): ?>
        {
            "data": null,
            "orderable": false,
            "render": function(data, type, row) {
                return `
                    <div class="text-center">
                        <a href="<?= base_url('nilai/edit_siswa/') ?>${row.no_induk}" 
                        class="btn btn-primary btn-sm" title="Tambah Nilai">
                            <i class="fa fa-plus"></i> Tambah
                        </a>
                        <a href="<?= base_url('nilai/all_nilai_siswa/') ?>${row.no_induk}" 
                        class="btn btn-warning btn-sm text-white" title="Edit Nilai">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                `;
            }
        }
        <?php endif; ?>
    ];

    // --- Inisialisasi DataTable ---
    var table = $('.table-list-nilai-siswa').DataTable({
        "processing": true,
        "columns": columns,
        "columnDefs": [
            {
                "targets": 0,
                "render": function(data, type, row, meta) {
                    return meta.row + 1; // nomor urut
                }
            },
            { "className": "text-center", "targets": "_all" }
        ]
    });

    // --- Fungsi untuk load data via AJAX ---
    function loadSiswa(kelas_id = '') {
        $.ajax({
            url: "<?= site_url('ControllerNilaiSiswa/get_siswa') ?>",
            type: "POST",
            data: { kelas: kelas_id },
            dataType: "json",
            beforeSend: function() {
                table.clear().draw();
            },
            success: function(response) {
                let data = response.data || [];
                table.clear();
                if (data.length > 0) {
                    table.rows.add(data);
                }
                table.draw();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // --- Event ketika filter kelas diganti ---
    $('#filterKelas').change(function() {
        let kelas_id = $(this).val();
        loadSiswa(kelas_id);
    });

    // --- Load data pertama kali ---
    loadSiswa();
});
</script>
