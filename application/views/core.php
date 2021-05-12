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
    <title><?= $webConfig->judul ?> - <?= $pageTitle ?></title>

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
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=' . time()) ?>">
    <?= (isset($cssFiles)) ? $cssFiles : '' ?>
</head>

<body id="page-top">
    <div class="preloader">
        <div class="loader">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url() ?>">
                <div class="sidebar-brand-text mx-3 d-block">
                    <img src="<?= base_url("assets/img/{$webConfig->logo}") ?>" style="height: 3rem" class="d-none d-md-block">
                    <img src="<?= base_url("assets/img/{$webConfig->icon}") ?>" style="height: 2.5rem" class="d-block d-md-none">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                NAVIGASI
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <? if(isset($user)): ?>
                <li class="nav-item <?= isMenuActive('menu', '', 1) ?>">
                    <a class="nav-link" href="<?= base_url() ?>">
                        <i class="fas fa-fw fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

            <? else: ?>
                <li class="nav-item <?= isMenuActive('menu', '', 1) ?>">
                    <a class="nav-link" href="<?= base_url('auth/login') ?>">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </li>
            <? endif ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Waktu Terkini -->
                    <h5 class="ml-3 mr-auto d-none d-md-block my-2"><?= core_waktu_terkini() ?></h5>

                    <? if(isset($user)): ?>
                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Nav Item - Messages -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-envelope fa-fw"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter">7</span>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="messagesDropdown">
                                    <h6 class="dropdown-header">
                                        Message Center
                                    </h6>
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                                alt="...">
                                            <div class="status-indicator bg-success"></div>
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                                problem I've been having.</div>
                                            <div class="small text-gray-500">Emily Fowler · 58m</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                                </div>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user->data->nama_lengkap ?></span>
                                    <img class="img-profile rounded-circle" src="<?= base_url("assets/img/akun/{$user->data->foto_profile}") ?>">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="<?= base_url('pengaturan-akun') ?>">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Pengaturan Akun
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    <? endif ?>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <? if($this->uri->segment(1) != 'error' && $pageTitle != 'Halaman Tidak Ditemukan'): ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?= $pageTitle ?></h1>
                    </div>
                    <? endif ?>

                    <!-- Page Content -->
                    <?= $content ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-5">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <?= date('Y') . " {$webConfig->judul}, All Rights Rerserved." ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/js/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vendor/bootstrap/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/js/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/vendor/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/main.js?v=' . time()) ?>"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/js/vendor/chart.js/Chart.min.js') ?>"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('assets/js/demo/chart-area-demo.js') ?>"></script>
    <script src="<?= base_url('assets/js/demo/chart-pie-demo.js') ?>"></script>

    <!-- Custom script for this page -->
    <script>
        const base_url = '<?= base_url() ?>';
        const csrf_token = '<?= $this->security->get_csrf_hash() ?>';
    </script>
    <?= (isset($jsFiles)) ? $jsFiles : '' ?>

    <? if(isset($_SESSION['alertResult'])): ?>
        <script>show_result("<?= $_SESSION['alertResult']['status'] ?>", "<?= $_SESSION['alertResult']['message'] ?>");</script>
    <? endif ?>

</body>

</html>