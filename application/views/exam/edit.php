<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('exam') ?>">Ujian</a></div>
        <div class="breadcrumb-item">Ubah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('exam/edit/'.sha1($exam->id)); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Guru</label>
                    <input type="hidden" id="id_teacher" name="id_teacher" class="form-control" value="<?= $exam->id_teacher ?>">
                    <input type="text" class="form-control" value="<?= $exam->teacher ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="hidden" id="id_lesson" name="id_lesson" class="form-control" value="<?= $exam->id_lesson ?>">
                    <input type="text" class="form-control" value="<?= $exam->lesson ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label>Nama Ujian</label>
                    <?php $name_val = (!empty(set_value('name'))) ? set_value('name') : $exam->name; ?>
                    <input type="text" name="name" class="form-control" placeholder="Nama Ujian" value="<?= $name_val ?>">
                    <?= form_error('name', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Jumlah Soal</label>
                    <?php $noq_val = (!empty(set_value('noq'))) ? set_value('noq') : $exam->noq; ?>
                    <input type="text" name="noq" class="form-control" onblur="checkMaxQuestion(this)" placeholder="0" value="<?= $noq_val ?>">
                    <?= form_error('noq', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <label>Waktu (Menit)</label>
                    <?php $time_val = (!empty(set_value('time'))) ? set_value('time') : $exam->time; ?>
                    <input type="text" name="time" class="form-control" placeholder="0 Menit" value="<?= $time_val ?>">
                    <?= form_error('time', '<small class="text-danger">','</small>'); ?>
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
                          <?php $date_start_val = (!empty(set_value('date_start'))) ? set_value('date_start') : date('Y-m-d', strtotime($exam->date_start)); ?>
                          <input type="text" name="date_start" class="form-control datepicker" value="<?= $date_start_val ?>">
                        </div>
                        <?= form_error('date_start', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Waktu Mulai</label>
                        <?php $time_start_val = (!empty(set_value('time_start'))) ? set_value('time_start') : date('H:i:s', strtotime($exam->date_start)); ?>
                        <input type="time" name="time_start" class="form-control" value="<?= $time_start_val ?>">
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
                          <?php $date_end_val = (!empty(set_value('date_end'))) ? set_value('date_end') : date('Y-m-d', strtotime($exam->date_end)); ?>
                          <input type="text" name="date_end" class="form-control datepicker" value="<?= $date_end_val ?>">
                        </div>
                        <?= form_error('date_end', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Waktu Selesai</label>
                        <?php $time_end_val = (!empty(set_value('time_end'))) ? set_value('time_end') : date('H:i:s', strtotime($exam->date_end)); ?>
                        <input type="time" name="time_end" class="form-control" value="<?= $time_end_val ?>">
                        <?= form_error('time_end', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Jenis Soal</label>
                    <?php $type_val = (!empty(set_value('type'))) ? set_value('type') : $exam->type; ?>
                    <select class="form-control select2" name="type" style="width:100%">
                      <option value="">-- Pilih Jenis Soal --</option>
                      <option <?= ($type_val==0)?'selected':''; ?> value="0">Urut Soal</option>
                      <option <?= ($type_val==1)?'selected':''; ?> value="1">Acak Soal</option>
                    </select>
                    <?= form_error('type', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <a href="<?= site_url('exam') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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

<script type="text/javascript">
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