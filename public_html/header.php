<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 3:01 PM
 */

if(!isset($_SESSION['admin'])) {
    header('Location: login');
} else {
    $__CONNECT->setCurrentUser();
}


?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard | Connect</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $site_url;?>/assets/img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/font-awesome.min.css">
    <!-- adminpro icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/adminpro-custon-icon.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/meanmenu.min.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/jquery.mCustomScrollbar.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/animate.css">
    <!-- data-table CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/data-table/bootstrap-editable.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/normalize.css">
    <!-- charts C3 CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/c3.min.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/form/all-type-forms.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="<?php echo $site_url;?>/assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <!-- Sweet Alert
    ============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/bower_components/bootstrap-sweetalert/dist/sweetalert.css">

    <!-- dropzone CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo $site_url;?>/assets/css/dropzone.css">

    <!-- Notie
        ============================================ -->
    <link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/assets/css/notie.min.css">
    <style>
        /* override styles here */
        .notie-container {
            box-shadow: none;
        }
    </style>

    <!-- Toastr
        ============================================ -->
    <link rel="stylesheet" type="text/css" href="<?php echo $site_url;?>/assets/css/toastr.min.css">

</head>

<body class="darklayout">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Header top area start-->
<div class="wrapper-pro">

    <?php include('sidebar.php'); ?>



    <div class="content-inner-all">
        <?php include('topbar.php'); ?>


