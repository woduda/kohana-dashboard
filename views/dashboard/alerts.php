<?php defined('SYSPATH') or die('No direct script access.'); ?>
<?php foreach ($alerts as $level => $_alerts): ?>
<?php foreach ($_alerts as $message): ?>
	<div class="alert alert-<?php echo $level; ?>"><?php echo $message; ?></div>
<?php endforeach; ?>
<?php endforeach; ?>

