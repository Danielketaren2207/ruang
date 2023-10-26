<style>
.table-top td {
  height: 30px !important;
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
        <div class="breadcrumb-item"><a href="<?= site_url('examresult/detail/'.$this->uri->segment(3)) ?>">Detail</a></div>
        <div class="breadcrumb-item">Jawaban</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <div class="btn-group">
              <a href="<?= site_url('examresult/detail/'.$this->uri->segment(3)) ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-top" style="width:100%">
                  <tr>
                    <td width="40%"><b>Nama Siswa</b></td>
                    <td><?= $examresume->student ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Kelas/Jurusan</b></td>
                    <td><?= $examresume->classroom.'/'.$examresume->major ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Nilai</b></td>
                    <td><?= $examresume->total_value ?>/100</td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-top" style="width:100%">
                  <tr>
                    <td width="40%"><b>Nama Ujian</b></td>
                    <td><?= $examresume->exam_name ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Mata Pelajaran</b></td>
                    <td><?= $examresume->lesson ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Guru</b></td>
                    <td><?= $examresume->teacher ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <hr>
            <div class="table-responsive">
              <table id="myTable" class="table table-sm table-hover table-bordered" style="width:100%">
                <tbody>
                  <?php
                    $answer_arr = explode(',', $examresume->list_answer);
                  ?>
                  <?php $no=1; foreach($answer_arr as $value) : ?>
                    <?php
                      $question_exp = explode(':', $value);
                      $id_question = $question_exp[0];
                      $question = $this->Question_model->get_by_id(sha1($id_question));
                      if($question->answer == $question_exp[1]){
                        $answer = '<span class="badge-success p-2">Jawaban Anda: <b>('.$question_exp[1].') Benar</b></span>';
                      }else{
                        $answer = '<span class="badge-danger p-2">Jawaban Anda: <b>('.$question_exp[1].') Salah</b></span>';
                      }
                    ?>
                    <tr>
                      <td>
                        <b>Soal No #<?= $no++; ?></b>
                        <br>
                        <?= $question->question; ?>
                        <p>Kunci Jawaban: <b><?= $question->answer; ?></b></p>
                        <p><?= $answer; ?></p>
                      </td>
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