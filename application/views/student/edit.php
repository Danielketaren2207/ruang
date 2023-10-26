<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('student') ?>">Siswa</a></div>
        <div class="breadcrumb-item">Ubah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('student/edit/'.sha1($student->id)); ?>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama Siswa</label>
                    <?php $name_val = (!empty(set_value('name'))) ? set_value('name') : $student->name; ?>
                    <input type="text" name="name" class="form-control" placeholder="Nama Siswa" value="<?= $name_val ?>">
                    <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <?php $email_val = (!empty(set_value('email'))) ? set_value('email') : $student->email; ?>
                    <input type="text" name="email" class="form-control" placeholder="contoh@gmail.com" value="<?= $email_val ?>">
                    <?= form_error('email', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Telepon</label>
                    <?php $phone_val = (!empty(set_value('phone'))) ? set_value('phone') : $student->phone; ?>
                    <input type="text" name="phone" class="form-control" placeholder="Nomor Telepon" value="<?= $phone_val ?>">
                    <?= form_error('phone', '<small class="text-danger">','</small>'); ?>
                  </div>
                </div>
                <!-- END Col -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label>NIS</label>
                    <?php $nis_val = (!empty(set_value('nis'))) ? set_value('nis') : $student->nis; ?>
                    <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Pegawai" value="<?= $nis_val ?>">
                    <?= form_error('nis', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Kelas</label>
                    <?php $id_classroom_val = (!empty(set_value('id_classroom'))) ? set_value('id_classroom') : $student->id_classroom; ?>
                    <select class="form-control select2" name="id_classroom" style="width:100%">
                      <option value="">-- Pilih Kelas --</option>
                      <?php foreach($classroom as $row) : ?>
                        <option <?= ($id_classroom_val==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_classroom', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Jurusan</label>
                    <?php $id_major_val = (!empty(set_value('id_major'))) ? set_value('id_major') : $student->id_major; ?>
                    <select class="form-control select2" name="id_major" style="width:100%">
                      <option value="">-- Pilih Jurusan --</option>
                      <?php foreach($major as $row) : ?>
                        <option <?= ($id_major_val==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('id_major', '<small class="text-danger">','</small>'); ?>
                  </div>
                </div>
              </div>
              <!-- END Row -->
              <div class="row">
                <div class="col">
                  <a href="<?= site_url('student') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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