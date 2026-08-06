<?php
if(!isset($_COOKIE['mode'])){
    setcookie('mode', 'dark', time() + (86400 * 30), "/");
	header('refresh:3;url='.$url.'dashboard');
}

if(isset($_POST['changeMode'])){
    if($_COOKIE['mode'] == 'dark'){
        setcookie('mode', 'light', time() + (86400 * 30), "/");
    } else {
        setcookie('mode', 'dark', time() + (86400 * 30), "/");
    }
	header('refresh:3;url='.$url.'dashboard');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
   
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<link rel="icon" href="<?= htmlspecialchars($helper->getFaviconUrl()); ?>">
    <title><?= htmlspecialchars($helper->getDisplayName()); ?> | <?= htmlspecialchars($currPageName); ?></title>

    
<style>
ul.home-timeline {
    list-style-type: none;
    position: relative;
}
ul.home-timeline:before {
    content: ' ';
    background: #0074ff;
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
    z-index: 400;
}
ul.home-timeline > li {
    margin: 20px 0;
    padding-left: 20px;
}
ul.home-timeline > li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #22c0e8;
    left: 20px;
    width: 20px;
    height: 20px;
    z-index: 400;
}
</style>	
	
	
<link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/plugins/summernote/summernote-bs4.css">

    <?php if($_COOKIE['mode'] == 'dark'){ ?>
        <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/style-dark.css">
    <?php } else { ?>
        <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/style.css">
    <?php } ?>

  <!-- Chat CSS -->
<link href="<?= $helper->cdnUrl(); ?>/BACKEND/chat.css" rel="stylesheet">

  
  
  
  <!-- Google Font: Plus Jakarta Sans via teaspace-admin.css -->
  <link rel="stylesheet" href="<?= $helper->cdnUrl(); ?>/BACKEND/teaspace-admin.css">
	

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/34a9171ac2.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha256-321PxS+POvbvWcIVoRZeRmf32q7fTFQJ21bXwTNWREY=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" integrity="sha256-f6fW47QDm1m01HIep+UjpCpNwLVkBYKd+fhpb4VQ+gE=" crossorigin="anonymous" />

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">