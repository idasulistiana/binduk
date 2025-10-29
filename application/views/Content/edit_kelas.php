<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Data Kelas</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Kelas</h3>
                </div>
                <form role="form" action="<?= base_url('kelas/update_kelas/' . $kelas->id_kelas) ?>" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Kelas</label>
                            <input type="text" class="form-control" 
                                   name="nama_kelas" 
                                   value="<?= $kelas->nama_kelas ?>" 
                                   placeholder="Masukkan Nama Kelas" required>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
