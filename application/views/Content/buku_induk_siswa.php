<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Daftar Siswa</h1>
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
                <div class="card-header">
                    <h3 class="card-title">Informasi Nilai Siswa</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-list-nilai-siswa">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">No Induk</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Nama Siswa</th>
                                <th class="text-center">Gender</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($siswa as $s): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center"><?= $s->nama_kelas ?></td>
                                    <td class="text-center"><?= $s->no_induk ?></td>
                                    <td class="text-center"><?= $s->nama_siswa ?></td>
                                    <td class="text-center"><?= $s->gender ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('bukuinduk/detail/'.$s->no_induk) ?>" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i> Lihat
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
