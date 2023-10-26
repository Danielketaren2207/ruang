<?php
$setting = $this->db->get_where('tbl_setting', ['id'=>1])->row();
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <!-- <div class="breadcrumb-item"><a href="<?= site_url() ?>">Jurusan</a></div> -->
        <div class="breadcrumb-item">Profil</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <?= form_open_multipart('setting'); ?>
            <div class="card-body">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-5">
                    <?php if ($setting->logo == '') { ?>
                      <img src="<?= site_url('uploads/setting/p-250.png'); ?>" alt="User-Photo" width="100%" class="img-thumbnail mb-3">
                    <?php } else { ?>
                      <img src="<?= site_url('uploads/setting/'.$setting->logo); ?>" alt="User-Photo" width="100%" class="img-thumbnail mb-3">
                    <?php } ?>
                  </div>

                  <div class="col-sm-7">
                    <div class="form-group">
                      <label>Nama Aplikasi</label>
                      <?php $name_val = (set_value('name')) ? set_value('name') : $setting->name; ?>
                      <input type="text" class="form-control" name="name" placeholder="Nama Aplikasi" value="<?= $name_val ?>">
                      <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Nama Sekolah</label>
                      <?php $name_school_val = (set_value('name_school')) ? set_value('name_school') : $setting->name_school; ?>
                      <input type="text" class="form-control" name="name_school" placeholder="Nama Sekolah" value="<?= $name_school_val ?>">
                      <?= form_error('name_school', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <?php $email_val = (set_value('email')) ? set_value('email') : $setting->email; ?>
                      <input type="text" class="form-control" name="email" placeholder="Alamat Email" value="<?= $email_val ?>">
                      <?= form_error('email', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Telepon</label>
                      <?php $phone_val = (set_value('phone')) ? set_value('phone') : $setting->phone; ?>
                      <input type="text" class="form-control" name="phone" placeholder="Nomor Telepon" value="<?= $phone_val ?>">
                      <?= form_error('phone', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Alamat</label>
                      <?php $address_val = (set_value('address')) ? set_value('address') : $setting->address; ?>
                      <textarea name="address" rows="3" class="form-control"><?= $address_val ?></textarea>
                      <?= form_error('address', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label for="logo">Logo</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo" name="logo" onchange="logoPreview(this,'preview')">
                        <label class="custom-file-label">Pilih file</label>
                      </div>
                      <small class="text-muted">Ukuran maksimal 1MB</small>
                    </div>
                    <b>Pratinjau Logo</b><br>
                    <img id="preview" width="350px" class="mb-3">
                    <br>
                    <div class="form-group">
                      <label>Peraturan Ujian</label>
                      <?php $exam_rules_val = (set_value('exam_rules')) ? set_value('exam_rules') : $setting->exam_rules; ?>
                      <textarea name="exam_rules" class="summernote"><?= $exam_rules_val ?></textarea>
                      <?= form_error('exam_rules', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
                  </div>
                </div>
              </div>
            </div>
          <?= form_close(); ?>
        </div>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  function logoPreview(settingfile,idpreview)
  {
    var gb = settingfile.files;
    for (var i = 0; i < gb.length; i++){
      var gbPreview = gb[i];
      var imageType = /image.*/;
      var preview=document.getElementById(idpreview);
      var reader = new FileReader();
      if (gbPreview.type.match(imageType)){
        //jika tipe data sesuai
        preview.file = gbPreview;
        reader.onload = (function(element){
          return function(e){
            element.src = e.target.result;
          };
        })(preview);
        //membaca data URL gambar
        reader.readAsDataURL(gbPreview);
      }else{
        //jika tipe data tidak sesuai
        alert("Tipe file tidak sesuai. Gambar harus bertipe .png, .gif atau .jpg.");
      }
    }
  }
</script>