<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="<?= base_url('asset/AdminLTE/dist/img/logo_sekolah.png') ?>">
	<link rel="icon" type="image/x-icon" href="<?= base_url('asset/AdminLTE/dist/img/favicon_v2.ico') ?>">


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>idasulistiana</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ================= Inline CSS kritikal ================= -->
    <style>


    #tableSiswa td, 
    #tableSiswa th {
    text-align: center;
    vertical-align: middle;
    }

    #tableLoadingSpinner {
        transition: opacity 0.3s ease;
        }
        #tableLoadingSpinner[style*="display: none"] {
        opacity: 0;
        }

    .content-wrapper .content {
    margin: 10px !important;
    }

    /* Matikan efek hover sidebar (AdminLTE biasanya pakai :hover untuk buka) */
    /* ======== FIX ICON BERGERAK SAAT HOVER (AdminLTE 3) ======== */

        /* Kunci lebar sidebar dan nonaktifkan efek hover expand */
        body.sidebar-collapse .main-sidebar,
        body.sidebar-collapse .main-sidebar:hover {
        width: 80px !important;
        overflow: hidden !important;
        transition: none !important;
        }

        /* Pastikan konten utama tidak bergeser */
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-header,
        body.sidebar-collapse .main-footer {
        transition: none !important;
        }

        /* Matikan semua animasi dan transformasi di dalam sidebar */
        body.sidebar-collapse .main-sidebar *,
        body.sidebar-collapse .main-sidebar:hover * {
        transition: none !important;
        transform: none !important;
        animation: none !important;
        }
        
    /* Matikan efek hover sidebar (AdminLTE biasanya pakai :hover untuk buka) */


        /* Sembunyikan seluruh halaman sampai semua CSS & gambar siap */
        body {
            display: none;
            font-family: "Source Sans Pro", sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            color: #333;
        }
      

        /* Tabel dataTables */
        table.dataTable th {
            vertical-align: middle !important;
            text-align: center;
        }

        .padding-left20 { padding-left: 20px; }
        .mright-10 { margin-right: 10px; }

        /* Tab kelas */
        #kelasTab .nav-link {
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 5px 5px 0 0;
            padding: 10px 15px;
            background-color: #f8f9fa;
            color: #000;
            transition: all 0.3s;
            margin-right: 5px;
        }
        #kelasTab .nav-link.active {
            background-color: #000 !important;
            color: #fff !important;
        }
         .brand-link {
            display: none; /* sembunyikan sampai CSS siap */
        }
        /* Gambar responsif */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
    </style>

    <!-- ================= Preload CSS utama & plugin ================= -->
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

    <!-- ================= Fallback untuk browser tanpa preload ================= -->
    <noscript>
        <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/dist/css/adminlte.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/select2/css/select2.min.css') ?>">
        <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    </noscript>

    <!-- ================= Tampilkan body setelah load selesai ================= -->
    
</head>
