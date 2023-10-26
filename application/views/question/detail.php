<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('question') ?>">Soal</a></div>
        <div class="breadcrumb-item">Detail</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card card-outline card-primary">
          <div class="card-body">
            <?= $question->question ?>
            <ol type="A">
              <li><?= $question->option_a ?></li>
              <li><?= $question->option_b ?></li>
              <li><?= $question->option_c ?></li>
              <li><?= $question->option_d ?></li>
              <li><?= $question->option_e ?></li>
            </ol>
          </div>
          <div class="card-footer">
            <a href="<?= site_url('question') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
            <a class="btn btn-warning" href="<?= site_url('question/edit/'.sha1($question->id)) ?>"><i class="fas fa-edit mr-2"></i>Ubah Soal</a>
          </div>
        </div>
      </div>
      <!-- END Col -->
      <div class="col-md-4">
        <div class="alert alert-primary alert-has-icon">
          <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
          <div class="alert-body">
            <div class="alert-title" style="margin-top:2px">Kunci Jawaban (<b><?= $question->answer ?></b>)</div>
            <p>Tanggal Dibuat : <?= longdate_indo(date('Y-m-d', strtotime($question->created_at))) ?></p>
            <p>Diperbaharui : <?= ($question->updated_at===NULL) ? '-' : longdate_indo(date('Y-m-d', strtotime($question->updated_at))); ?></p>
          </div>
        </div>
        <div class="alert alert-primary alert-has-icon">
          <div class="alert-icon"><i class="fas fa-book"></i></div>
          <div class="alert-body">
            <div class="alert-title" style="margin-top:2px">Mata Pelajaran</div>
            <p><?= $question->lesson ?></p>
          </div>
        </div>
        <div class="alert alert-primary alert-has-icon">
          <div class="alert-icon"><i class="fas fa-user-circle"></i></div>
          <div class="alert-body">
            <div class="alert-title" style="margin-top:2px">Guru</div>
            <p><?= $question->teacher ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>