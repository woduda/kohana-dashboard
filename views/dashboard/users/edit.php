<?php defined('SYSPATH') or die('No direct script access.'); ?>
<a class="btn btn-primary pull-right" href="<?php echo Route::url('dashboard', array('controller' => 'users')); ?>"><?php echo __('Back'); ?> &rarr;</a>
<?php if ($user->loaded()): ?>
<h1 class="page-header"><?php echo __('Edit user'); ?></h1>
<?php else: ?>
<h1 class="page-header"><?php echo __('New user'); ?></h1>
<?php endif; ?>
<?php echo Form::open(NULL, array('role' => 'form')); ?>
<div class="row">
<div class="col-sm-6 col-md-4 col-lg-3">
	<div class="form-group<?php echo isset($errors['username']) ? ' has-error' : ''; ?>">
		<label for="username"><?php echo __('Username'); ?></label>
		<?php echo Form::input("username", Arr::get($data, "username"), array("id" => "username", "class" => "form-control", "placeholder" => __('Username'))); ?>
		<?php if (isset($errors['username'])): ?>
			<p class="help-block"><?php echo $errors['username']; ?></p>
		<?php endif; ?>
	</div>
	<div class="form-group<?php echo isset($errors['first_name']) ? ' has-error' : ''; ?>">
		<label for="first_name"><?php echo __('First name'); ?></label>
		<?php echo Form::input("first_name", Arr::get($data, "first_name"), array("id" => "first_name", "class" => "form-control", "placeholder" => __('First name'))); ?>
		<?php if (isset($errors['first_name'])): ?>
			<p class="help-block"><?php echo $errors['first_name']; ?></p>
		<?php endif; ?>
	</div>
	<div class="form-group<?php echo isset($errors['last_name']) ? ' has-error' : ''; ?>">
		<label for="last_name"><?php echo __('Last name'); ?></label>
		<?php echo Form::input("last_name", Arr::get($data, "last_name"), array("id" => "last_name", "class" => "form-control", "placeholder" => __('Last name'))); ?>
		<?php if (isset($errors['last_name'])): ?>
			<p class="help-block"><?php echo $errors['last_name']; ?></p>
		<?php endif; ?>
	</div>
<?php if ($user->loaded()): ?>
	<div class="form-group">
		<label for="username"><?php echo __('E-mail'); ?></label>
		<p class="help-block"><?php echo $user->email; ?></p>
	</div>
<?php endif; ?>
	<div class="form-group<?php echo isset($errors['email']) ? ' has-error' : ''; ?>">
		<label for="email"><?php echo __($user->loaded() ? 'New e-mail' : 'E-mail'); ?></label>
		<?php echo Form::input("email", Arr::get($data, "email"), array("id" => "email", "class" => "form-control", "placeholder" =>__($user->loaded() ? 'New e-mail' : 'E-mail'))); ?>
		<?php if (isset($errors['email'])): ?>
			<p class="help-block"><?php echo $errors['email']; ?></p>
		<?php endif; ?>
	</div>
	<div class="form-group<?php echo isset($errors['_external']['repeat_email']) ? ' has-error' : ''; ?>">
		<label for="repeat_email"><?php echo __($user->loaded() ? 'Repeat new e-mail' : 'Repeat e-mail'); ?></label>
		<?php echo Form::input("repeat_email", Arr::get($data, "repeat_email"), array("id" => "repeat_email", "class" => "form-control", "placeholder" => __($user->loaded() ? 'Repeat new e-mail' : 'Repeat e-mail'))); ?>
		<?php if (isset($errors['_external']['repeat_email'])): ?>
			<p class="help-block"><?php echo $errors['_external']['repeat_email']; ?></p>
		<?php endif; ?>
	</div>
<?php if ( ! $user->loaded()): ?>
	<div class="checkbox">
		<label><?php echo Form::checkbox('send_hashlink', '1', ! empty($data['send_hashlink']), array('id' => "send_hashlink")); ?> <?php echo __('Send activation link'); ?></label>
	</div>
<?php endif; ?>
</div>
<div class="col-sm-8 col-md-6 col-lg-4">
	<div class="form-group<?php echo isset($errors['roles']) ? ' has-error' : ''; ?>">
		<p><strong><?php echo __('User roles'); ?></strong></p>
	<?php if (isset($errors['roles'])): ?>
			<p class="help-block"><?php echo $errors['roles']; ?></p>
	<?php endif; ?>
	<?php foreach($roles as $role): ?>
		<div class="checkbox">
			<label>
				<?php echo Form::checkbox('roles[]', $role->id, in_array($role->id, (array) Arr::get($data, 'roles', array())), array('id' => "roles")); ?>
				<?php echo $role->name ?><br />
				<small class="text-muted"><?php echo __($role->description); ?></small>
			</label>
		</div>
	<?php endforeach; ?>
	</div>
</div>
</div>
<p>
	<button type="submit" class="btn btn-default btn-success"> <?php echo __("Save"); ?></button>
</p>
<?php echo Form::close(); ?>
