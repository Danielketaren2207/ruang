<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('user') ?>">Pengguna</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <?= form_open_multipart('user/add'); ?>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="<?= set_value('name') ?>">
                    <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email Address" value="<?= set_value('email') ?>">
                    <?= form_error('email', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Telepon</label>
                    <input type="number" class="form-control" name="phone" placeholder="Nomor Telepon" value="<?= set_value('phone') ?>">
                    <?= form_error('phone', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label for="photo">Foto</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="photo" name="photo" onchange="photoPreview(this,'preview')" value="<?= set_value('photo') ?>">
                      <label class="custom-file-label">Choose file</label>
                    </div>
                    <small class="text-muted">Maximum file size 1MB</small>
                  </div>
                  <b>Pratinjau Foto</b><br>
                  <img id="preview" width="150px" height="150px"/>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" class="form-control" name="username" placeholder="Nama Pengguna" value="<?= set_value('username') ?>">
                    <?= form_error('username', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" class="form-control" name="password" placeholder="Kata Sandi">
                    <?= form_error('password', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Ulangi Kata Sandi</label>
                    <input type="password" class="form-control" name="password_confirm" placeholder="Ulangi Kata Sandi">
                    <?= form_error('password_confirm', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Grup Pengguna</label>
                    <select class="form-control select2" name="usertype_id" style="width:100%">
                      <option value="">-- Pilih Grup Pengguna --</option>
                      <?php foreach($usertype as $row) : ?>
                        <option <?= (set_value('usertype_id')==$row->id_usertype)?'selected':''; ?> value="<?= $row->id_usertype ?>"><?= $row->usertype_name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('usertype_id', '<small class="text-danger">','</small>'); ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <a href="<?= site_url('user') ?>" class="btn btn-icon icon-left btn-dark"><i class="fas fa-angle-left"></i> Kembali</a>
              <button type="submit" class="btn btn-icon icon-left btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
          <?= form_close(); ?>
        </div>
      </div>
  </section>
</div>

<script type="text/javascript">
  function photoPreview(userfile,idpreview)
  {
    var gb = userfile.files;
    for (var i = 0; i < gb.length; i++)
    {
      var gbPreview = gb[i];
      var imageType = /image.*/;
      var preview=document.getElementById(idpreview);
      var reader = new FileReader();
      if (gbPreview.type.match(imageType))
      {
        //jika tipe data sesuai
        preview.file = gbPreview;
        reader.onload = (function(element)
        {
          return function(e)
          {
            element.src = e.target.result;
          };
        })(preview);
        //membaca data URL gambar
        reader.readAsDataURL(gbPreview);
      }
      else
      {
        //jika tipe data tidak sesuai
        alert("Tipe file tidak sesuai. Gambar harus bertipe .png, .gif atau .jpg.");
      }
    }
  }
</script>