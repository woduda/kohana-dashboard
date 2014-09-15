<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
	<div class="col-xs-offset-1 col-sm-offset-2 col-md-offset-4 col-xs-10 col-sm-8 col-md-4">
	<div class="panel panel-default panel-login">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo __('Sign in to dashboard'); ?></h3>
		</div>
		<div class="panel-body">
<?php if (isset($error)): ?>
		<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
		<?php echo Form::open('', array('role' => 'form')); ?>
		<div class="form-group">
			<?php echo Form::label('username', __('Username')); ?>
			<?php echo Form::input('username', '', array('id' => 'login', 'class' => 'form-control input-lg')); ?>
		</div>
		<div class="form-group">
			<?php echo Form::label('password', 'Hasło'); ?>
			<?php echo Form::password('password', '', array('id' => 'password', 'class' => 'form-control input-lg')); ?>
		</div>

<?php echo Form::submit('sign_in', __('Sign in'), array("class" => "btn btn-primary btn-lg btn-block")); ?>
<?php echo Form::close(); ?>
		</div>
	</div>
	</div>
</div>
