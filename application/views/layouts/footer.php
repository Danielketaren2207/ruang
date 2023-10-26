
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; <?= date('Y'); ?> <div class="bullet"></div> <?= $setting->name ?>
        </div>
        <div class="footer-right">
          Developer <div class="bullet"></div> <a href="https://????">Ruang Kedinasan</a>
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="<?=base_url();?>assets/modules/popper.js"></script>
  <script src="<?=base_url();?>assets/modules/tooltip.js"></script>
  <script src="<?=base_url();?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url();?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?=base_url();?>assets/modules/moment.min.js"></script>
  <script src="<?=base_url();?>assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  <script src="<?=base_url();?>assets/modules/chart.min.js"></script>
  <script src="<?=base_url();?>assets/modules/summernote/summernote-bs4.js"></script>
  <script src="<?=base_url();?>assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="<?=base_url();?>assets/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
  <script src="<?=base_url();?>assets/modules/datatables/datatables.min.js"></script>
  <script src="<?=base_url();?>assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?=base_url();?>assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
  <script src="<?=base_url();?>assets/modules/izitoast/js/iziToast.min.js"></script>
  <script src="<?=base_url();?>assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="<?=base_url();?>assets/modules/select2/dist/js/select2.full.min.js"></script>
  <script src="<?=base_url();?>assets/modules/jquery-ui/jquery-ui.min.js"></script>

  <!-- Template JS File -->
  <script src="<?=base_url();?>assets/js/scripts.js"></script>
  <script src="<?=base_url();?>assets/js/app/app.js"></script>
  <script>    
    <?php if($this->session->flashdata('success')) { ?>
      iziToast.success({
        title: 'Berhasil!',
        message: '<?= $this->session->flashdata('success') ?>',
        position: 'topRight'
      });
    <?php } else if($this->session->flashdata('warning')) { ?>
      iziToast.warning({
        title: 'Peringatan!',
        message: '<?= $this->session->flashdata('warning') ?>',
        position: 'topRight'
      });
    <?php } else if($this->session->flashdata('danger')) { ?>
      iziToast.error({
        title: 'Gagal!',
        message: '<?= $this->session->flashdata('danger') ?>',
        position: 'topRight'
      });
    <?php } ?>
  </script>
  
</body>
</html>