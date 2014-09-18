<?php defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
	<div class="panel panel-default panel-hashlink">
		<div class="panel-body">
		<h4 class="text-center"><?php echo __('We\'ve sent you an email containing a link that will allow you to reset your password'); ?></h4>
		<p><?php echo __('Please check your spam folder if the email doesn’t appear within a few minutes.'); ?></p>
		<p class="text-center">
			<?php echo HTML::anchor(Route::get('login')->uri(), __('Go to login page')." &rarr;"); ?>
		</p>
		</div>
	</div>
</div>
