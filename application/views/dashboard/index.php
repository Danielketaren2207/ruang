<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?= $title ?></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#"><i class="fas fa-tachometer-alt"></i> Beranda</a></div>
        <!-- <div class="breadcrumb-item"><a href="#">Bootstrap Components</a></div>
        <div class="breadcrumb-item">Breadcrumb</div> -->
      </div>
    </div>

    <?php if(is_student()){ ?>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-info alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-columns"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Kelas</div>
              <p style="font-size:16px"><?= $student->classroom ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-danger alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-bookmark"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Jurusan</div>
              <p style="font-size:16px"><?= $student->major ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-primary alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Tanggal</div>
              <p style="font-size:16px"><?= longdate_indo(date('Y-m-d')) ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-warning alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-clock"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Jam</div>
              <p class="live-clock" style="font-size:16px"><?= date('H:i:s') ?></p>
            </div>
          </div>
        </div>
      </div>
    <?php }elseif(is_teacher()){ ?>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-info alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-users"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Siswa</div>
              <p style="font-size:16px"><?= $total_student ?> Orang</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-danger alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-book"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Mata Pelajaran</div>
              <p style="font-size:16px"><?= $total_lesson ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-primary alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Tanggal</div>
              <p style="font-size:16px"><?= date_indo(date('Y-m-d')) ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-warning alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-clock"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Jam</div>
              <p class="live-clock" style="font-size:16px"><?= date('H:i:s') ?> WIB</p>
            </div>
          </div>
        </div>
      </div>
    <?php }else{ ?>
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-info alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-users"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Siswa</div>
              <p style="font-size:16px"><?= $total_student; ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-danger alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-user-tie"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Guru</div>
              <p style="font-size:16px"><?= $total_teacher; ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-primary alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-bookmark"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Jurusan</div>
              <p style="font-size:16px"><?= $total_major; ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
          <div class="alert alert-warning alert-has-icon shadow">
            <div class="alert-icon"><i class="fas fa-columns"></i></div>
            <div class="alert-body">
              <div class="alert-title" style="margin-top:2px">Kelas</div>
              <p style="font-size:16px"><?= $total_class; ?></p>
            </div>
          </div>
        </div>                  
      </div>
    <?php } ?>

    <div class="row">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Grafik Siswa Berdasarkan Jurusan</h4>
          </div>
          <div class="card-body">
            <canvas id="chartStudentByMajor"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <h4>Grafik Siswa Berdasarkan Jenis Kelamin</h4>
          </div>
          <div class="card-body">
            <canvas id="chartStudentByGender"></canvas>
          </div>
        </div>
      </div>
    </div>

  </section>
</div>

<?php
// Data jumlah siswa berdasarkan jurusan
$major_name = '';
$major_total = null;
foreach($total_student_by_major as $row){
  $major_name .= "'".$row->major."',";
  $major_total .= (int)$row->total.',';
}

// Data jumlah siswa berdasarkan jenis kelamin
$student_gender = '';
$student_total = null;
foreach($total_student_by_gender as $row){
  $student_gender .= "'".$row->gender."',";
  $student_total .= (int)$row->total.',';
}
?>

<script>
$(document).ready(function(){
  // Live Clock
  setInterval(function() {
    var date = new Date();
    var h = date.getHours(), m = date.getMinutes(), s = date.getSeconds();
    h = ("0" + h).slice(-2);
    m = ("0" + m).slice(-2);
    s = ("0" + s).slice(-2);

    var time = h + ":" + m + ":" + s + " WIB";
    $('.live-clock').html(time);
  }, 1000);

  // Chart
  var ctx = document.getElementById("chartStudentByMajor").getContext('2d');
  var chartStudentByMajor = new Chart(ctx, {
    type: 'pie',
    data: {
      datasets: [{
        data: [<?= $major_total; ?>],
        backgroundColor: [
          '#6777ef',
          '#191d21',
          '#fc544b',
        ],
        label: 'Dataset 1'
      }],
      labels: [<?= $major_name; ?>],
    },
    options: {
      responsive: true,
      legend: {
        position: 'bottom',
      },
    }
  });

  // Chart
  var ctx = document.getElementById("chartStudentByGender").getContext('2d');
  var chartStudentByGender = new Chart(ctx, {
    type: 'pie',
    data: {
      datasets: [{
        data: [<?= $student_total; ?>],
        backgroundColor: [
          '#6777ef',
          '#191d21',
        ],
        label: 'Dataset 1'
      }],
      labels: [<?= $student_gender; ?>],
    },
    options: {
      responsive: true,
      legend: {
        position: 'bottom',
      },
    }
  });
});
</script>