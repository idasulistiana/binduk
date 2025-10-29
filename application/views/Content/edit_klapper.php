<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Data Kelas Siswa</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Data Kelas</h3>
                </div>
                <form role="form" action="<?= base_url('ControllerKlapper/update_klapper/' . $klapper->no_induk) ?>" method="POST">
                    <div class="card-body">
                        <!-- No Induk -->
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>No Induk</label>
                                    <input type="text" class="form-control" name="no_induk" value="<?= $klapper->no_induk ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                      <!-- Nama Siswa -->
                                    <div class="form-group">
                                        <label>Nama Siswa</label>
                                        <input type="text" class="form-control" value="<?= $klapper->nama_siswa ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" class="form-control" 
                                        name="tgl_masuk" 
                                        value="<?= $klapper->tgl_masuk ?>">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control" 
                                        name="keterangan" 
                                        value="<?= $klapper->keterangan?>">
                                </div>
                            </div>
                        </div>        
                        <div class="row">
                            <div class="col-sm-12">
                                 <hr class="flex-grow-1 border-2 border-dark">
                            <span class="px-3 fw-bold text-secondary">Edit Data Kelas Siswa</span>
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
                        <div class="card-footer text-right" >
                            <a href="<?= base_url('ControllerKlapper') ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>
            </div>
        </div>
    </section>
</div>
