<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Data Mata Pelajaran</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Mata Pelajaran</h3>
                </div>
                <form role="form" action="<?= base_url('mapel/update_mapel/' . $mapel->id_mapel) ?>" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" 
                                   name="nama_mapel" 
                                   value="<?= $mapel->nama_mapel ?>" 
                                   placeholder="Masukkan Nama Mata Pelajaran" required>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('mapel') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
