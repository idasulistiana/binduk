<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
        <img id="logo-sekolah" src="<?= base_url('asset/AdminLTE/') ?>dist/img/logo_sekolah.png" alt="sekolah Logo" class="brand-image img-circle elevation-3" style="opacity: 0; transition: opacity 0.5s ease-in-out;">
        <span class="brand-text font-weight-light">SDN Tegal Alur 04</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= strtolower($this->uri->segment(1)) == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                 <!-- Data Akademik-->
                <?php
                $segment1 = strtolower($this->uri->segment(1));
                $submenu = ['siswa', 'alumni']; // submenu yg terkait dengan Data Akademik
                ?>

                <li class="nav-item has-treeview <?= in_array($segment1, $submenu) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($segment1, $submenu) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Data Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('siswa') ?>" class="nav-link <?= strtolower($this->uri->segment(1)) == 'siswa' ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Aktif</p>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a href="<?= base_url('alumni') ?>" class="nav-link <?= ($segment1 == 'alumni') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Alumni</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Pembantu Induk -->
                

                 <!-- Data Klapper -->
                <li class="nav-item">
                   <a href="<?= base_url('riwayatkelas') ?>" class="nav-link <?= (strtolower($this->uri->segment(1)) == 'riwayatkelas') ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-chalkboard-teacher"></i>
                        <p>Data Kelas Siswa</p>
                    </a>
                </li>
                 <!-- Data Kehadiran -->
                <li class="nav-item">
                   <a href="<?= base_url('kehadiran') ?>" class="nav-link <?= (strtolower($this->uri->segment(1)) == 'kehadiran') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-barcode"></i>
                        <p>Data Kehadiran siswa</p>
                    </a>
                </li>
                <!-- Data Akademik-->
                <?php
                $segment1 = strtolower($this->uri->segment(1));
                $submenu = ['kelas', 'mapel', 'ekskul', 'nilai']; // submenu yg terkait dengan Data Akademik
                ?>

                <li class="nav-item has-treeview <?= in_array($segment1, $submenu) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= in_array($segment1, $submenu) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Data Akademik
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('kelas') ?>" class="nav-link <?= ($segment1 == 'kelas') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('mapel') ?>" class="nav-link <?= ($segment1 == 'mapel') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Mapel</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('ekskul') ?>" class="nav-link <?= ($segment1 == 'ekskul') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Ekskul</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('nilai') ?>" class="nav-link <?= ($segment1 == 'nilai') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Nilai Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Buku Induk -->
                <li class="nav-item">
                    <a href="<?= base_url('bukuinduk') ?>" class="nav-link <?= (strtolower($this->uri->segment(1)) == 'bukuinduk') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Buku Induk Siswa</p>
                    </a>
                </li>

                <!-- SignOut -->
                <li class="nav-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>SignOut</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>