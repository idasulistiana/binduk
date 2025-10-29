<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard Buku Induk Siswa</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Total Siswa -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner text-center">
              <h3><?= $total_siswa ?></h3>
              <p>Total Siswa</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-graduate"></i>
            </div>
          </div>
        </div>

        <!-- Laki-laki -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner text-center">
              <h3><?= $siswa_laki ?></h3>
              <p>Laki-laki</p>
            </div>
            <div class="icon">
              <i class="fas fa-male"></i>
            </div>
          </div>
        </div>

        <!-- Perempuan -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-pink">
            <div class="inner text-center">
              <h3><?= $siswa_perempuan ?></h3>
              <p>Perempuan</p>
            </div>
            <div class="icon">
              <i class="fas fa-female"></i>
            </div>
          </div>
        </div>

        <!-- Total Kelas -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner text-center">
              <h3><?= $total_kelas ?></h3>
              <p>Kelas</p>
            </div>
            <div class="icon">
              <i class="fas fa-chalkboard"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Identitas Sekolah -->
      <div class="card mt-4">
        <div class="card-header bg-gradient-warning">
          <h3 class="card-title text-white"><strong>Identitas Sekolah</strong></h3>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <th width="200px">Nama Sekolah</th>
              <td>: <?= $identitas->nama_sekolah ?></td>
            </tr>
            <tr>
              <th>NPSN</th>
              <td>: <?= $identitas->npsn ?></td>
            </tr>
            <tr>
              <th>Nama Kepala Sekolah</th>
              <td>: <?= $identitas->nama_kepala_sekolah ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <!-- Jumlah rombel -->
    <div class="card mt-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Data Jumlah Siswa per Rombel</h5>
    </div>
    <div class="card-body p-3" style="background-color: #e9fbe9;">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="bg-light">
                    <tr>
                        <th>NO</th>
                        <th>ROMBEL</th>
                        <th>Laki-laki</th>
                        <th>Perempuan</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($jumlah_siswa as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row->nama_kelas ?></td>
                            <td><?= $row->L ?></td>
                            <td><?= $row->P ?></td>
                            <td><?= $total_per_kelas->total_siswa ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="2">JUMLAH</th>
                        <th><?= $total_keseluruhan->total_L ?></th>
                        <th><?= $total_keseluruhan->total_P ?></th>
                        <th><?= $total_keseluruhan->total_siswa ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

  </section>
</div>
<script>
    // Nonaktifkan cache halaman agar tombol Back tidak menampilkan halaman lama
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
        // Panggil logout otomatis
        window.location.href = "<?= base_url('controllerLogin/logout') ?>";
    };
</script>
