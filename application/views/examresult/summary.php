<style>
.table-top td {
  height: 30px !important;
}
#myTable .bg-light td {
  font-weight: bold;
  vertical-align: middle !important;
}
</style>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('examresult') ?>">Hasil Ujian</a></div>
        <div class="breadcrumb-item">Analisis</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <div class="btn-group">
              <a href="<?= site_url('examresult') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
              <a href="<?= site_url('examresult/export_summary/'.sha1($examresult[0]->id_exam)) ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive mt-3">
              <table id="myTable" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                <thead>
                  <tr class="bg-light">
                    <td width="1%" rowspan="2" align="center">No.</td>
                    <td rowspan="2">Nama Siswa</td>
                    <td colspan="12"><center>Soal</center></td>
                    <td rowspan="2" align="center">Nilai</td>
                  </tr>
                  <tr class="bg-light">
                    <?php for($i=1; $i<=$examresult[0]->noq; $i++) : ?>
                      <td align="center"><?= $i; ?></td>
                    <?php endfor; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $no=1; foreach($examresult as $row) : ?>
                    <tr>
                      <td align="center"><?= $no++; ?></td>
                      <td><?= $row->student; ?></td>
                      <?php
                        $answer_arr = explode(',', $row->list_answer);
                      ?>
                      <?php foreach($answer_arr as $value) : ?>
                        <?php
                          $question_exp = explode(':', $value);
                          $id_question = $question_exp[0];
                          $question = $this->Question_model->get_by_id(sha1($id_question));
                          if($question->answer == $question_exp[1]){
                            $answer = '<span class="text-success"><i class="fas fa-check"></i></span>';
                          }else{
                            $answer = '<span class="text-danger"><i class="fas fa-times"></i></span>';
                          }
                        ?>
                        <td align="center"><?= $answer; ?></td>
                      <?php endforeach; ?>
                      <td align="center"><?= $row->total_value; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>