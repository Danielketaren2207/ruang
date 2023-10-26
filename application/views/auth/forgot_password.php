<div class="card card-primary">
  <div class="card-header"><h4><?= $title ?></h4></div>
  <div class="card-body">
    <p class="text-muted">Kami akan mengirimkan link kepada email anda untuk mengatur ulang kata sandi.</p>
    <?=form_open('password/forgot');?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="hidden" name="code" value="<?= random_string('alnum',100); ?>">
        <input type="text" id="email" name="email" class="form-control" value="<?=set_value('email')?>" autofocus>
        <?=form_error('email', '<small class="text-danger">','</small>');?>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Kirim</button>
      </div>
    <?=form_close();?>
  </div>
</div>