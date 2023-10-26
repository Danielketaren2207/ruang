<?php
$setting = $this->db->get_where('tbl_setting', ['id'=>1])->row();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="shorcut icon" href="<?= base_url('uploads/setting/'.$setting->logo) ?>">
  <title><?= $title ?> | <?= $setting->name ?></title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/summernote/summernote-bs4.css">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?=base_url();?>assets/css/style.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/css/components.css">
<!-- Start GA -->
<script src="<?=base_url();?>assets/modules/jquery.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA -->
<style>
  .dropdown-toggle::after {
    display: none;
  }
  .navbar-bg {
    height: 70px !important;
  }
  .main-content {
    padding-top: 90px !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
  }
  .section-header {
    margin-bottom: 15px !important;
  }
  .main-navbar {
    top: 0 !important;
  }
  @media (max-width: 575px) {
    .navbar-brand {
      font-size: 1rem;
    }
  }
</style>
</head>

<body class="layout-3">
  <div id="app" class="container">
    <div class="main-wrapper">
      <div class="navbar-bg shadow"></div>
      <nav class="navbar navbar-expand-lg main-navbar container">
        <a href="#" class="navbar-brand"><?= $setting->name ?></a>
        <ul class="navbar-nav navbar-right ml-auto">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-user" style="padding-right:0 !important">
            <img alt="image" src="<?=base_url();?>assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-inline-block"><?= $this->session->name ?></div></a>
          </li>
        </ul>
      </nav>