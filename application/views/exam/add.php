<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('exam') ?>">Ujian</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('exam/add'); ?>
              <div class="row">
                <div class="col">
                  <?php if(is_superadmin()||is_admin()) : ?>
                    <div class="form-group">
                      <label>Guru</label>
                      <select class="form-control select2" id="id_teacher" name="id_teacher" style="width:100%">
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach($teacher as $row) : ?>
                          <option <?= (set_value('id_teacher')==$row->id)?'selected':''; ?> value="<?= $row->id ?>"><?= $row->name ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?= form_error('id_teacher', '<small class="text-danger">','</small>'); ?>
                    </div>
                    <div class="form-group">
                      <label>Mata Pelajaran</label>
                      <select class="form-control select2" id="id_lesson" name="id_lesson" style="width:100%">
                      </select>
                      <?= form_error('id_lesson', '<small class="text-danger">','</small>'); ?>
                    </div>
                  <?php endif; ?>
                  <?php if(is_teacher()) : ?>
                    <div class="form-group">
                      <label>Guru</label>
                      <input type="hidden" id="id_teacher" name="id_teacher" class="form-control" value="<?= $teacher->id ?>">
                      <input type="text" class="form-control" value="<?= $teacher->name ?>" readonly>
                    </div>
                    <div class="form-group">
                      <label>Mata Pelajaran</label>
                      <select class="form-control select2" id="id_lesson" name="id_lesson" style="width:100%">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php foreach($lesson as $row) : ?>
                          <option <?= (set_value('id_lesson')==$row->id_lesson)?'selected':''; ?> value="<?= $row->id_lesson ?>"><?= $row->lesson ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?= form_error('id_lesson', '<small class="text-danger">','</small>'); ?>
                    </div>
                  <?php endif; ?>
                  <div class="form-group">
                    <label>Nama Ujian</label>
                    <input type="text" name="name" class="form-control" placeholder="Nama Ujian" value="<?= set_value('name') ?>">
                    <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Jumlah Soal</label>
                        <input type="text" name="noq" class="form-control" onblur="checkMaxQuestion(this)" placeholder="0" value="<?= set_value('noq') ?>">
                        <?= form_error('noq', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Waktu (Menit)</label>
                        <input type="text" name="time" class="form-control" placeholder="0 Menit" value="<?= set_value('time') ?>">
                        <?= form_error('time', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-calendar"></i>
                            </div>
                          </div>
                          <input type="text" name="date_start" class="form-control datepicker" value="<?= set_value('date_start') ?>">
                        </div>
                        <?= form_error('date_start', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Waktu Mulai</label>
                        <input type="time" name="time_start" class="form-control" value="<?= set_value('time_start') ?>">
                        <?= form_error('time_start', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-calendar"></i>
                            </div>
                          </div>
                          <input type="text" name="date_end" class="form-control datepicker" value="<?= set_value('date_end') ?>">
                        </div>
                        <?= form_error('date_end', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Waktu Selesai</label>
                        <input type="time" name="time_end" class="form-control" value="<?= set_value('time_end') ?>">
                        <?= form_error('time_end', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Jenis Soal</label>
                    <select class="form-control select2" name="type" style="width:100%">
                      <option value="">-- Pilih Jenis Soal --</option>
                      <option <?= (set_value('type')==0)?'selected':''; ?> value="0">Urut Soal</option>
                      <option <?= (set_value('type')==1)?'selected':''; ?> value="1">Acak Soal</option>
                    </select>
                    <?= form_error('type', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('exam') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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

<script type="text/javascript">
  $(document).ready(function(){
    $('#id_teacher').change(function(){
      var id_teacher = $(this).val();
      $.ajax({
        url : "<?= site_url('exam/get_lesson_by_teacher'); ?>",
        method : "POST",
        dataType : "JSON",
        data : {id_teacher:id_teacher},
        success : function(data){
          var html = '<option value="">-- Pilih Mata Pelajaran --</option>';
          var i;
          for(i=0; i<data.length; i++){
            html += '<option value="'+data[i].id_lesson+'">'+data[i].lesson+'</option>';
          }
          $('#id_lesson').html(html);
        }
      });
    });
  });

  function checkMaxQuestion(obj)
  {
    var noq        = $(obj).val();
    var id_teacher = $('#id_teacher').val();
    var id_lesson  = $('#id_lesson').val();
    $.ajax({
      url : "<?= site_url('exam/check_max_question'); ?>",
      method : "POST",
      dataType : "JSON",
      data : {
        id_teacher:id_teacher,
        id_lesson:id_lesson
      },
      success : function(data){
        if(noq > data){
          $(obj).val(data);
          $(obj).after('<small class="text-danger">Hanya '+data+' soal yang tersedia.</small>');
        }
      }
    });
  }
</script>