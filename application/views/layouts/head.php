<?php
$users   = $this->User_model->get_by_id(sha1($this->session->id_user));
$setting = $this->db->get_where('tbl_setting', ['id'=>1])->row();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <link rel="shorcut icon" href="<?= base_url('uploads/setting/'.$setting->logo) ?>">
  <title><?=$title;?> | <?= $setting->name ?></title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/datatables/datatables.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/modules/select2/dist/css/select2.min.css">

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
</head>

<style>
  .dataTables_length select.form-control {
    height: calc(1.8125rem + 2px) !important;
  }
  .dataTables_length select.form-control {
    padding: .25rem .5rem !important;
  }
  .note-editable {
    min-height: 50px !important;
  }
</style>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">