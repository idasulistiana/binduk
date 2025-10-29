<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Data Ekstrakurikuler</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Ekstrakurikuler</h3>
                </div>
                <form role="form" action="<?= base_url('ekskul/update_ekskul/' . $ekskul->id_ekskul) ?>" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Ekstrakurikuler</label>
                            <input type="text" class="form-control" 
                                   name="nama_ekskul" 
                                   value="<?= $ekskul->nama_ekskul ?>" 
                                   placeholder="Masukkan Nama Ektrakurikuler" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Penanggung Jawab</label>
                            <input type="text" class="form-control" 
                                   name="nama_pj" 
                                   value="<?= $ekskul->nama_pj ?>" 
                                   placeholder="Masukkan Nama Penanggung jawab" required>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?= base_url('ekskul') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
