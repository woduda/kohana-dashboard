<?php defined('SYSPATH') or die('No direct script access.'); ?>
<h1 class="page-header">Zmień hasło</h1>
<div class="col-sm-6 col-md-5 col-lg-4">
<?php echo Form::open(NULL, array('role' => 'form')); ?>
	<div class="form-group<?php echo isset($errors['current_password']) ? ' has-error' : ''; ?>">
		<label for="current-password">Aktualne hasło</label>
		<input type="password" name="current_password" class="form-control" id="current-password" placeholder="Aktualne hasło" value="<?php echo Arr::get($data, 'current_password'); ?>">
		<?php if (isset($errors['current_password'])): ?>
			<p class="help-block"><?php echo $errors['current_password']; ?></p>
		<?php endif; ?>
	</div>
	<div class="form-group<?php echo isset($errors['new_password']) ? ' has-error' : ''; ?>">
		<label for="new-password">Nowe hasło</label>
		<input type="password" name="new_password" class="form-control" id="new-password" placeholder="Nowe hasło">
		<?php if (isset($errors['new_password'])): ?>
			<p class="help-block"><?php echo $errors['new_password']; ?></p>
		<?php endif; ?>
	</div>
	<div class="form-group<?php echo isset($errors['repeat_password']) ? ' has-error' : ''; ?>">
		<label for="repeat-password">Powtórz nowe hasło</label>
		<input type="password" name="repeat_password" class="form-control" id="repeat-password" placeholder="Powtórz nowe hasło">
		<?php if (isset($errors['repeat_password'])): ?>
			<p class="help-block"><?php echo $errors['repeat_password']; ?></p>
		<?php endif; ?>
	</div>
	<button type="submit" class="btn btn-default btn-primary">Zapisz</button>
<?php echo Form::close(); ?>
</div>
