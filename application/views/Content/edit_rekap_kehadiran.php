<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-edit"></i> Edit Data Kehadiran</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('ControllerRekapKehadiran/update_rekap') ?>" method="POST">
                        <input type="hidden" name="id_rekap" value="<?= $rekap->id_rekap ?>">

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Induk / Nama Siswa</label>
                                    <input type="text" name="no_induk" class="form-control" 
                                           value="<?= $rekap->no_induk . ' - ' . $rekap->nama_siswa; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Sakit</label>
                                    <input type="number" name="sakit" class="form-control" 
                                           value="<?= $rekap->sakit ?>" min="0" required>
                                </div>

                                <div class="form-group">
                                    <label>Izin</label>
                                    <input type="number" name="izin" class="form-control" 
                                           value="<?= $rekap->izin ?>" min="0" required>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanpa Keterangan</label>
                                    <input type="number" name="tanpa_keterangan" class="form-control" 
                                           value="<?= $rekap->tanpa_keterangan ?>" min="0" required>
                                </div>

                                <div class="form-group">
                                    <label>Tahun Ajaran</label>
                                    <input type="text" name="tahun_ajaran" class="form-control" 
                                           value="<?= $rekap->tahun_ajaran ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
