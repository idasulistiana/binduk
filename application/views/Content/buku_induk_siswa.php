<!-- jQuery dulu, baru Popper.js, baru Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.min.js"></script>
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
<script>
$(document).ready(function() {

    // --- Siapkan kolom untuk DataTable ---
    var columns = [
        { "data": null }, // nomor urut
        { "data": "no_induk" },
        { "data": "nama_kelas" },
        { "data": "nama_siswa" },
        { "data": "gender" },
        {
            "data": null,
            "orderable": false,
            "render": function(data, type, row) {
                return `
                    <div class="text-center">
                        <a href="<?= base_url('bukuinduk/detail/') ?>${row.no_induk}" 
                           class="btn btn-primary btn-sm" title="Lihat">
                            <i class="fa fa-eye"></i> Lihat
                        </a>
                    </div>
                `;
            }
        }
    ];

    // --- Inisialisasi DataTable ---
    var table = $('.table-list-nilai-siswa').DataTable({
        "processing": true,
        "serverSide": false, // kalau mau pakai server-side bisa diset true
        "columns": columns,
        "columnDefs": [
            {
                "targets": 0,
                "render": function(data, type, row, meta) {
                    return meta.row + 1; // nomor urut otomatis
                }
            },
            { "className": "text-center", "targets": "_all" }
        ]
    });

    // --- Fungsi untuk load data via AJAX ---
    function loadSiswa(kelas_id = '') {
        $.ajax({
            url: "<?= site_url('ControllerNilaiSiswa/get_siswa') ?>", // endpoint controller kamu
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

    // --- Event: ketika filter kelas berubah ---
    $('#filterKelas').change(function() {
        let kelas_id = $(this).val();
        loadSiswa(kelas_id);
    });

    // --- Load data pertama kali ---
    loadSiswa();
});
</script>

