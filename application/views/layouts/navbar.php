<div class="navbar-bg" style="height:70px"></div>
<nav class="navbar navbar-expand-lg main-navbar">
  <div class="mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  </div>
  <ul class="navbar-nav navbar-right">
    <!-- User Panel -->
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      <?php
      if($users->photo == ''){
        echo '<img alt="image" src="'.base_url('assets/img/avatar/avatar-1.png').'" class="rounded-circle mr-1">';
      }else{
        echo '<img alt="image" src="'.base_url('uploads/user/'.$users->photo).'" class="rounded-circle mr-1">';
      }
      ?>
      <div class="d-inline-block"><?= $this->session->name; ?></div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= site_url('profile') ?>" class="dropdown-item has-icon">
          <i class="fas fa-user"></i> Profil
        </a>
        <a href="<?= site_url('password/change') ?>" class="dropdown-item has-icon">
          <i class="fas fa-lock"></i> Ganti Kata Sandi
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= site_url('logout') ?>" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
      </div>
    </li>
  </ul>
</nav>