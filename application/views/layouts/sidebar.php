<?php $uri1 = $this->uri->segment(1); ?>
<?php $uri2 = $this->uri->segment(2); ?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?=base_url();?>"><?= $setting->name ?></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?=base_url();?>">CAT</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Menu Utama</li>
      <li class="<?= ($uri1=='dashboard')?'active':''; ?>">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">
          <i class="fas fa-tachometer-alt"></i> <span>Beranda</span>
        </a>
      </li>

      <?php if(is_superadmin()||is_admin()) : ?>
        <li class="<?= ($uri1=='major')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('major') ?>">
            <i class="fas fa-bookmark"></i> <span>Jurusan</span>
          </a>
        </li>
        <li class="<?= ($uri1=='classroom')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('classroom') ?>">
            <i class="fas fa-columns"></i> <span>Kelas</span>
          </a>
        </li>
        <li class="<?= ($uri1=='lesson')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('lesson') ?>">
            <i class="fas fa-book"></i> <span>Mata Pelajaran</span>
          </a>
        </li>
        <li class="<?= ($uri1=='teacher')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('teacher') ?>">
            <i class="fas fa-user-tie"></i> <span>Guru</span>
          </a>
        </li>
        <li class="<?= ($uri1=='student')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('student') ?>">
            <i class="fas fa-user-graduate"></i> <span>Siswa</span>
          </a>
        </li>
        <li class="dropdown <?= ($uri1=='relation')?'active':''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-arrows-alt"></i><span>Relasi</span></a>
          <ul class="dropdown-menu">
            <li class="<?= ($uri2=='classteacher')?'active':''; ?>"><a class="nav-link" href="<?= base_url('relation/classteacher') ?>">Guru ~ Kelas</a></li>
            <li class="<?= ($uri2=='lessonteacher')?'active':''; ?>"><a class="nav-link" href="<?= base_url('relation/lessonteacher') ?>">Guru ~ Mata Pelajaran</a></li>
            <li class="<?= ($uri2=='majorlesson')?'active':''; ?>"><a class="nav-link" href="<?= base_url('relation/majorlesson') ?>">Mata Pelajaran ~ Jurusan</a></li>
          </ul>
        </li>
      <?php endif; ?>

      <?php if(is_superadmin()||is_admin()||is_teacher()) : ?>
        <li class="<?= ($uri1=='question')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('question') ?>">
            <i class="fas fa-book-open"></i> <span>Soal</span>
          </a>
        </li>
      <?php endif; ?>

      <li class="<?= ($uri1=='exam')?'active':''; ?>">
        <a class="nav-link" href="<?= base_url('exam') ?>">
          <i class="fas fa-cube"></i> <span>Ujian</span>
        </a>
      </li>

      <?php if(is_superadmin()||is_admin()||is_teacher()) : ?>
        <li class="<?= ($uri1=='examresult')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('examresult') ?>">
            <i class="fas fa-book-open"></i> <span>Hasil Ujian</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if(is_superadmin()) : ?>
        <li class="menu-header">Menu Admin</li>
        <li class="<?= ($uri1=='user')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('user') ?>">
            <i class="fas fa-users"></i> <span>Pengguna</span>
          </a>
        </li>
        <li class="<?= ($uri1=='usertype')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('usertype') ?>">
            <i class="fas fa-user-friends"></i> <span>Grup Pengguna</span>
          </a>
        </li>
        <li class="<?= ($uri1=='setting')?'active':''; ?>">
          <a class="nav-link" href="<?= base_url('setting') ?>">
            <i class="fas fa-cogs"></i> <span>Pengaturan</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </aside>
</div>