<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .select2-container--default .select2-search--dropdown .select2-search__field {
        display: block !important;
        width: 100% !important;
    }

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
    <script src="<?= base_url('asset/AdminLTE/plugins/jquery/jquery.min.js') ?>"></script>
    <link rel="preload" href="<?= base_url('asset/AdminLTE/dist/css/adminlte.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/select2/css/select2.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="<?= base_url('asset/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <!-- ================= Stylesheet tambahan ================= -->

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
        <section class="content-header" style="box-shadow: 2px 2px 5px #00000040; background-color: #ffffff">
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
            <form id="absenForm" role="form" action="<?= base_url('absensi/store') ?>" method="POST">
                <div class="row g-3">
                    <!-- Kiri -->
                    <div class="col-12 col-lg-8 mt-3">
                        <label class="d-block d-md-inline-flex align-items-center font-medium">
                            Tanggal :
                            <input id="tanggal"
                                neme="tanggal"
                                type="date"
                                class="ml-md-2 mt-2 mt-md-0 text-center rounded-xl border-slate-300 pointer-events-none"
                                readonly>
                        </label>
                        <div class="bg-white rounded-2xl shadow p-4 mb-4">
                    

                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label>Pilih Kelas</label>
                                        <select id="kelasSelect" class="form-control" ></select>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Status Kehadiran Kelas</label>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                type="radio" 
                                                name="status_kelas" 
                                                id="status_hadir"
                                                value="1"
                                                checked>
                                            <label class="form-check-label" for="status_hadir">
                                                Semua siswa hadir
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                type="radio" 
                                                name="status_kelas" 
                                                id="status_tidak_hadir"
                                                value="0">
                                            <label class="form-check-label" for="status_tidak_hadir">
                                                Ada siswa tidak hadir
                                            </label>
                                        </div>
                                    </div>
                                    <div class="content-name" style="width:100%; display:none">
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
                                         <div class="col-12 mb-2">
                                            <label>Catatan</label>
                                            <textarea
                                                class="form-control"
                                                id="catatan"
                                                name="catatan[]"
                                                rows="3"
                                                placeholder="Masukkan catatan..."
                                                ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 col-lg-auto ml-lg-auto text-right">
                                    <button type="button" id="tambahBtn" class="btn btn-primary w-100">
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div id="list-siswa" class="col-12 col-lg-4 mt-desktop-50" style="display:none">
                        <div class="bg-white rounded-2xl shadow p-4">
                            <h2 class="h4 fw-semibold mb-3">
                                Daftar Siswa Tidak Masuk
                            </h2>
                            <ul id="listAbsen" class="list-unstyled"></ul>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-lg-auto ml-lg-auto text-right">
                                <button id="simpanBtn" type="button" class="btn btn-success w-100">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
        </section>
    </div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="<?= base_url('asset/AdminLTE/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ===================== TANGGAL ===================== */
const inputTanggal = document.getElementById("tanggal");
inputTanggal.value = new Date().toISOString().split("T")[0];

/* ===================== DATA ===================== */
const KELAS = <?= json_encode($kelas); ?>;
const SISWA = <?= json_encode($siswa); ?>;

const kelasSelect = document.getElementById('kelasSelect');
const namaInput   = $('#namaInput'); // pakai jQuery
const ketSelect   = document.getElementById('keteranganSelect');
const listAbsen   = document.getElementById('listAbsen');
const catatan   = document.getElementById('catatan');
/* ===================== RESET CATATAN ===================== */
namaInput.val(null).trigger('change.select2');
ketSelect.value = '';
document.getElementById('catatan').value = '';

$(document).ready(function () {
    setTimeout(function () {
        $('.alert').alert('close');
    }, 3000);
});
/* ===================== STATUS KELAS ===================== */
$('#status_tidak_hadir').on('click', () => {
    $('.content-name').show();

    // Reset daftar siswa
    listAbsen.innerHTML = '';

    // Sembunyikan list sampai klik Tambah
    $('#list-siswa').hide();

    // Reset input
    namaInput.val(null).trigger('change');
    ketSelect.value = '';
    catatan.value = '';
});
$('#status_hadir').on('click', () => {

    $('.content-name').hide();

    // Reset daftar
    listAbsen.innerHTML = '';

    // Sembunyikan list
    $('#list-siswa').hide();

    // Reset input
    namaInput.val(null).trigger('change');
    ketSelect.value = '';
    catatan.value = '';
});

/* ===================== INIT SELECT2 ===================== */
$(function () {
    namaInput.select2({
        placeholder: '-- Pilih Siswa --',
        allowClear: true,
        width: '100%'

    }).prop('disabled', true);
});

/* ===================== ISI KELAS ===================== */
kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
KELAS.forEach(k => {
    const opt = document.createElement('option');
    opt.value = k.id_kelas;
    opt.textContent = k.nama_kelas;
    kelasSelect.appendChild(opt);
});

/* ===================== KELAS BERUBAH ===================== */
kelasSelect.addEventListener('change', updateSiswaByKelas);

function updateSiswaByKelas() {
    const idKelas = kelasSelect.value;

    namaInput.empty().append('<option value=""></option>');
    ketSelect.disabled = true;

    if (!idKelas) {
        namaInput.prop('disabled', true).trigger('change.select2');
        return;
    }

    SISWA
        .filter(s => String(s.id_kelas || s.kelas) === String(idKelas))
        .forEach(s => {
            namaInput.append(
                new Option(s.nama_siswa, s.no_induk, false, false)
            );
        });

    namaInput.prop('disabled', false).trigger('change.select2');
}
/* ===================== SISWA DIPILIH ===================== */
namaInput.on('change', function () {
    ketSelect.disabled = !this.value;
    if (!this.value) ketSelect.value = '';
});

/* ===================== TAMBAH DATA ===================== */
document.getElementById('tambahBtn').addEventListener('click', () => {
    document.getElementById('list-siswa').style.display = 'block';
    console.log(document.getElementById('list-siswa'));
        
    const statusKelas = document.querySelector(
        'input[name="status_kelas"]:checked'
    ).value;

    if (statusKelas === '1') {

        listAbsen.innerHTML = `
            <li class="bg-slate-50 p-3 rounded-xl mb-1 text-center font-medium">
                Semua siswa hadir
            </li>
        `;

        namaInput.val(null).trigger('change.select2');
        ketSelect.value = '';

        return;
    }

    const idKelas   = kelasSelect.value;
    const kelasText = kelasSelect.options[kelasSelect.selectedIndex]?.text;

    const no_induk  = namaInput.val();
    const namaSiswa = namaInput.find(':selected').text();

    const idKet     = ketSelect.value;
    const textKet   = ketSelect.options[ketSelect.selectedIndex]?.text;

    if (!idKelas)
        return showNotif('failed', 'Pilih kelas terlebih dahulu');

    if (!no_induk)
        return showNotif('failed', 'Pilih siswa terlebih dahulu');

    if (!idKet)
        return showNotif('failed', 'Pilih keterangan terlebih dahulu');

  // Cek apakah siswa sudah ada di daftar
    const existing = listAbsen.querySelector(`li[data-no-induk="${no_induk}"]`);

    if (existing) {
        existing.querySelector('.ket-text').innerHTML = `<b>${textKet}</b>`;

        existing.querySelector('input[name="keterangan[]"]').value = idKet;

        existing.querySelector('input[name="catatan[]"]').value =
            document.getElementById('catatan').value;

        showNotif('success', 'Data siswa berhasil diperbarui');

        namaInput.val(null).trigger('change.select2');
        ketSelect.value = '';
        document.getElementById('catatan').value = '';

        return;
}

    const li = document.createElement('li');

    li.className = 'bg-slate-50 p-3 rounded-xl mb-1';

    li.dataset.noInduk = no_induk;

    li.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-center">

            <div class="md:col-span-2 font-medium">
                ${kelasText} - ${namaSiswa}
            </div>

            <div class="text-sm text-slate-600 ket-text">
                <b>${textKet}</b>
            </div>

            <div class="text-sm text-slate-600">
                ${document.getElementById('catatan').value}
            </div>

            <div class="text-right">
                <button type="button" class="text-red-500">
                    Hapus
                </button>
            </div>

        </div>
        <input type="hidden" name="id_kelas[]" value="${idKelas}">
        <input type="hidden" name="no_induk[]" value="${no_induk}">
        <input type="hidden" name="keterangan[]" value="${idKet}">
        <input type="hidden" name="catatan[]" value="${document.getElementById('catatan').value}">
    `;

    li.querySelector('button').onclick = () => li.remove();

    listAbsen.appendChild(li);
    
    namaInput.val(null).trigger('change.select2');
    ketSelect.value = '';
    catatan.value = '';

});
/* ===================== NOTIF ===================== */
function showNotif(type, message, duration = 3000) {
    const notifArea = document.getElementById('notifArea');
    const map = {
        success: 'alert-success',
        failed: 'alert-warning',
        error: 'alert-danger'
    };

    notifArea.innerHTML = `
        <div class="alert ${map[type]} alert-dismissible fade show">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;

    setTimeout(() => $('.alert').alert('close'), duration);
}

/* ===================== SUBMIT FORMNYA ===================== */
document.getElementById('simpanBtn').addEventListener('click', function () {

    const idKelas = kelasSelect.value;

    const statusKelas = document.querySelector(
        'input[name="status_kelas"]:checked'
    ).value;

    const jumlahList =
        document.querySelectorAll('#listAbsen li').length;

    if (!idKelas) {

        showNotif(
            'failed',
            'Pilih kelas terlebih dahulu'
        );

        return;
    }

    if (statusKelas === '0' && jumlahList === 0) {

        showNotif(
            'failed',
            'Tambahkan minimal 1 siswa tidak hadir'
        );

        return;
    }
    if (statusKelas === '1') {

        document.querySelectorAll('.kelas-submit').forEach(e => e.remove());

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_kelas[]';
        input.value = idKelas;
        input.className = 'kelas-submit';

        document.getElementById('absenForm').appendChild(input);
    }

    document.getElementById('absenForm').submit();

    });

</script>

