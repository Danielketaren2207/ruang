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
        <div class="breadcrumb-item">Detail</div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <div class="btn-group">
              <a href="<?= site_url('examresult') ?>" class="btn btn-dark"><i class="fas fa-angle-left mr-2"></i>Kembali</a>
              <a href="javascript:void(0)" class="btn btn-warning" onclick="reload_table()"><i class="fas fa-bullseye"></i> Reload</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-top" style="width:100%">
                  <tr>
                    <td width="40%"><b>Nama Ujian</b></td>
                    <td><?= $exam->exam_name ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Jumlah Soal</b></td>
                    <td><?= $exam->noq ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Waktu</b></td>
                    <td><?= $exam->time ?> Menit</td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Tanggal Mulai</b></td>
                    <?php
                    $date_start = date('Y-m-d', strtotime($exam->exam_date_start));
                    $time_start = date('H:i', strtotime($exam->exam_date_start));
                    ?>
                    <td><?= date_indo($date_start).' '.$time_start ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Tanggal Selesai</b></td>
                    <?php
                    $date_end = date('Y-m-d', strtotime($exam->exam_date_end));
                    $time_end = date('H:i', strtotime($exam->exam_date_end));
                    ?>
                    <td><?= date_indo($date_end).' '.$time_end ?></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-top" style="width:100%">
                  <tr>
                    <td width="40%"><b>Mata Pelajaran</b></td>
                    <td><?= $exam->lesson ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Guru</b></td>
                    <td><?= $exam->teacher ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Nilai Tertinggi</b></td>
                    <td><?= $exam->max_value ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Nilai Terendah</b></td>
                    <td><?= $exam->min_value ?></td>
                  </tr>
                  <tr>
                    <td width="40%"><b>Nilai Rata-Rata</b></td>
                    <td><?= $exam->avg_value ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="table-responsive mt-3">
              <table id="myTable" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead>
                  <tr class="bg-light">
                    <th width="1%">No.</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Jumlah Benar</th>
                    <th>Nilai</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
$(document).ready(function() {
  //datatables
  var id = '<?= $this->uri->segment(3) ?>';
  table = $('#myTable').DataTable({ 
    "responsive": true,
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": "<?= site_url('examresult/ajax_list_detail') ?>",
      "type": "POST",
      "data": {id:id}
    },

    //Set column definition initialisation properties.
    "columnDefs": [{ 
      "targets": [0,1,2,3,4,5,6], //last column
      "orderable": false, //set not orderable
    },
    {
      "targets": [0,4,5,6],
      "className": "text-center"
    }],
  });
});
 
function reset_data(id_exam, id_student)
{
  swal({
    title: 'Apakah anda yakin?',
    text: 'Data ujian siswa ini akan direset dan siswa dapat mengikuti ujian ulang.',
    icon: 'warning',
    buttons: true,
    dangerMode:true
  })
  .then((willDelete) => {
    if (willDelete) {
      // ajax delete data to database
      $.ajax({
        url : "<?= site_url('examresult/reset')?>/"+id_exam+"/"+id_student,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          message('success','Berhasil!','Data ujian telah direset.');
          reload_table();
        },
        error: function(){
          message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi.');
        }
      });
    }
  });
}
</script>