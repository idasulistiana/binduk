<?php if($absensi->status_kelas==0): ?>

<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            Daftar Siswa Tidak Hadir
        </h3>

        <div class="card-tools">

            <button
                type="button"
                class="btn btn-primary btn-sm"
                data-toggle="modal"
                data-target="#modalTambah">

                <i class="fa fa-plus"></i>
                Tambah Siswa

            </button>

        </div>

    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>

                <tr class="text-center">

                    <th width="5%">No</th>

                    <th>Nama Siswa</th>

                    <th width="15%">Keterangan</th>

                    <th>Catatan</th>

                    <th width="15%">Action</th>

                </tr>

            </thead>

            <tbody>

            <?php $no=1; foreach($detail as $d): ?>

                <tr>

                    <td class="text-center">
                        <?= $no++; ?>
                    </td>

                    <td>

                        <?= $d->nama_siswa; ?>

                    </td>

                    <td class="text-center">

                        <?php

                        switch($d->keterangan){

                            case 'S':
                                echo '<span class="badge badge-warning">Sakit</span>';
                                break;

                            case 'I':
                                echo '<span class="badge badge-primary">Izin</span>';
                                break;

                            case 'A':
                                echo '<span class="badge badge-danger">Alfa</span>';
                                break;

                        }

                        ?>

                    </td>

                    <td>

                        <?= $d->catatan ?: '-'; ?>

                    </td>

                    <td class="text-center">

                        <button
                            class="btn btn-success btn-sm"
                            data-toggle="modal"
                            data-target="#edit<?= $d->no_induk ?>">

                            <i class="fa fa-edit"></i>

                        </button>

                        <button
                            class="btn btn-danger btn-sm"
                            data-toggle="modal"
                            data-target="#hapus<?= $d->no_induk ?>">

                            <i class="fa fa-trash"></i>

                        </button>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>
<div class="modal fade" id="modalTambah">

    <div class="modal-dialog">

        <form action="<?= base_url('ControllerDataAbsen/tambah_detail') ?>" method="POST">

            <input type="hidden"
                   name="id_absensi"
                   value="<?= $absensi->id_absensi ?>">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Siswa Tidak Hadir
                    </h5>

                    <button class="close" data-dismiss="modal">&times;</button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Nama Siswa</label>

                        <select class="form-control" name="no_induk">

                            <?php foreach($siswa as $s): ?>

                                <option value="<?= $s->no_induk ?>">

                                    <?= $s->nama_siswa ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Keterangan</label>

                        <select class="form-control" name="keterangan">

                            <option value="S">Sakit</option>

                            <option value="I">Izin</option>

                            <option value="A">Alfa</option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Catatan</label>

                        <textarea
                            class="form-control"
                            name="catatan"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-success">

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
</div>

<?php endif; ?> 