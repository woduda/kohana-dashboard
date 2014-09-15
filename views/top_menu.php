<?php defined('SYSPATH') or die('No direct script access.'); ?>
<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only"><?php echo __('Show menu'); ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo URL::base(); ?>"><?php echo Arr::get($dashboard_config, 'name'); ?> <span class="glyphicon glyphicon-home"></span></a>
		</div>
		<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav navbar-right">
<?php foreach ($data as $key => $item): ?>
			<li<?php echo ($active_menu_item == $key ? ' class="active"': ''); ?>>
				<a href="<?php echo Route::url(Arr::get($item, 'route', 'default'), Arr::extract($item, array('controller', 'action', 'directory'))); ?>"><?php echo Arr::get($item, 'name'); ?></a>
			</li>
<?php endforeach; ?>
			<li><a href="<?php echo Route::url('logout'); ?>"><span class="glyphicon glyphicon-log-out"></span> <?php echo __('Sign out'); ?></a></li>
		</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>
