<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
	<div class="panel panel-default panel-recover">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo __('Forgot password'); ?></h3>
		</div>
		<div class="panel-body">
		<p class="help-block"><?php echo __('Enter your username and email of your account to setup new password'); ?></p>
		</p>
<?php if ( ! empty($error)): ?>
		<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php echo Form::open(NULL, array('role' => 'form')); ?>
		<div class="form-group">
			<?php echo Form::label('username', __('Username')); ?>
			<?php echo Form::input('username', '', array('id' => 'username', 'class' => 'form-control input-lg')); ?>
		</div>
		<div class="form-group">
			<?php echo Form::label('email', __('E-mail')); ?>
			<?php echo Form::input('email', '', array('id' => 'email', 'class' => 'form-control input-lg')); ?>
		</div>
		<?php echo Form::submit('submit', __('Submit'), array("class" => "btn btn-primary btn-lg btn-block")); ?>
<?php echo Form::close(); ?>
		<div class="text-center" style="margin-top: 1.4em;"><?php echo HTML::anchor(Route::get('login')->uri(), __('Go to login page')." &rarr;"); ?></div>
		</div>
	</div>
</div>
