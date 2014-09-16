<?php defined('SYSPATH') or die('No direct script access.'); ?>
<?php if ($is_admin): ?>
<a class="btn btn-success pull-right" href="<?php echo Route::url('default', array('controller' => 'users', 'action' => 'add')); ?>"><span class="glyphicon glyphicon-plus"></span> <?php echo __("New user"); ?></a>
<?php endif; ?>
<h1 class="page-header"><?php echo __('Users'); ?></h1>
<?php if (isset($users)): ?>
<?php if ($users->count() == 0): ?>
<div class="well"><?php echo __('No data'); ?></div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-striped">
<thead>
	<tr>
		<th><?php echo __("Created"); ?></th>
		<th><?php echo __('Username'); ?></th>
		<th><?php echo __("E-mail"); ?></th>
		<th><?php echo __("First name"); ?></th>
		<th><?php echo __("Last name"); ?></th>
		<th class="text-center"><?php echo __("Status"); ?></th>
		<th>&nbsp;</th>
	</tr>
</thead>
<tbody>
<?php foreach ($users as $user): $active = $user->active(); ?>
<tr>
	<td><?php echo date('Y-m-d', $user->created)." ".date('H:i:s', $user->created); ?></td>
	<td><?php echo $user->username; ?></td>
	<td><?php echo HTML::mailto($user->email); ?></td>
	<td><?php echo $user->first_name; ?></td>
	<td><?php echo $user->last_name; ?></td>
	<td class="text-center"><?php echo Arr::path($statuses, array($user->status, $active)); ?></td>
	<td>
<?php if ($user->id != $logged_user->id): ?>
		<?php echo HTML::anchor(Route::get('default')->uri(array('controller' => 'users', 'action' => 'edit', 'id' => $user->id)), '<span class="glyphicon glyphicon-edit"></span> '.__('Edit'), array('class' => 'btn btn-primary btn-xs', 'style' => 'margin: 0.2em 0')); ?></li>
<?php if ($user->status == Model_User::STATUS_CREATED): ?>
		<?php echo HTML::anchor(Route::get('default')->uri(array('controller' => 'users', 'action' => 'sendactivationlink', 'id' => $user->id)), '<span class="glyphicon glyphicon-send"></span> '.__('Send activation link'), array('class' => 'btn btn-xs btn-success', 'style' => 'margin: 0.2em 0')); ?></li>
<?php else: ?>
		<?php echo HTML::anchor(Route::get('default')->uri(array('controller' => 'users', 'action' => $active ? 'off' : 'on', 'id' => $user->id)), '<span class="glyphicon glyphicon-off"></span> '.__($active ? 'Turn off' : 'Turn on'), array('class' => 'btn btn-xs btn-'.($active ? 'success' : 'warning'), 'style' => 'margin: 0.2em 0')); ?></li>
<?php endif; ?>
<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
<?php endif; ?>
<?php endif; ?>
