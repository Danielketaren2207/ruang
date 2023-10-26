<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('question') ?>">Soal</a></div>
        <div class="breadcrumb-item">Tambah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('question/add'); ?>
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
                      <input type="hidden" name="id_teacher" class="form-control" value="<?= $teacher->id ?>">
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
                    <label>Soal</label>
                    <textarea name="question" class="summernote"><?= set_value('question') ?></textarea>
                    <?= form_error('question', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <?php foreach($option as $key => $val) : ?>
                    <div class="form-group">
                      <label>Jawaban <?= $val ?></label>
                      <textarea name="option_<?= $key ?>" class="summernote-simple"><?= set_value('option_'.$key) ?></textarea>
                      <?= form_error('option_'.$key, '<small class="text-danger">','</small>'); ?>
                    </div>
                  <?php endforeach; ?>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Kunci Jawaban</label>
                        <select class="form-control select2" id="answer" name="answer" style="width:100%">
                          <option value="">-- Pilih Kunci Jawaban --</option>
                          <?php foreach($option as $key => $val) : ?>
                            <option <?= (set_value('answer')==$val)?'selected':''; ?> value="<?= $val ?>"><?= $val ?></option>
                          <?php endforeach; ?>
                        </select>
                        <?= form_error('answer', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Bobot Nilai</label>
                        <input type="text" name="value" class="form-control" placeholder="0" value="<?= set_value('value') ?>">
                        <?= form_error('value', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                  </div>
                  <a href="<?= site_url('question') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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

<?php if(is_superadmin()||is_admin()) : ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#id_teacher').change(function(){
      var id_teacher = $(this).val();
      $.ajax({
        url : "<?= site_url('question/get_lesson_by_teacher'); ?>",
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
</script>
<?php endif; ?>