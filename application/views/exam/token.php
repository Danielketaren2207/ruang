<?php
$setting = $this->db->get_where('tbl_setting', ['id'=>1])->row();
?>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Beranda</a></div>
        <div class="breadcrumb-item"><a href="<?= site_url('exam') ?>">Ujian</a></div>
        <div class="breadcrumb-item">Token</div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col">
          <div class="alert alert-primary alert-has-icon">
            <div class="alert-icon"><i class="fas fa-lightbulb"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Peraturan Ujian</div>
              <?= $setting->exam_rules ?>
            </div>
          </div>
        </div>
      </div>
      <!-- END Row -->
      <div class="row">
        <div class="col-lg-6">
          <div class="card card-primary">
            <div class="card-header">
              <h4>Detail Data Siswa</h4>
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered" style="width:100%">
                <tr>
                  <th width="200px">NIS</th>
                  <td><?= $student->nis ?></td>
                </tr>
                <tr>
                  <th width="200px">Nama</th>
                  <td><?= $student->name ?></td>
                </tr>
                <tr>
                  <th width="200px">Guru</th>
                  <td><?= $exam->teacher ?></td>
                </tr>
                <tr>
                  <th width="200px">Kelas/Jurusan</th>
                  <td><?= $student->classroom.'/'.$student->major ?></td>
                </tr>
                <tr>
                  <th width="200px">Mata Pelajaran</th>
                  <td><?= $exam->lesson ?></td>
                </tr>
                <tr>
                  <th width="200px">Nama Ujian</th>
                  <td><?= $exam->name ?></td>
                </tr>
                <tr>
                  <th width="200px">Jumlah Soal</th>
                  <td><?= $exam->noq ?></td>
                </tr>
                <tr>
                  <th width="200px">Waktu</th>
                  <td><?= $exam->time.' Menit' ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card card-primary">
            <div class="card-body">
              <?php
                $date_now   = time();
                $date_start = strtotime($exam->date_start);
                $date_end   = strtotime($exam->date_end);
              ?>
              <?php if($date_start > $date_now){ ?>
                <div class="alert alert-warning">
                  <p>Ujian akan dimulai setelah tombol "<b>Mulai Ujian</b>" berwarna hijau.</p>
                </div>
                <div class="form-group">
                  <label>Masukkan Token</label>
                  <input type="text" id="token" name="token" class="form-control" autocomplete="off">
                </div>
                <button type="button" class="btn btn-secondary" disabled><i class="fas fa-check mr-2"></i>Mulai Ujian</button>
              <?php }elseif($date_end > $date_now){ ?>
                <div class="alert alert-danger">
                  <p>Batas waktu untuk mengikuti ujian.<br>
                    <span class="countdown" data-time="<?=date('Y-m-d H:i:s', strtotime($exam->date_end))?>">
                      <strong>00 Hari, 00 Jam, 00 Menit, 00 Detik</strong>
                    </span>
                  </p>
                </div>
                <div class="form-group">
                  <label>Masukkan Token</label>
                  <input type="text" id="token" name="token" class="form-control" autocomplete="off">
                </div>
                <button type="button" id="start_exam" class="btn btn-success"><i class="fas fa-check mr-2"></i>Mulai Ujian</button>
              <?php }else{ ?>
                <div class="alert alert-warning">
                  <p><b>Waktu ujian sudah berakhir!</b> <br>
                    Silahkan hubungi guru anda untuk mengikuti ujian susulan. 
                  </p>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  $(document).ready(function(){
    var time = $('.countdown');
    if (time.length) {
        countdown(time.data('time'));
    }

    $('#start_exam').click(function(){
      var key     = '<?= $key ?>';
      var id_exam = '<?= sha1($exam->id) ?>';
      var token   = $('#token').val();

      if(token === ''){
        message('error','Gagal!','Anda belum memasukkan token.');
      }else{
        $.ajax({
          url : "<?= site_url('exam/check_token')?>",
          type: "POST",
          dataType: "JSON",
          data: {key:key,token:token,id_exam:id_exam},
          cache: false,
          success: function(data){
            // console.log(data);
            if(data.status){
              swal({
                title: 'Token Benar!',
                text: 'Ujian akan dimulai saat anda menekan tombol OK.',
                icon: 'success',
                buttons: true,
                dangerMode:true
              })
              .then((willOK) => {
                if (willOK) {
                  location.href = "<?= site_url('exam/test/?key=')?>"+key;
                }
              });
            }else{
              message('error','Gagal!','Token salah.');
            }
          },
          error: function(){
            message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi.');
          }
        });
      }
    });
  });
</script>