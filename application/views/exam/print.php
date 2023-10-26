<?php
$setting = $this->db->get_where('tbl_setting', ['id'=>1])->row();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title; ?> | <?= $setting->name ?></title>
  <style>
  body {
		font-family: sans-serif;
		font-size: 14px;
  }

  table {
    font-family: sans-serif;
    color: #232323;
    border-collapse: collapse;
  }
   
  table, th, td {
    border: 0px solid #999;
    padding: 5px 10px;
  }
  </style>
</head>
<body>

  <table width="100%">
    <tr>
      <td align="center" style="vertical-align:middle">
        <img src="<?=base_url('uploads/setting/'.$setting->logo)?>" alt="logo" width="60">
      </td>
      <td style="vertical-align:middle">
        <p style="font-size:20px; font-weight:bold; margin-bottom:0px; margin-top:0px; text-align:center;"><?= $setting->name_school ?></p>
        <p style="text-align:center; margin-bottom:0px; margin-top:0px; font-size:16px;"><?= $setting->address ?></p>
        <p style="text-align:center; margin-bottom:0px; margin-top:0px; font-size:16px;"><?= 'Email: '.$setting->email.'&nbsp;&nbsp;&nbsp; Telepon: '.$setting->phone ?></p>
      </td>
    </tr>
  </table>

	<hr style="margin-bottom:0px; border:2px solid #000;">
  <hr style="margin-bottom:0px; border:1px solid #000; margin-top:3px;">

  <p style="margin-top:20px; font-size:18px; font-weight:bold; text-align:center;">Hasil Ujian</p>

  <div>
    <table style="width:100%">
      <tr>
        <td colspan="2"><h3 style="margin-bottom:0;">Data Siswa</h3></td>
      </tr>
      <tr>
        <td width="35%"><b>Nama</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->student ?></td>
      </tr>
      <tr>
        <td width="35%"><b>NIS</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->nis ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Kelas</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->classroom ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Jurusan</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->major ?></td>
      </tr>
      <tr>
        <td colspan="2"><h3 style="margin-bottom:0;">Data Ujian</h3></td>
      </tr>
      <tr>
        <td width="35%"><b>Nama Ujian</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->exam_name ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Mata Pelajaran</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->lesson ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Jumlah Soal</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->noq ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Waktu</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->time ?> Menit</td>
      </tr>
      <tr>
        <td width="35%"><b>Tanggal Dibuka</b></td>
        <td width="10px"><b> : </b></td>
        <td>
          <?php
          $date = date('Y-m-d', strtotime($exam->exam_date_start));
          $time = date('H:i:s', strtotime($exam->exam_date_start));
          echo longdate_indo($date).' '.$time;
          ?>
        </td>
      </tr>
        <td width="35%"><b>Tanggal Ditutup</b></td>
        <td width="10px"><b> : </b></td>
        <td>
          <?php
          $date = date('Y-m-d', strtotime($exam->exam_date_end));
          $time = date('H:i:s', strtotime($exam->exam_date_end));
          echo longdate_indo($date).' '.$time;
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><h3 style="margin-bottom:0;">Hasil Ujian</h3></td>
      </tr>
      <tr>
        <td width="35%"><b>Jumlah Benar</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->total_true ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Total Nilai</b></td>
        <td width="10px"><b> : </b></td>
        <td><?= $exam->total_value ?></td>
      </tr>
      <tr>
        <td width="35%"><b>Lama Pengerjaan</b></td>
        <td width="10px"><b> : </b></td>
        <td>
          <?php
          $exp = explode(':', $exam->exam_time);
          if($exp[0] == '00'){
            echo trim($exp[1]).' Menit '.trim($exp[2]).' Detik';
          }else{
            echo trim($exp[0]).' Jam '.trim($exp[1]).' Menit '.trim($exp[2]).' Detik';
          }
          ?>
        </td>
      </tr>
      <tr>
        <td width="35%"><b>Waktu Mulai</b></td>
        <td width="10px"><b> : </b></td>
        <td>
          <?php
          $date = date('Y-m-d', strtotime($exam->date_start));
          $time = date('H:i:s', strtotime($exam->date_start));
          echo longdate_indo($date).' '.$time;
          ?>
        </td>
      </tr>
        <td width="35%"><b>Waktu Selesai</b></td>
        <td width="10px"><b> : </b></td>
        <td>
          <?php
          $date = date('Y-m-d', strtotime($exam->date_end));
          $time = date('H:i:s', strtotime($exam->date_end));
          echo longdate_indo($date).' '.$time;
          ?>
        </td>
      </tr>
    </table>
  </div>

  <div style="width:270px; height:100; text-align:center; float:right; margin-top:30px;">
    <p>Pandeglang, <?php $date = date('Y-m-d'); echo date_indo($date); ?></p>
    <p>Guru Pengampu,</p>
    <br><br><br>
    <b><u><?= $exam->teacher ?></u></b>
    <p style="margin-top:0px">NIP. <?= $exam->nip ?></p>
  </div>

  <script>
    window.print();
  </script>
	
</body>
</html>