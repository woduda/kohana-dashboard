<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
	<div class="panel panel-default panel-hashlink">
		<div class="panel-body">
		<h4 class="text-center"><?php echo __('This link has expired'); ?></h4>
		<p class="text-center">
			<?php echo HTML::anchor(Route::get('login')->uri(), __('Go to login page')." &rarr;"); ?>
		</p>
		</div>
	</div>
</div>
