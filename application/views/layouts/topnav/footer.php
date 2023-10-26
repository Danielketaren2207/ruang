      <footer class="main-footer" style="margin-top:15px !important">
        <div class="footer-left">
          Copyright &copy; <?= date('Y'); ?> <div class="bullet"></div> <?= $setting->name ?>
        </div>
        <div class="footer-right">
          Developer <div class="bullet"></div> <a href="http://??????">Ruang Kedinasan</a>
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
  <script src="<?=base_url();?>assets/modules/sweetalert/sweetalert.min.js"></script>
  <script src="<?=base_url();?>assets/modules/summernote/summernote-bs4.js"></script>

  <!-- Page Specific JS File -->

  <!-- Template JS File -->
  <script src="<?=base_url();?>assets/js/scripts.js"></script>

  <script>
    function sisawaktu(t) {
      var time = new Date(t);
      var n = new Date();
      var x = setInterval(function() {
        var now = new Date().getTime();
        var dis = time.getTime() - now;
        var h = Math.floor((dis % (1000 * 60 * 60 * 60)) / (1000 * 60 * 60));
        var m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
        var s = Math.floor((dis % (1000 * 60)) / (1000));
        h = ("0" + h).slice(-2);
        m = ("0" + m).slice(-2);
        s = ("0" + s).slice(-2);
        var cd = h + ":" + m + ":" + s;
        $('.sisawaktu').html(cd);
      }, 100);
      setTimeout(function() {
        waktuHabis();
      }, (time.getTime() - n.getTime()));
    }

    function countdown(t) {
      var time = new Date(t);
      var n = new Date();
      var x = setInterval(function() {
        var now = new Date().getTime();
        var dis = time.getTime() - now;
        var d = Math.floor(dis / (1000 * 60 * 60 * 24));
        var h = Math.floor((dis % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
        var s = Math.floor((dis % (1000 * 60)) / (1000));
        d = ("0" + d).slice(-2);
        h = ("0" + h).slice(-2);
        m = ("0" + m).slice(-2);
        s = ("0" + s).slice(-2);
        var cd = "<strong>" + d + " Hari, " + h + " Jam, " + m + " Menit, " + s + " Detik</strong>";
        $('.countdown').html(cd);

        setTimeout(function() {
          location.reload()
        }, dis);
      }, 1000);
    }

    function message(type,title,message)
    {
      swal(message, {
        icon: type,
        title: title,
        buttons: false,
        timer: 3000
      });
    }
  </script>
</body>
</html>