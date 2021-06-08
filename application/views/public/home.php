<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>SGC - Sistema de Gestão Cemerge</title>

    <link rel="shortcut icon" href="<?php echo base_url($frameworks_dir . '/medino/assets/images/logo/favicon.png'); ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/animate-3.7.0.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/font-awesome-4.7.0.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/bootstrap-4.1.3.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/owl-carousel.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/jquery.datetimepicker.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/linearicons.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/medino/assets/css/style.css'); ?>">
</head>
<body>
    <!-- Preloader Starts -->
    <div class="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Preloader End -->

    <!-- Header Area Starts -->
    <header class="header-area">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 d-md-flex">
                        <h6 class="mr-3"><span class="mr-2"><i class="fa fa-mobile"></i></span>Informações 85-32441704 / 85-32641912</h6>
                        <h6 class="mr-3"><span class="mr-2"><i class="fa fa-envelope-o"></i></span> cemerge@secrel.com.br</h6>
                    </div>
                    <div class="col-lg-3">
                        <div class="social-links">
                            <ul>
                                <!--<li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="header" id="home">
            <div class="container">
                <div class="row align-items-center justify-content-between d-flex">
                <div id="logo">
                    <a href="#"><img src="<?php echo base_url($frameworks_dir . '/medino/assets/images/logo/logo.png'); ?>" alt="Cemerge" title="Cemerge" /></a>
                </div>
                <nav id="nav-menu-container">
                    <ul class="nav-menu">
                        <li class="menu-active"><a href="/sgc/">Início</a></li>
                        <li><a href="/sgc/home/help">Ajuda</a></li>
                        <!--<li><a href="suporte.html">Suporte</a></li>-->
                        <li><a href="http://www.cemerge.com.br">Acessar o site</a></li>
                        <li><a href="/sgc/auth/login">Acessar o sistema</a></li>
                    </ul>
                </nav><!-- #nav-menu-container -->
                </div>
            </div>
        </div>
    </header>
    <!-- Header Area End -->

    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <h4>Sistema de Gestão Cemerge</h4>
                    <h1>Buscamos a excelência na gestão da cooperativa</h1>
                    <p>Todas as informações necessárias &agrave; sua disposição</p>
                    <a href="/sgc/auth/login" class="template-btn mt-3">Acessar</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area End -->

    <!-- Javascript -->
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/jquery-2.2.4.min.js'); ?>"></script>
	<script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/bootstrap-4.1.3.min.js'); ?>"></script>
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/wow.min.js'); ?>"></script>
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/owl-carousel.min.js'); ?>"></script>
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/jquery.datetimepicker.full.min.js'); ?>"></script>
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/jquery.nice-select.min.js'); ?>"></script>
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/vendor/superfish.min.js'); ?>"></script>
    <script src="<?php echo base_url($frameworks_dir . '/medino/assets/js/main.js'); ?>"></script>

    <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
      <div class="vw-plugin-top-wrapper"></div>
    </div>
  </div>
  <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
  <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
  </script>

</body>
</html>