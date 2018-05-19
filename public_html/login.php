<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 3:02 PM
 */

require_once 'init.php';

$error = '';
if (isset($_POST['username'], $_POST['password'])) {
    //print_r($_POST);
    $error = $__CONNECT->adminLogin($_POST);

    //echo $__GB->DisplayError($error, 'red');
}

if (isset($_SESSION['admin'])) {
    header('Location: index');
}

?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Tracksol | Connect </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- adminpro icon CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/adminpro-custon-icon.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/meanmenu.min.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/jquery.mCustomScrollbar.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <!-- form CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/form.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body class="darklayout">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div class="wrapper wrapper-full-page">
    <div class="full-page login-page" data-color="black" data-image="./assets/images/devices.jpg">
        <!--<div class="content-inner-all"> -->

        <div class="content">
            <div class="container">
                <div class="row">

                    <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3">
                        <div class="login-form-area">
                            <div class="container-fluid">
                                <div class="row">
                                    <form class="adminpro-form" action="#" id="loginForm" method="post">
                                        <div class="">
                                            <div class="login-bg">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="logo">
                                                            <a href="#"><img src="assets/images/logo.png" width="200px"
                                                                             alt=""/>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="login-title">
                                                            <h1>Login Form</h1>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="login-input-head">
                                                            <p>Username</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="login-input-area">
                                                            <input type="text" name="username"/>
                                                            <i class="fa fa-user login-user" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="login-input-head">
                                                            <p>Password</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="login-input-area">
                                                            <input type="password" name="password"/>
                                                            <i class="fa fa-lock login-user"></i>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4">

                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="login-button-pro">
                                                            <button type="submit" class="login-button login-button-lg">
                                                                Log in
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>


<?php

require_once 'footer.php';

?>


