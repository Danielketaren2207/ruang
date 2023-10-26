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
              <?php if(is_student()){ ?>
                <a href="javascript:void(0)" class="btn btn-secondary" onclick="reload_table()"><i class="fas fa-refresh"></i> Reload</a>
              <?php }else{ ?>
                <a href="<?= site_url('exam/add') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                <a href="javascript:void(0)" class="btn btn-warning" onclick="reload_table()"><i class="fas fa-bullseye"></i> Reload</a>
                <a href="javascript:void(0)" class="btn btn-danger" onclick="bulk_delete()"><i class="fas fa-trash"></i> Hapus</a>
              <?php } ?>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="myTable" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead>
                  <tr class="bg-light">
                    <?php if(!is_student()) : ?>
                      <th width="1%"><input type="checkbox" id="check-all"></th>
                    <?php endif; ?>
                    <th width="1%">No.</th>
                    <th>Nama Ujian</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Jumlah Soal</th>
                    <th>Waktu</th>
                    <?php if(!is_student()) : ?>
                      <th>Token</th>
                    <?php endif; ?>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
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
var save_method;
var url;
 
$(document).ready(function() {
  //datatables
  table = $('#myTable').DataTable({ 
      "responsive": false,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.

      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "<?= site_url('exam/ajax_list')?>",
          "type": "POST"
      },

      //Set column definition initialisation properties.
    <?php if(is_student()){ ?>
      "columnDefs": [{
          "targets": [0,1,2,3,4,5,6,7,8], //last column
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,4,8],
          "className": "text-center"
      }],
    <?php }else{ ?>
      "columnDefs": [{
          "targets": [0,1,2,3,4,5,6,7,8,9,10], //last column
          "orderable": false, //set not orderable
      },
      {
          "targets": [0,1,5,7,10],
          "className": "text-center"
      }],
    <?php } ?>
  });
 
  // Check all
  $("#check-all").click(function(){
    $(".data-check").prop('checked', $(this).prop('checked'));
  });
});

function refresh_token(id)
{
  swal({
    title: 'Perbaharui token?',
    text: 'Token saat ini akan kadaluarsa.',
    icon: 'warning',
    buttons: true,
    dangerMode:true
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        url : "<?= site_url('exam/refresh_token')?>/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          message('success','Berhasil!','Token telah diperbaharui.');
          reload_table();
        },
        error: function(){
          message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi.');
        }
      });
    }
  });
}
 
function delete_data(id)
{
  swal({
    title: 'Apakah anda yakin?',
    text: 'Data yang dihapus tidak dapat dikembalikan.',
    icon: 'warning',
    buttons: true,
    dangerMode:true
  })
  .then((willDelete) => {
    if (willDelete) {
      // ajax delete data to database
      $.ajax({
        url : "<?= site_url('exam/ajax_delete')?>/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          message('success','Berhasil!','Data telah dihapus.');
          reload_table();
        },
        error: function(){
          message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi.');
        }
      });
    }
  });
}
 
function bulk_delete()
{
  var list_id = [];
  $(".data-check:checked").each(function(){
      list_id.push(this.value);
  });

  if(list_id.length > 0){
    swal({
      title: 'Apakah anda yakin?',
      text: 'Anda akan menghapus '+list_id.length+' data?',
      icon: 'warning',
      buttons: true,
      dangerMode:true
    })
    .then((willDelete) => {
      if (willDelete){
        // ajax delete data to database
        $.ajax({
          url: "<?= site_url('exam/ajax_bulk_delete')?>",
          type: "POST",
          data: {id_exam:list_id},
          dataType: "JSON",
          success: function(data){
            if(data.status){
              message('success','Berhasil!',+list_id.length+' data telah dihapus.');
              reload_table();
            }else{
              message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi.');
            }
          },
          error: function(){
            message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi.');
          }
        });
      }
    });
  }else{
    message('error','Gagal!','Tidak ada data yang dipilih.');
  }
}
</script>