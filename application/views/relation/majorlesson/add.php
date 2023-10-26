<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('relation/majorlesson') ?>">Mata Pelajaran ~ Jurusan</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-5">
        <div class="alert alert-primary alert-has-icon">
          <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
          <div class="alert-body">
            <div class="alert-title">Informasi</div>
            <p>Jika kolom Mata Pelajaran kosong, berikut ini kemungkinan penyebabnya:</p>
            <ul>
              <li>Anda belum menambahkan master data pelajaran (Data Mata Pelajaran kosong/belum ada data sama sekali).</li>
              <li>Mata pelajaran sudah ditambahkan, jadi anda tidak perlu menambahkan lagi. Anda hanya perlu mengedit data Jurusan Mata Pelajarannya saja.</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('relation/majorlesson/add'); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <select class="form-control select2" name="id_lesson" style="width:100%">
                      <option value="">-- Pilih Mata Pelajaran --</option>
                      <?php foreach($lesson as $row) : ?>
                        <option <?= (set_value('id_lesson')==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_lesson', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Jurusan</label>
                    <select class="form-control select2" name="id_major[]" style="width:100%" multiple>
                      <?php foreach($major as $row) : ?>
                        <option <?= (set_value('id_major[]')==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_major[]', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('relation/majorlesson') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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