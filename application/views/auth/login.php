<div class="card card-primary">
  <div class="card-header"><h4><?= $title ?></h4></div>
  <div class="card-body">
    <?=form_open('login');?>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control" tabindex="1" value="<?=set_value('email')?>" autofocus>
        <?=form_error('email', '<small class="text-danger">','</small>');?>
      </div>

      <div class="form-group">
        <div class="d-block">
          <label for="password" class="control-label">Kata Sandi</label>
          <div class="float-right">
            <a href="<?=base_url('password/forgot')?>" class="text-small">
              Lupa Kata Sandi?
            </a>
          </div>
        </div>
        <input type="password" name="password" class="form-control" tabindex="2" value="<?=set_value('password')?>">
        <?=form_error('password', '<small class="text-danger">','</small>');?>
      </div>

      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" id="remember" name="remember" class="custom-control-input" tabindex="3">
          <label class="custom-control-label" for="remember">Ingat Saya</label>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
          Masuk
        </button>
      </div>
    <?=form_close();?>
  </div>
</div>