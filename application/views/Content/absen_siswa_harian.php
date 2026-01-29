<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .brand-link .brand-image{
        max-height: 80px !important;
    }
    .content-wrapper{
        margin-left:0px !important;
    }
    .brand-link, .brand-link:hover {
        color: #111111 !important;
        font-weight : 500 !important;
    }
    .ml-35 {
        margin-left: 35px;
    }

     @media (max-width: 768px) {
        .ml-35 {
            margin-left: 0;
        }
    }
    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        width: 100% !important;
        height: 38px;
    }
    .select2-container .select2-selection--single{
        height: inherit !important;
    }
    .mt-desktop-50 {
            margin-top: 0;
        }

        @media (min-width: 992px) { /* ukuran desktop (Bootstrap lg) */
            .mt-desktop-50 {
                margin-top: 50px;
            }
    }
</style>
<head>
 <link rel="preload" href="<?= base_url('asset/AdminLTE/dist/css/adminlte.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/select2/css/select2.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <!-- ================= Stylesheet tambahan ================= -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/summernote/summernote-bs4.css') ?>">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body style="background-color:#f4f6f9">
    <div class="content-wrapper">
        <section class="content-header" style="box-shadow: 2px 2px 5px #00000040">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <a href="#" class="brand-link">
                            <img id="logo-sekolah"
                                src="<?= base_url('asset/AdminLTE/') ?>dist/img/logo_sekolah.png"
                                alt="sekolah Logo"
                                class="brand-image img-circle elevation-3">

                            <span class="brand-text">
                                SDN Tegal Alur 04<br>
                                Absen Harian Siswa-Siswi
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
    <div class="d-flex justify-content-center">
        <div class="w-100 px-3" style="max-width: 1600px;">

            <div id="notifArea" class="mt-2">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('failed')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('failed'); ?>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row g-3">
                <!-- Kiri -->
                <div class="col-12 col-lg-8 mt-3">
                    <label class="d-block d-md-inline-flex align-items-center font-medium">
                        Tanggal :
                        <input id="tanggal"
                            type="date"
                            class="ml-md-2 mt-2 mt-md-0 text-center rounded-xl border-slate-300 pointer-events-none"
                            readonly>
                    </label>
                    <div class="bg-white rounded-2xl shadow p-4 mb-4">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label>Pilih Kelas</label>
                                <select id="kelasSelect" class="form-control"></select>
                            </div>

                            <div class="col-12 mb-2">
                                <label>Nama Siswa</label>
                                <select id="namaInput" class="form-control select2" disabled>
                                    <option value="">-- Pilih Siswa --</option>
                                </select>
                            </div>

                            <div class="col-12 mb-2">
                                <label>Keterangan</label>
                                <select class="form-control" id="keteranganSelect" disabled>
                                    <option value="">-- Pilih Keterangan --</option>
                                    <option value="1">Sakit</option>
                                    <option value="2">Izin</option>
                                    <option value="3">Alfa</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-lg-auto ml-lg-auto text-right">
                                <button id="tambahBtn" class="btn btn-primary w-100">
                                    Tambah
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Kanan -->
                <div class="col-12 col-lg-4 mt-desktop-50">
                    <div class="bg-white rounded-2xl shadow p-4">
                        <h2 class="h4 fw-semibold mb-3">
                            Daftar Siswa Tidak Masuk
                        </h2>
                        <ul id="listAbsen" class="list-unstyled"></ul>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-lg-auto ml-lg-auto text-right">
                            <button id="simpanBtn" class="btn btn-success w-100">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

        </section>
    </div>
</body>

<script src="https://cdn.tailwindcss.com"></script>
<script src="<?= base_url('asset/AdminLTE/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('asset/AdminLTE/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // get tanggal sekarang
    const input = document.getElementById("tanggal");
    const today = new Date().toISOString().split("T")[0];
    input.value = today;

        
    const KELAS = <?= json_encode($kelas); ?>;   // [{id_kelas, nama_kelas}]
    const SISWA = <?= json_encode($siswa); ?>;   // [{no_induk, nama_siswa, id_kelas}]
    const ketSelect = document.getElementById('keteranganSelect');
    const kelasSelect = document.getElementById('kelasSelect');
    const namaInput = document.getElementById('namaInput');
    const listAbsen = document.getElementById('listAbsen');
    

    // Saat siswa dipilih
    namaInput.addEventListener('change', function () {
        if (this.value) {
            ketSelect.disabled = false;   // aktifkan keterangan
        } else {
            ketSelect.disabled = true;    // nonaktifkan lagi jika siswa dikosongkan
            ketSelect.value = '';
        }
    });


 // aktifkan select2 SETELAH jQuery ada
    $(document).ready(function () {
        $('#namaInput').select2({
            placeholder: '-- Pilih Siswa --',
            allowClear: true,
            width: '100%'
        });
        $('#namaInput').prop('disabled', true);
    });

kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
KELAS.forEach(k => {
    const opt = document.createElement('option');
    opt.value = k.id_kelas;
    opt.textContent = k.nama_kelas;
    kelasSelect.appendChild(opt);
});

// saat kelas berubah, isi siswa sesuai id_kelas
kelasSelect.addEventListener('change', function () {
    updateSiswaByKelas();
    
});


function updateSiswaByKelas() {
    const idKelas = kelasSelect.value;

    // reset select siswa
    namaInput.innerHTML = '<option value="">-- Pilih Siswa --</option>';

    if (!idKelas) {
        // jika kelas belum dipilih, disable lagi
        $('#namaInput').prop('disabled', true);
        return;
    }

    // aktifkan select siswa
    $('#namaInput').prop('disabled', false);

    SISWA
        .filter(s => String(s.kelas) === String(idKelas))
        .forEach(s => {
            const opt = new Option(s.nama_siswa, s.no_induk, false, false);
            $('#namaInput').append(opt);
        });
     // refresh select2
    $('#namaInput').trigger('change');
}

document.getElementById('tambahBtn').addEventListener('click', () => {
    const idKelas = kelasSelect.value;
    const kelasText = kelasSelect.options[kelasSelect.selectedIndex].text;

    const siswaSelect = document.getElementById('namaInput');
    const ketSelect = document.getElementById('keteranganSelect');

    const idSiswa = siswaSelect.value;
    const namaSiswa = siswaSelect.options[siswaSelect.selectedIndex]?.text;
    const idKeterangan = ketSelect.value;
    const textKeterangan = ketSelect.options[ketSelect.selectedIndex]?.text;

    if (!idKelas) {
        showNotif('failed', 'Pilih kelas terlebih dahulu');
        return;
    }
    if (!idSiswa) {
        showNotif('failed', 'Pilih siswa terlebih dahulu');
        return;
    }

    if (!idKeterangan) {
        showNotif('failed', 'Pilih keterangan terlebih dahulu');
        return;
    }

    // Cegah siswa yang sama ditambahkan dua kali
    if (listAbsen.querySelector(`input[value="${idSiswa}"]`)) {
        showNotif('error', 'Siswa sudah ditambahkan');
        return;
    }

    const li = document.createElement('li');
    li.className = 'bg-slate-50 p-3 rounded-xl';
    li.style.marginBottom = "5px";
    li.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center">
            <div class="md:col-span-2 font-medium">
                ${kelasText} - ${namaSiswa}
            </div>
            <div class="text-sm text-slate-600">
                <b>${textKeterangan}</b>
            </div>
            <div class="text-right">
                <button type="button" class="text-red-500">Hapus</button>
            </div>
        </div>

        <input type="hidden" name="id_siswa[]" value="${idSiswa}" />
        <input type="hidden" name="id_kelas[]" value="${idKelas}" />
        <input type="hidden" name="keterangan[]" value="${idKeterangan}" />
    `;

    li.querySelector('button').onclick = () => li.remove();
    listAbsen.appendChild(li);

    // reset pilihan
    siswaSelect.value = '';
    ketSelect.value = '';
});
   function showNotif(type, message, duration = 3000) {
    const notifArea = document.getElementById('notifArea');

    const classMap = {
        success: 'alert-success',
        failed: 'alert-warning',
        error: 'alert-danger'
    };

    notifArea.innerHTML = `
        <div class="alert ${classMap[type]} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;

    const alertEl = notifArea.querySelector('.alert');

    // Auto close pakai Bootstrap 4
    setTimeout(() => {
        if (window.jQuery) {
            $(alertEl).alert('close');
        } else {
            // fallback kalau jQuery tidak ada
            alertEl.remove();
        }
    }, duration);
}




</script>
