
            <div class="simple-footer">
              <div class="footer-left">
                Copyright &copy; <?= date('Y'); ?> <div class="bullet"></div> <?= $setting->name ?>
              </div>
              <div class="footer-right">
                Development By <a href="https://???????">Ruang Kedinasan</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="<?=base_url()?>assets/modules/jquery.min.js"></script>
  <script src="<?=base_url()?>assets/modules/popper.js"></script>
  <script src="<?=base_url()?>assets/modules/tooltip.js"></script>
  <script src="<?=base_url()?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?=base_url()?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?=base_url()?>assets/modules/moment.min.js"></script>
  <script src="<?=base_url()?>assets/js/stisla.js"></script>
  
  <!-- JS Libraies -->
  <script src="<?=base_url();?>assets/modules/izitoast/js/iziToast.min.js"></script>

  <!-- Page Specific JS File -->

  <!-- Template JS File -->
  <script src="<?=base_url()?>assets/js/scripts.js"></script>
  <script src="<?=base_url()?>assets/js/custom.js"></script>
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