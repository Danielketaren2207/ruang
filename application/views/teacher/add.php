<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('teacher') ?>">Guru</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('teacher/add'); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Nama Guru</label>
                    <input type="text" name="name" class="form-control" placeholder="Nama Guru" value="<?= set_value('name') ?>">
                    <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" class="form-control" placeholder="Nomor Induk Pegawai" value="<?= set_value('nip') ?>">
                    <?= form_error('nip', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" placeholder="contoh@gmail.com" value="<?= set_value('email') ?>">
                    <?= form_error('email', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="phone" class="form-control" placeholder="Nomor Telepon" value="<?= set_value('phone') ?>">
                    <?= form_error('phone', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('teacher') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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