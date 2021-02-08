<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
    <head>
        <meta charset="<?php echo $charset; ?>">
        <title><?php echo $title; ?></title>
<?php if ($mobile === false) : ?>
        <!--[if IE 8]>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
<?php else: ?>
        <meta name="HandheldFriendly" content="true">
<?php endif; ?>
<?php if ($mobile == true && $mobile_ie == true) : ?>
        <meta http-equiv="cleartype" content="on">
<?php endif; ?>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="google" content="notranslate">
        <meta name="robots" content="noindex, nofollow">
<?php if ($mobile == true && $ios == true): ?>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="<?php echo $title; ?>">
<?php endif; ?>
<?php if ($mobile == true && $android == true) : ?>
        <meta name="mobile-web-app-capable" content="yes">
<?php endif; ?>
        <link rel="shortcut icon" href="<?php echo base_url($frameworks_dir . '/medino/assets/images/logo/favicon.png'); ?>" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url($frameworks_dir . '/medino/assets/images/logo/favicon.png'); ?>">
        <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic,700italic">-->
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/googlefonts-sourcesanspro.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/w3/w3.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/ionicons/css/ionicons.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/adminlte/css/adminlte.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/adminlte/css/skins/skin-green.min.css'); ?>">

<?php if ($mobile === false && $admin_prefs['transition_page'] == true) : ?>
        <link rel="stylesheet" href="<?php echo base_url($plugins_dir . '/animsition/animsition.min.css'); ?>">
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'groups' && ($this->router->fetch_method() == 'create' OR $this->router->fetch_method() == 'edit')) : ?>
        <link rel="stylesheet" href="<?php echo base_url($plugins_dir . '/colorpickersliders/colorpickersliders.min.css'); ?>">
<?php endif; ?>
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/domprojects/css/dp.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/cemerge.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/hint.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/calendar.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/calendario.css'); ?>">
        <script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>
<?php if ($this->router->fetch_class() == 'plantoes' && in_array($this->router->fetch_method(), ['index', 'cessoestrocas'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/cessoestrocas.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'escalas' && in_array($this->router->fetch_method(), ['index'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/html2canvas/html2canvas.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/jsPDF/jspdf.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/export2pdf.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'plantoes' && in_array($this->router->fetch_method(), ['cederplantaoeprocessar'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/frequencias_por_profissional.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'justificativas' && in_array($this->router->fetch_method(), ['create'])) : ?>
        <script src="<?php //echo base_url($frameworks_dir . '/cemerge/js/justificativa.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'frequencias' && in_array($this->router->fetch_method(), ['editarfrequencia', 'buscarfrequencias'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/setores_assessus_por_unidade.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'frequencias' && in_array($this->router->fetch_method(), ['buscarfrequenciaporprofissional'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/profissionais_por_unidade.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'escalas' && in_array($this->router->fetch_method(), ['buscarescalaporprofissional'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/profissionais_por_unidade.js'); ?>"></script>
<?php endif; ?>

<?php if ($this->router->fetch_class() == 'faltas' && in_array($this->router->fetch_method(), ['index'])) : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/faltas__profissionais_por_unidade_hospitalar.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'residentes' && $this->router->fetch_method() == 'register') : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/gps.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/clock.js'); ?>"></script>
<?php endif; ?>
<?php if ($this->router->fetch_class() == 'especializacoes') : ?>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/sweetalert2.all.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/datatables.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/dataTables.bootstrap.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/util.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/modal.js'); ?>"></script>
<?php endif; ?>
<script src="<?php echo base_url($frameworks_dir . '/cemerge/js/pace.min.js'); ?>"></script>
<script src="<?php echo base_url($frameworks_dir . '/moment/moment.min.js'); ?>"></script>
<script src="<?php echo base_url($frameworks_dir . '/cemerge/js/jquery.blockUI.js'); ?>"></script>
<?php if ($mobile === false) : ?>
        <!--[if lt IE 9]>
            <script src="<?php echo base_url($plugins_dir . '/html5shiv/html5shiv.min.js'); ?>"></script>
            <script src="<?php echo base_url($plugins_dir . '/respond/respond.min.js'); ?>"></script>
        <![endif]-->
<?php endif; ?>
    </head>
    <body class="hold-transition skin-green fixed sidebar-mini">
<?php if ($mobile === false && $admin_prefs['transition_page'] == true) : ?>
        <div class="wrapper animsition">
<?php else: ?>
        <div class="wrapper">
<?php endif; ?>
