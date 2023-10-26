<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('relation/majorlesson') ?>">Mata Pelajaran ~ Jurusan</a></div>
        <div class="breadcrumb-item">Ubah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('relation/majorlesson/edit/'.sha1($lesson->id)); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="hidden" name="id_lesson" class="form-control" value="<?= $lesson->id ?>">
                    <input type="text" class="form-control" value="<?= $lesson->name ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Jurusan</label>
                    <?php
                    $majorArr = [];
                    foreach($majorlesson as $row){
                      $majorArr[] = $row->id_major;
                    }
                    ?>
                    <select class="form-control select2" name="id_major[]" style="width:100%" multiple>
                      <?php foreach($major as $row) : ?>
                        <option <?= (in_array($row->id, $majorArr))?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_major[]', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('relation/majorlesson') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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