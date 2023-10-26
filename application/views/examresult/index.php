<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><i class="fas fa-tachometer-alt mr-2"></i> &nbsp; Beranda</a></div>
        <!-- <div class="breadcrumb-item"><a href="#"><?= $title ?></a></div> -->
        <div class="breadcrumb-item"><?= $title ?></div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <div class="btn-group">
              <a href="javascript:void(0)" class="btn btn-warning" onclick="reload_table()"><i class="fas fa-bullseye"></i> Reload</a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="myTable" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead>
                  <tr class="bg-light">
                    <th width="1%">No.</th>
                    <th>Nama Ujian</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Jumlah Soal</th>
                    <th>Waktu</th>
                    <th>Tanggal Mulai</th>
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
  table = $('#myTable').DataTable({ 
    "responsive": false,
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": "<?= site_url('examresult/ajax_list') ?>",
      "type": "POST"
    },

    //Set column definition initialisation properties.
    "columnDefs": [{ 
      "targets": [0,1,2,3,4,5,6,7], //last column
      "orderable": false, //set not orderable
    },
    {
      "targets": [0,4,7],
      "className": "text-center"
    }],
  });
});
</script>