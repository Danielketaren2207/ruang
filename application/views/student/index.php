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
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="btn-group">
              <a href="<?= site_url('student/add') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
              <a href="javascript:void(0)" class="btn btn-warning" onclick="reload_table()"><i class="fas fa-bullseye"></i> Reload</a>
              <a href="javascript:void(0)" class="btn btn-info" onclick="modal_import()"><i class="fas fa-upload"></i> Import</a>
              <a href="<?= site_url('student/export_excel') ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a>
              <a href="javascript:void(0)" class="btn btn-danger" onclick="bulk_delete()"><i class="fas fa-trash"></i> Hapus</a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="myTable" class="table table-striped" style="width:100%">
                <thead>                                 
                  <tr class="bg-light">
                    <th><input type="checkbox" id="check-all"></th>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
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

<!-- Modal Import -->
<div class="modal fade" id="modal_import" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" id="form_import">
                    <div class="alert alert-primary" role="alert">
                      Silahkan download format data excelnya 
                      <a href="<?= site_url('student/download_format'); ?>"><b>disini</b></a>
                    </div>
                    <input type="file" name="import" class="form-control-file" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-icon icon-left btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <a href="javascript:void(0)" id="btnImport" onclick="import_excel()" class="btn btn-icon icon-left btn-primary">Proses</a>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Import -->

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
      "url": "<?= site_url('student/ajax_list')?>",
      "type": "POST"
    },

    //Set column definition initialisation properties.
    "columnDefs": [{ 
      "targets": [0,1,2,3,4,5,6,7,8], //last column
      "orderable": false, //set not orderable
    },
    {
      "targets": [0,1,8],
      "className": "text-center"
    }],
  });
 
  // Check all
  $("#check-all").click(function(){
    $(".data-check").prop('checked', $(this).prop('checked'));
  });
});
 
function delete_data(id)
{
  swal({
    title: 'Apakah anda yakin?',
    text: 'Setelah dihapus, data tidak bisa dikembalikan!',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      // ajax delete data to database
      $.ajax({
        url : "<?= site_url('student/ajax_delete')?>/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          message('success','Berhasil!','Data telah dihapus!');
          $('#modal_form').modal('hide');
          reload_table();
        },
        error: function(){
          message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi!');
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
          url: "<?= site_url('student/ajax_bulk_delete')?>",
          type: "POST",
          data: {id_student:list_id},
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
 
function activated(id,name)
{
  swal({
    title: 'Aktifkan akun ini?',
    text: 'Data NIS akan dijadikan sebagai kata sandi akun.',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      // ajax delete data to database
      $.ajax({
        url : "<?= site_url('student/activated')?>/"+id,
        type: "POST",
        dataType: "JSON",
        success: function(data){
          message('success','Berhasil!','Akun '+name+' telah diaktifkan.');
          $('#modal_form').modal('hide');
          reload_table();
        },
        error: function(){
          message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi!');
        }
      });
    }
  });
}

function modal_import()
{
  $('#form_import')[0].reset(); // reset form on modals
  $('#modal_import').modal('show'); // show bootstrap modal
}

function import_excel()
{
  $('#btnImport').text('Sedang diproses...'); //change button text
  $('#btnImport').attr('disabled',true); //set button disable

  // ajax adding data to database
  var formImport = new FormData($('#form_import')[0]);
  $.ajax({
    url : "<?= site_url('student/import_excel')?>",
    type: "POST",
    data: formImport,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function(data){
      if(data.status){ //if success close modal and reload ajax table
        message('success','Berhasil!','Data telah ditambahkan!');
        $('#modal_import').modal('hide');
        reload_table();
      }

      $('#btnImport').text('Proses'); //change button text
      $('#btnImport').attr('disabled',false); //set button enable 
    },
    error: function(){
      message('error','Gagal!','Terjadi kesalahan, cobalah beberapa saat lagi!');
      $('#btnImport').text('Proses'); //change button text
      $('#btnImport').attr('disabled',false); //set button enable 
    }
  });
}
</script>