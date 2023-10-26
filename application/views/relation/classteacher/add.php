<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('relation/classteacher') ?>">Guru ~ Kelas</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-5">
        <div class="alert alert-primary alert-has-icon">
          <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
          <div class="alert-body">
            <div class="alert-title">Informasi</div>
            <p>Jika kolom Guru kosong, berikut ini kemungkinan penyebabnya:</p>
            <ul>
              <li>Anda belum menambahkan master data guru (Master guru kosong/belum ada data sama sekali).</li>
              <li>Guru sudah ditambahkan, jadi anda tidak perlu menambahkan lagi. Anda hanya perlu mengedit data kelas gurunya saja.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('relation/classteacher/add'); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Guru</label>
                    <select class="form-control select2" name="id_teacher" style="width:100%">
                      <option value="">-- Pilih Guru --</option>
                      <?php foreach($teacher as $row) : ?>
                        <option <?= (set_value('id_teacher')==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_teacher', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Kelas</label>
                    <select class="form-control select2" name="id_classroom[]" style="width:100%" multiple>
                      <?php foreach($classroom as $row) : ?>
                        <option <?= (set_value('id_classroom[]')==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_classroom[]', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('relation/classteacher') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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