<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="col-sm-3 col-md-2 sidebar">
	<ul class="nav nav-sidebar">
<?php foreach ($data as $key => $item): ?>
		<li<?php echo ($active_menu_item == $key ? ' class="active"': ''); ?>>
			<a href="<?php echo Route::url('default', Arr::extract($item, array('controller', 'action'))); ?>"><?php echo Arr::get($item, 'name'); ?></a>
		</li>
<?php endforeach; ?>
	</ul>
	<ul class="nav nav-sidebar">
		<li<?php echo ($active_menu_item == 'forms_index' ? ' class="active"': ''); ?>>
			<a href="<?php echo Route::url('default', array('controller' => 'forms')); ?>">Testowe formularze</a>
		</li>
	</ul>
</div>