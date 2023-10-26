<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('usertype') ?>">Grup Pengguna</a></div>
        <div class="breadcrumb-item">Ubah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('usertype/edit/'.sha1($usertype->id_usertype)); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Usertype Name</label>
                    <input type="text" name="usertype_name" class="form-control" placeholder="Usertype Name" value="<?= $usertype->usertype_name ?>">
                    <?= form_error('usertype_name', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('usertype') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Ubah</button>
                </div>
              </div>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>