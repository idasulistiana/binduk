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
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/daterangepicker/daterangepicker.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

     <!-- judul di data table middle rata atas bawa  -->
    <style>
        table.dataTable th {
            vertical-align: middle !important;  /* rata tengah vertikal */
            text-align: center;                  /* rata tengah horizontal */
        }
        .padding-left20 {
            padding-left: 20px;
        }
        .mright-10 {
            margin-right:10px;
        }

        /* Tab kelas  */
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

</head>