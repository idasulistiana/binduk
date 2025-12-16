<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - SDN Tegal Alur 04 PG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('asset/AdminLTE/') ?>/dist/css/adminlte.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('<?= base_url("asset/images/bg-sekolah.png") ?>') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            overflow: hidden;
        }

        .login-container {
            width: 40%;
            padding: 71px;
            position: absolute;
            top: 60%;
            transform: translateY(-50%);  
        }

        .login-container img.logo {
            display: block;
            margin: 0 auto 15px auto;
            width: 30%;
            margin-bottom: 4px;
            
        }

        .login-title {
            text-align: center;
            color: #003366;
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .login-footer {
            text-align: center;
            font-size: 12px;
            color: white;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            body {
                justify-content: center;
            }

            .login-container {
                width: 85%;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="login-container justify-content-center align-items-center vh-100">
        <img src="<?= base_url('asset/images/logo_sekolah.png') ?>" class="logo" alt="Logo DKI">
        <h4 class="login-title">
            Sistem Buku Induk Siswa <br>
            SD Negeri Tegal Alur 04 PG 
        </h4>

        <?php if ($this->session->userdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->userdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('controllerLogin') ?>" method="post">
            <div class="form-group mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>

            <div class="form-group mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn btn-danger btn-block">LOGIN</button>
        </form>

        <div class="login-footer">
            &copy; <?= date('Y') ?> Dinas Pendidikan Provinsi DKI Jakarta
        </div>
    </div>

    <script src="<?= base_url('asset/AdminLTE/') ?>plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url('asset/AdminLTE/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('asset/AdminLTE/') ?>dist/js/adminlte.min.js"></script>
</body>

</html>
