<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>idasulistiana</title>
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Preload dan stylesheet utama -->
    <link rel="preload" href="<?= base_url('asset/AdminLTE/dist/css/adminlte.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= base_url('asset/AdminLTE/dist/css/adminlte.min.css') ?>"></noscript>

    <!-- Plugin CSS lainnya -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/summernote/summernote-bs4.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Inline CSS kritikal -->
    <style>
        body {
            visibility: hidden; /* sembunyikan dulu sampai CSS siap */
            font-family: "Source Sans Pro", sans-serif;
        }
        table.dataTable th {
            vertical-align: middle !important;
            text-align: center;
        }
        .padding-left20 { padding-left: 20px; }
        .mright-10 { margin-right:10px; }

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
    </style>

    <!-- Tampilkan body setelah CSS siap -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.body.style.visibility = "visible";
        });
    </script>
</head>
