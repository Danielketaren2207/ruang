<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('question') ?>">Soal</a></div>
        <div class="breadcrumb-item">Ubah</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= form_open('question/edit/'.sha1($question->id)); ?>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Guru</label>
                    <input type="text" class="form-control" value="<?= $question->teacher ?>" readonly>
                  </div>
                  <?php if(is_teacher()){ ?>
                    <div class="form-group">
                      <label>Mata Pelajaran</label>
                      <?php $id_lesson_val = (!empty(set_value('id_lesson'))) ? set_value('id_lesson') : $question->id_lesson; ?>
                      <select class="form-control select2" id="id_lesson" name="id_lesson" style="width:100%">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        <?php foreach($lesson as $row) : ?>
                          <option <?= ($id_lesson_val==$row->id_lesson)?'selected':''; ?> value="<?= $row->id_lesson ?>"><?= $row->lesson ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?= form_error('id_lesson', '<small class="text-danger">','</small>'); ?>
                    </div>
                  <?php }else{ ?>
                    <div class="form-group">
                      <label>Mata Pelajaran</label>
                      <input type="text" class="form-control" value="<?= $question->lesson ?>" readonly>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label>Soal</label>
                    <?php $question_val = (!empty(set_value('question'))) ? set_value('question') : $question->question; ?>
                    <textarea name="question" class="summernote"><?= $question_val ?></textarea>
                    <?= form_error('question', '<small class="text-danger">','</small>'); ?>
                  </div>
                  <?php foreach($option as $key => $val) :
                    $opt = 'option_'.$key; 
                  ?>
                    <div class="form-group">
                      <label>Jawaban <?= $val ?></label>
                      <?php $option_val = (!empty(set_value('option_'.$key))) ? set_value('option_'.$key) : $question->$opt; ?>
                      <textarea name="option_<?= $key ?>" class="summernote-simple"><?= $option_val ?></textarea>
                      <?= form_error('option_'.$key, '<small class="text-danger">','</small>'); ?>
                    </div>
                  <?php endforeach; ?>
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>Kunci Jawaban</label>
                        <?php $answer_val = (!empty(set_value('answer'))) ? set_value('answer') : $question->answer; ?>
                        <select class="form-control select2" id="answer" name="answer" style="width:100%">
                          <option value="">-- Pilih Kunci Jawaban --</option>
                          <?php foreach($option as $key => $val) : ?>
                            <option <?= ($answer_val==$val)?'selected':''; ?> value="<?= $val ?>"><?= $val ?></option>
                          <?php endforeach; ?>
                        </select>
                        <?= form_error('answer', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                        <label>Bobot Nilai</label>
                        <?php $value_val = (!empty(set_value('value'))) ? set_value('value') : $question->value; ?>
                        <input type="text" name="value" class="form-control" placeholder="0" value="<?= $value_val ?>">
                        <?= form_error('value', '<small class="text-danger">','</small>'); ?>
                      </div>
                    </div>
                  </div>
                  <a href="<?= site_url('question') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
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