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
              <a href="<?= site_url('question/add') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
              <a href="javascript:void(0)" class="btn btn-warning" onclick="reload_table()"><i class="fas fa-bullseye"></i> Reload</a>
              <a href="javascript:void(0)" class="btn btn-info" onclick="modal_import()"><i class="fas fa-upload"></i> Import</a>
              <a href="javascript:void(0)" class="btn btn-success" onclick="modal_export()"><i class="fas fa-file-excel"></i> Export</a>
              <!-- <a href="<?= site_url('question/export_excel') ?>" class="btn btn-success"><i class="fas fa-file-excel"></i> Export</a> -->
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
                    <th>Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Soal</th>
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
                      <ul class="mb-0" style="padding-inline-start:10px">
                        <li>Silahkan download format data excelnya <a href="<?= site_url('question/download_format'); ?>"><b>disini</b></a>.</li>
                        <li>Kolom ID Guru hanya boleh berisi angka, klik <a href="javascript:void(0)" onclick="modal_teacher()"><b>disini</b></a> untuk melihat ID Guru.</li>
                        <li>Kolom ID Mata Pelajaran hanya boleh berisi angka, klik <a href="javascript:void(0)" onclick="modal_lesson()"><b>disini</b></a> untuk melihat ID Mata Pelajaran.</li>
                      </ul>
                    </div>
                    <input type="file" name="import" class="form-control-file" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-icon icon-left btn-danger" data-dismiss="modal">Batal</button>
                <a href="javascript:void(0)" id="btnImport" onclick="import_excel()" class="btn btn-icon icon-left btn-primary">Proses</a>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Import -->

<!-- Modal Export -->
<div class="modal fade" id="modal_export" role="dialog" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Export Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open('question', ['id'=>'form_export']); ?>
      <div class="modal-body">
        <?php if(is_superadmin()||is_admin()) : ?>
          <div class="alert alert-primary" role="alert">
            Kosongkan data Guru dan Mata Pelajaran jika ingin export semua data.
          </div>
          <div class="form-group">
            <label>Guru</label>
            <select class="form-control select2" id="id_teacher" name="id_teacher" style="width:100%">
              <option value="">-- Pilih Guru --</option>
              <?php foreach($teacher as $row) : ?>
                <option value="<?= $row->id ?>"><?= $row->name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Mata Pelajaran</label>
            <select class="form-control select2" id="id_lesson" name="id_lesson" style="width:100%">
            </select>
            <small id="err_lesson" class="text-danger"></small>
          </div>
        <?php endif; ?>
        <?php if(is_teacher()) : ?>
          <div class="alert alert-primary" role="alert">
            Kosongkan data Mata Pelajaran jika ingin export semua data.
          </div>
          <div class="form-group">
            <label>Guru</label>
            <input type="hidden" id="id_teacher" name="id_teacher" class="form-control" value="<?= $teacher->id ?>">
            <input type="text" class="form-control" value="<?= $teacher->name ?>" readonly>
          </div>
          <div class="form-group">
            <label>Mata Pelajaran</label>
            <select class="form-control select2" id="id_lesson" name="id_lesson" style="width:100%">
              <option value="">-- Pilih Mata Pelajaran --</option>
              <?php foreach($lesson as $row) : ?>
                <option value="<?= $row->id_lesson ?>"><?= $row->lesson ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-icon icon-left btn-danger" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Proses</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>
<!-- End Modal Export -->

<!-- Modal Guru -->
<div class="modal fade" id="modal_teacher" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Data Guru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <table class="table">
                <tr class="bg-light">
                  <th>#</th>
                  <th>ID Guru</th>
                  <th>Nama Guru</th>
                </tr>
                <?php if(is_superadmin()||is_admin()) : ?>
                  <?php $no=1; foreach($teacher as $row) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $row->id ?></td>
                      <td><?= $row->name ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
                <?php if(is_teacher()) : ?>
                  <tr>
                    <td>1</td>
                    <td><?= $teacher->id ?></td>
                    <td><?= $teacher->name ?></td>
                  </tr>
                <?php endif; ?>
              </table>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Guru -->

<!-- Modal Mata Pelajaran -->
<div class="modal fade" id="modal_lesson" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Data Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <table class="table">
                <tr class="bg-light">
                  <th>#</th>
                  <th>ID Mata Pelajaran</th>
                  <th>Nama Mata Pelajaran</th>
                </tr>
                <?php $no=1; foreach($lesson as $row) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= (is_teacher()) ? $row->id_lesson : $row->id ?></td>
                    <td><?= (is_teacher()) ? $row->lesson : $row->name ?></td>
                  </tr>
                <?php endforeach; ?>
              </table>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Mata Pelajaran -->

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
      "url": "<?= site_url('question/ajax_list')?>",
      "type": "POST"
    },

    //Set column definition initialisation properties.
    "columnDefs": [{ 
      "targets": [0,1,2,3,4,5], //last column
      "orderable": false, //set not orderable
    },
    {
      "targets": [0,1,5],
      "className": "text-center"
    }],
  });

  <?php if(is_superadmin()||is_admin()) : ?>
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
  <?php endif; ?>

  $('#id_lesson').change(function(){
    var id_lesson = $(this).val();
    if(id_lesson != ''){
      $('#err_lesson').text('');
    }
  });
 
  // Check all
  $("#check-all").click(function(){
    $(".data-check").prop('checked', $(this).prop('checked'));
  });
});

function modal_teacher()
{
  $('#modal_teacher').modal('show');
}

function modal_lesson()
{
  $('#modal_lesson').modal('show');
}

function modal_import()
{
  $('#form_import')[0].reset();
  $('#modal_import').modal('show');
}

function import_excel()
{
  $('#btnImport').text('Sedang diproses...'); //change button text
  $('#btnImport').attr('disabled',true); //set button disable

  // ajax adding data to database
  var formImport = new FormData($('#form_import')[0]);
  $.ajax({
    url : "<?= site_url('question/import_excel')?>",
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

function modal_export()
{
  $('#form_export')[0].reset();
  $('#modal_export').modal('show');
}
 
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
        url : "<?= site_url('question/ajax_delete')?>/"+id,
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
          url: "<?= site_url('question/ajax_bulk_delete')?>",
          type: "POST",
          data: {id_question:list_id},
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