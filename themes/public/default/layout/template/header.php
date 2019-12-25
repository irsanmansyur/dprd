<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= @$page['title']; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?= $thema_folder; ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?= $thema_folder; ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="<?= $thema_folder; ?>assets/css/agency.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="<?= base_url() ?>#page-top"><?= $this->setting->site_name ?></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase mr-auto mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="<?= base_url() ?>#panduan">Panduan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="<?= base_url() ?>#about">About</a>
                    </li>
                    <form class="form-inline my-2 my-lg-0" action="<?= base_url('aspirasi/search') ?>" method="post">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search Inspirasi">
                        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit">Search</button>
                    </form>

                </ul>
                <div class="navbar-nav">
                    <?php if (!$this->session->userdata('masyarakat_login')) : ?>
                        <div class="my-auto mr-md-2">
                            <a class="nav-link btn btn-outline-warning btn-sm" href="<?= base_url('admin/auth') ?>">Login</a>
                        </div>
                        <div class="my-auto">
                            <a class="nav-link btn btn-danger btn-sm mx-auto text-light" href="<?= base_url('admin/auth/registration/masyarakat') ?>">Register</a>
                        </div>
                    <?php else : ?>
                        <div class="nav-item">
                            <a class="mt-2 mr-md-2 btn btn-outline-warning waves-effect waves" href="#">
                                <span class="badge badge-danger ml-2">4</span>
                                <i class="fas fa-bell"></i>
                            </a>
                        </div>
                        <div class="nav-item dropdown">
                            <a class="dropdown-toggle btn btn-primary text-left d-block mt-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Irsan Mansyur
                            </a>
                            <div class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?= base_url('user') ?>">Profile</a>
                                <a class="dropdown-item" href="<?= base_url('user/profile') ?>">Change Profile</a>
                                <a class="dropdown-item" href="<?= base_url('user/change_password') ?>">Change Password</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-toggle="modal" data-target=".modal#logout" href="#">Logout</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>

    <!-- Home Title -->
    <header class="masthead">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?= @$page['title']; ?></div>
                <div class="intro-heading text-uppercase">DPRD Makassar</div>
                <?php if ($this->router->fetch_class() == "home" && "index" == $this->router->fetch_method()) : ?>
                    <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" data-toggle="modal" data-target="#laporModal" href="<?= base_url('user/lapor') ?>">Laporkan!</a>
                <?php endif; ?>
            </div>
        </div>
    </header>