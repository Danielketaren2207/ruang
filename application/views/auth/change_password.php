<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('major') ?>">Jurusan</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('password/change'); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Kata Sandi Saat Ini</label>
                    <input type="password" class="form-control" name="current_password" placeholder="Kata Sandi" value="<?= set_value('current_password'); ?>">
                    <?= form_error('current_password', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Kata Sandi Baru</label>
                    <input type="password" class="form-control" name="new_password" placeholder="Kata Sandi" value="<?= set_value('new_password'); ?>">
                    <?= form_error('new_password', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Ulangi Kata Sandi Baru</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Kata Sandi">
                    <?= form_error('confirm_password', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('dashboard') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
                </div>
              </div>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>