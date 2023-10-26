<div class="card card-primary">
  <div class="card-header"><h4><?= $title ?></h4></div>
  <div class="card-body">
    <?=form_open('password/reset/'.$this->uri->segment(3));?>
      <div class="form-group">
        <label>Kata Sandi Baru</label>
        <input type="password" class="form-control" name="new_password" placeholder="Kata Sandi" value="<?= set_value('new_password'); ?>">
        <?= form_error('new_password', '<small class="text-danger">','</small>'); ?>
      </div>
      <div class="form-group">
        <label>Ulangi Kata Sandi Baru</label>
        <input type="password" class="form-control" name="confirm_password" placeholder="Kata Sandi">
        <?= form_error('confirm_password', '<small class="text-danger">','</small>'); ?>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Proses</button>
      </div>
    <?=form_close();?>
  </div>
</div>