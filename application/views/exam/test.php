<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <table class="table mb-0" style="width:100%">
        <tr>
          <td class="text-center pl-2 pr-2" style="height:0"><b>Jumlah Soal</b></td>
          <td class="text-center pl-2 pr-2 text-success" style="height:0"><b>Soal Dijawab</b></td>
          <td class="text-center pl-2 pr-2 text-danger" style="height:0"><b>Belum Dijawab</b></td>
          <td class="text-center pl-2 pr-2" style="height:0" rowspan="2"><button type="button" class="btn btn-success" onclick="return save_end()">Selesai Ujian</button></td>
        </tr>
        <tr>
          <td class="text-center pl-2 pr-2" style="height:0"><h5 class="mb-0"><?= $total_question ?></h5></td>
          <td class="text-center pl-2 pr-2 text-success" style="height:0"><h5 id="total_answer" class="mb-0"></h5></td>
          <td class="text-center pl-2 pr-2 text-danger" style="height:0"><h5 id="total_not_answer" class="mb-0"></h5></td>
        </tr>
      </table>
    </div>

    <div class="section-body">
      <div class="card card-primary" style="margin-bottom:15px !important">
        <div class="card-header" style="min-height:20px; padding:5px 25px;">
          <h4>Navigasi Soal</h4>
          <div class="card-header-action mt-0" style="width:39px">
            <a data-collapse="#mycard-collapse" class="btn btn-sm btn-icon btn-secondary" href="#"><i class="fas fa-minus"></i></a>
          </div>
        </div>
        <div class="collapse show" id="mycard-collapse">
          <div class="card-body" id="show_answer">
          </div>
        </div>
      </div>
      <!-- END Card -->
      <?= form_open('exam/test_action', array('id'=>'exam'), array('id_test'=>$id_test)); ?>
        <input type="hidden" name="id_exam" value="<?= $id_exam ?>">
        <div class="card card-primary" style="margin-bottom:15px !important">
          <input type="hidden" id="total_question" name="total_question" value="<?= $total_question ?>">
          <div class="card-header d-flex justify-content-between">
            <h4><span class="badge badge-primary">Soal No. <span id="question_to"></span></span></h4>
            <h4 style="padding-right:0"><span class="badge badge-danger">Sisa Waktu <span id="remaining_time" data-time="<?= $detail_test->date_end ?>"></span></span></h4>
          </div>
          <div class="card-body">
            <?= $html_question ?>
          </div>
          <div class="card-footer text-center">
            <button type="button" id="btn-back" class="btn btn-dark" rel="0" onclick="return back()">Sebelumnya</button>
            <button type="button" id="btn-doubtfull" class="btn btn-warning" rel="1" onclick="return not_answer()">Ragu-Ragu</button>
            <button type="button" id="btn-next" class="btn btn-primary" rel="2" onclick="return next()">Selanjutnya</button>
            <button type="button" id="btn-finish" class="btn btn-success" onclick="return save_end()">Selesai Ujian</button>
          </div>
        </div>
      <?= form_close(); ?>
    </div>
  </section>
</div>

<!-- Modal Finish -->
<div class="modal fade" id="modal_finish" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items:center; padding-bottom:25px;">
              <h5 class="modal-title">Waktu Ujian Telah Selesai!</h5>
                <button type="button" class="btn btn-primary" onclick="time_end()">OKE</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Finish -->

<!-- Modal Selesai -->
<div class="modal fade" id="modal_selesai" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items:center; padding-bottom:25px;">
              <h5 class="modal-title">Aktifitas anda mencurigakan, ujian akan berakhir</h5>
                <button type="button" class="btn btn-primary" onclick="time_end()">OKE</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Selesai -->

<!-- Modal Start -->
<div class="modal fade" id="modal_start" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items:center; padding-bottom:25px;">
              <h5 class="modal-title">Selamat Mengerjakan!</h5>
                <button type="button" class="btn btn-primary" onclick="document.documentElement.requestFullscreen();$('#modal_start').modal('hide');">OKE</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Start -->

<!-- Modal Info -->
<div class="modal fade" id="modal_info" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="align-items:center; padding-bottom:25px;">
                <h5 class="modal-title">Apakah anda akan mengakhiri ujian?</h5>
            </div>
            <div class="modal-body">
                Ujian dinyatakan selesai saat anda menekan tombol OKE.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="document.documentElement.requestFullscreen();$('#modal_info').modal('hide');">Batal</button>
                <button type="button" class="btn btn-primary" onclick="document.exitFullscreen();finish();">OKE</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Info -->

<script>
  var base_url = '<?= base_url() ?>';
  var id_test = '<?= $id_test ?>';
  var widget  = $('.step');
  var total_widget = widget.length;
</script>
<script src="<?=base_url();?>assets/js/app/exam/test.js"></script>
<script type = "text/javascript" > history.pushState(null, null); window.addEventListener('popstate', function(event) { history.pushState(null, null); }); </script>
<script>$(document).on("keydown", disableF5); function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };</script>
<script>
  $(document).ready(function(){
    $("#modal_start").modal("show");
    $("body").bind("cut copy", function(e) {
      e.preventDefault();
    });
    $("body").on("contextmenu", function(e) {
      return false;
    });
  });

  function fullscreenchanged(event){
    if(!document.fullscreenElement){
      $("#modal_selesai").modal("show");
    }
  }
  document.addEventListener("fullscreenchange", fullscreenchanged);
</script>