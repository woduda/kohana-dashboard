<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
	<div class="panel panel-default panel-hashlink">
<?php if ($done): ?>
		<div class="panel-body">
		<h4><?php echo __('New password was set up successfully'); ?></h4>
		<p class="text-center">
			<?php echo HTML::anchor(Route::get('login')->uri(), __('Go to login page')." &rarr;"); ?>
		</p>
		</div>
<?php else: ?>
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo __('Set up your account password'); ?></h3>
		</div>
		<div class="panel-body">
		<?php echo Form::open(NULL, array('role' => 'form')); ?>
		<div class="form-group<?php echo isset($errors['password']) ? ' has-error' : ''; ?>">
			<?php echo Form::label('password', __('Password')); ?>
			<?php echo Form::password('password', '', array('id' => 'password', 'class' => 'form-control input-lg')); ?>
			<?php if (isset($errors['password'])): ?>
				<p class="help-block"><?php echo $errors['password']; ?></p>
			<?php endif; ?>
		</div>
		<div class="form-group<?php echo isset($errors['repeat_password']) ? ' has-error' : ''; ?>">
			<?php echo Form::label('repeat_password', __('Repeat password')); ?>
			<?php echo Form::password('repeat_password', '', array('id' => 'password', 'class' => 'form-control input-lg')); ?>
			<?php if (isset($errors['repeat_password'])): ?>
				<p class="help-block"><?php echo $errors['repeat_password']; ?></p>
			<?php endif; ?>
		</div>
		<?php echo Form::submit('save', __('Save'), array("class" => "btn btn-primary btn-lg btn-block")); ?>
<?php echo Form::close(); ?>
		</div>
<?php endif; ?>
	</div>
</div>
