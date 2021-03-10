<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
    <head>
        <meta charset="<?php echo $charset; ?>">
        <title><?php echo $title; ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="shortcut icon" href="<?php echo base_url($frameworks_dir . '/medino/assets/images/logo/favicon.png'); ?>" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url($frameworks_dir . '/medino/assets/images/logo/favicon.png'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/googlefonts-sourcesanspro.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/w3/w3.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/font-awesome/css/font-awesome.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/ionicons/css/ionicons.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/adminlte/css/adminlte.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/adminlte/css/skins/skin-green.min.css'); ?>">
<?php if ($this->router->fetch_class() == 'groups' && ($this->router->fetch_method() == 'create' OR $this->router->fetch_method() == 'edit')) : ?>
        <link rel="stylesheet" href="<?php echo base_url($plugins_dir . '/colorpickersliders/colorpickersliders.min.css'); ?>">
<?php endif; ?>
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/domprojects/css/dp.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/cemerge.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/hint.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/calendar.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url($frameworks_dir . '/cemerge/css/calendario.css'); ?>">
    </head>
    <body class="hold-transition skin-green fixed sidebar-mini">