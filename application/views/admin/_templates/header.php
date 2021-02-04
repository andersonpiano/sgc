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
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,700italic">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/bootstrap/css/bootstrap.min.css'); ?>">
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
        <script src="<?php echo base_url($frameworks_dir . '/jquery/jquery.min.js'); ?>"></script>
<?php if (($this->router->fetch_class() == 'plantoes' or $this->router->fetch_class() == 'escalas') && $this->router->fetch_method() == 'index') : ?>
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/fullcalendar/main.min.css'); ?>">
        <script src="<?php echo base_url($frameworks_dir . '/fullcalendar/main.min.js'); ?>"></script>
        <script src="<?php echo base_url($frameworks_dir . '/cemerge/js/calendar.js'); ?>"></script>
<?php endif; ?>
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
