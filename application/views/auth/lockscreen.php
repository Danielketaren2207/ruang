<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?> | Bahri CMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shorcut icon" href="<?= base_url() ?>assets/backend/dist/img/logo-payroll.png">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/backend/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/backend/plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/backend/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="3">Bahri <b>CMS</b></a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name"><?= $this->session->name; ?></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <?php if ($this->session->photo == NULL) { ?>
        <img src="<?= base_url('assets/backend/uploads/avatar/thumbs/noimage.jpg'); ?>" alt="User-Photo">
      <?php } else { ?>
        <img src="<?= base_url('assets/backend/uploads/avatar/thumbs/'.$this->session->photo); ?>" alt="User-Photo">
      <?php } ?>
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <?= form_open('lockscreen', ['class' => 'lockscreen-credentials']); ?>
      <div class="input-group">
        <input type="hidden" name="username" value="<?= $this->session->username; ?>">
        <input type="password" name="password" class="form-control" placeholder="password" autofocus>
        <div class="input-group-append">
          <button type="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    <?= form_close(); ?>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Enter your password to retrieve your session
  </div>
  <div class="text-center">
    <a href="<?= site_url('login'); ?>">Or sign in as a different user</a>
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; <?= date('Y'); ?> <b><a href="<?= site_url(); ?>" class="text-black">Bahri CMS</a></b><br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="<?= base_url() ?>assets/backend/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url() ?>assets/backend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<script src="<?= base_url() ?>assets/backend/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>assets/backend/dist/js/adminlte.min.js"></script>

<script>
  <?php if($this->session->flashdata('danger')) { ?>
    toastr.error('<?= $this->session->flashdata('danger') ?>')
  <?php } ?>
  <?php if(form_error('password')) { ?>
    toastr.error('<?= form_error('password'); ?>')
  <?php } ?>
  // Auto close alert
  window.setTimeout(function() {
    $(".alert").fadeOut(200, function(){
      $(this).remove(); 
    });
  }, 3000);
</script>

</body>
</html>