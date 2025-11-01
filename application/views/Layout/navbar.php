<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <!-- Tombol Menu -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <!-- Tombol Back -->
                        <?php
            $controller = $this->uri->segment(1); // 'nilai'
            $method     = $this->uri->segment(2); // 'all_nilai_siswa' / 'edit_siswa'
            $id_siswa   = $this->uri->segment(3); // ID siswa, dinamis

            // Tentukan URL tujuan Back
            $back_url = '';

            if ($controller == 'nilai') {
                if ($method == 'all_nilai_siswa' && !empty($id_siswa)) {
                    // Dari halaman list nilai siswa -> kembali ke halaman utama nilai
                    $back_url = base_url('nilai');
                } elseif ($method == 'edit_siswa' && !empty($id_siswa)) {
                    // Dari halaman edit siswa -> kembali ke halaman list nilai siswa
                    $back_url = base_url("nilai/all_nilai_siswa/$id_siswa");
                }
            }

            // Tampilkan tombol jika $back_url sudah ditentukan
            if (!empty($back_url)) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $back_url ?>" role="button" title="Kembali">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
            <?php } ?>
            </ul>
        </nav>
        <!-- /.navbar -->
