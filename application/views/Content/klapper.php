<!-- jQuery dulu, Popper.js, Bootstrap JS -->
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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-book"></i> Data Kelas Siswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Data Kelas Siswa</li>
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

    <div class="col-md-12">
        <div class="row">
            <div class="col-12 padding-left20">
                <div class="card">
                    <div class="card-header d-flex p-0">
                        <h3 class="card-title p-3">Informasi Riwayat Kelas Siswa</h3>
                           <ul class="nav nav-pills ml-auto p-2">
                            <?php if ($level_user != 2): ?>
                                <li class="nav-item">
                                    <button class="btn btn-success nav-link text-white" href="#tab_1" data-toggle="tab" style="margin-right: 10px">
                                        <i class="fas fa-users"></i> Daftar Kelas Siswa
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="btn btn-primary nav-link text-white" href="#tab_2" data-toggle="tab" style="margin-right: 10px">
                                        <i class="fas fa-plus"></i> Tambah Riwayat Kelas
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
                                                    <h5 class="modal-title" id="importCsvModalLabel">Import Data Kelas Siswa</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="<?= site_url('ControllerKlapper/import_kelas') ?>" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="file_csv">Pilih file CSV</label>
                                                            <input type="file" class="form-control-file" id="file_csv" name="file_csv" accept=".csv" required>
                                                            <small class="form-text text-muted">File CSV harus memiliki header: kelas 1, kelas 2, kelas 4, kelas 5,  kelas 6, Keterangan</small>
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
                                <a href="<?= site_url('ControllerKlapper/download_kelas') ?>" class="btn btn-dark nav-link text-white">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </li>
                            </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Tab Daftar Klapper -->
                            <div class="tab-pane active" id="tab_1">
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <!-- Baris pertama: judul umum -->
                                            <tr>
                                                <th rowspan="2" class="text-center">No</th>
                                                <th rowspan="2" class="text-center">No Induk</th>
                                                <th rowspan="2" class="text-center">Nama Siswa</th>
                                                <th rowspan="2" class="text-center">Gender</th>
                                                <th colspan="6" class="text-center">Tahun Kelas</th>
                                                <th rowspan="2" class="text-center">Keterangan</th>
                                                <?php if ($level_user != 2): ?>
                                                    <th rowspan="2" class="text-center">Action</th>
                                                <?php endif; ?>
                                            </tr>
                                            <!-- Baris kedua: kelas 1-6 -->
                                            <tr>
                                                <th class="text-center">1</th>
                                                <th class="text-center">2</th>
                                                <th class="text-center">3</th>
                                                <th class="text-center">4</th>
                                                <th class="text-center">5</th>
                                                <th class="text-center border-right">6</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $no=1; foreach($klapper as $k): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td class="text-center"><?= $k->no_induk ?></td>
                                                <td class="text-center"><?= $k->nama_siswa ?></td>
                                                <td class="text-center"><?= $k->gender ?></td>
                                                <?php 
                                                    $kelas_cols = ['kelas_1','kelas_2','kelas_3','kelas_4','kelas_5','kelas_6'];
                                                    foreach($kelas_cols as $col) {
                                                        echo '<td class="text-center">';
                                                        echo !empty($k->$col) ? $k->$col : '-';
                                                        echo '</td>';
                                                    }   
                                                ?>
                                                <td class="text-center"><?= $k->keterangan ?></td>
                                                <?php if ($level_user != 2): ?>
                                                    <td class="text-center">
                                                        <a href="<?= base_url('riwayatkelas/update_klapper/'.$k->no_induk) ?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?= $k->no_induk ?>"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="deleteModal<?= $k->no_induk ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $k->no_induk ?>" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus data Klapper <strong><?= $k->nama_siswa ?></strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <a href="<?= base_url('ControllerKlapper/delete_klapper/'.$k->no_induk) ?>" class="btn btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Tambah Klapper -->
                            <div class="tab-pane" id="tab_2">
                                <form action="<?= base_url('ControllerKlapper/add_klapper') ?>" method="POST">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>No Induk Siswa</label>
                                                <input type="number" name="no_induk" id="no_induk" max="9999" oninput="if(this.value.length > 4) this.value = this.value.slice(0,4);" class="form-control" placeholder="Ketik No Induk atau Nama Siswa" require>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Keterangan</label>
                                                <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row">
                                        <div class="col-sm-12">
                                            <hr class="flex-grow-1 border-2 border-dark">
                                        <span class="px-3 fw-bold text-secondary">Tambah Data Kelas Siswa</span>
                                        <hr class="flex-grow-1 border-2 border-dark">
                                            <div class="form-group">
                                                <div id="kelasContainerUpdate">
                                                    <?php for ($i=1; $i<=6; $i++): 
                                                        $field = "kelas_" . $i; 
                                                        $value = !empty($klapper->$field) ? $klapper->$field : ""; 
                                                        // buka row baru tiap kelipatan 3
                                                        if ($i % 3 == 1) echo '<div class="row mb-3">';
                                                    ?>
                                                        <div class="col-md-4">
                                                            <label>Kelas <?= $i ?></label>
                                                            <div class="input-group">
                                                                <input type="text" 
                                                                    name="kelas<?= $i ?>" 
                                                                    class="form-control tahunInput" 
                                                                    value="<?= $value ?>" 
                                                                    placeholder="Masukkan Tahun Ajar">
                                                            </div>
                                                        </div>
                                                    <?php 
                                                        // tutup row tiap 3 input atau di akhir
                                                        if ($i % 3 == 0 || $i == 6) echo '</div>';
                                                    endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-end mt-3 text-right">
                                        <a href="<?= base_url('ControllerKlapper') ?>" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
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

