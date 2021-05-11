<? defined('BASEPATH') OR exit('No direct script access allowed.') ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $webConfig->deskripsi ?>">
    <meta name="author" content="SilentX">

    <link rel="shortcut icon" href="<?= base_url("assets/img/{$webConfig->icon}") ?>" type="image/x-icon">
    <title><?= $webConfig->judul ?> - Login</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/css/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    
    <!-- CSS Plugin -->
    <link href="<?= base_url('assets/css/vendor/animate/animate.min.css') ?>" rel="stylesheet">

    <!-- CSS Custom -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>

<body class="bg-gradient-primary">
    <div class="preloader">
        <div class="loader">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background-image: url('https://i.pinimg.com/564x/8c/6f/63/8c6f636243880c2185d9d619bc588951.jpg')!important;"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang Kembali!</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= $this->security->get_csrf_hash() ?>">

                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                placeholder="Masukkan username anda..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                placeholder="Masukkan password anda..." required>
                                        </div>

                                        <button type="button" onclick="show_result('success', 'Hello World.')" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>

                                    <div class="text-center text-muted" style="margin-top: 5rem;">
                                        <hr>
                                        <small>&copy; <?= date('Y') . " {$webConfig->judul}, All Rights Reserved." ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/js/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/js/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vendor/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js?v=' . time()) ?>"></script>

    <? if(isset($_SESSION['alertResult'])): ?>
        <script>show_result("<?= $_SESSION['alertResult']['status'] ?>", "<?= $_SESSION['alertResult']['message'] ?>");</script>
    <? endif ?>

</body>

</html>