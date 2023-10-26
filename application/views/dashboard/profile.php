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
          <?= form_open_multipart('profile'); ?>
            <div class="card-body">
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-5">
                    <?php if ($user->photo == '') { ?>
                      <img src="<?= site_url('uploads/user/avatar.png'); ?>" alt="User-Photo" width="100%" class="img-thumbnail mb-3">
                    <?php } else { ?>
                      <img src="<?= site_url('uploads/user/'.$user->photo); ?>" alt="User-Photo" width="100%" class="img-thumbnail mb-3">
                    <?php } ?>
                  </div>

                  <div class="col-sm-7">
                    <div class="form-group">
                      <label>Nama Lengkap</label>
                      <input type="hidden" name="id_user" value="<?= sha1($user->id_user) ?>">
                      <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="<?= $user->name ?>">
                      <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Nama Pengguna</label>
                      <input type="text" class="form-control" name="username" placeholder="Nama Pengguna" value="<?= $user->username ?>" readonly>
                      <?= form_error('username', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="text" class="form-control" name="email" placeholder="Alamat Email" value="<?= $user->email ?>">
                      <?= form_error('email', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Telepon</label>
                      <input type="number" class="form-control" name="phone" placeholder="Nomor Telepon" value="<?= $user->phone ?>">
                      <?= form_error('phone', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label for="photo">Foto</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="photo" name="photo" onchange="photoPreview(this,'preview')">
                        <label class="custom-file-label">Pilih file</label>
                      </div>
                      <small class="text-muted">Ukuran maksimal 2MB</small>
                    </div>
                    <b>Pratinjau Foto</b><br>
                    <img id="preview" width="350px" class="mb-3">
                    <br>
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
  function photoPreview(userfile,idpreview)
  {
    var gb = userfile.files;
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