<?php defined('SYSPATH') or die('No direct script access.'); ?>
<a class="btn btn-primary pull-right" href="<?php echo Route::url('default', array('controller' => 'users')); ?>"><?php echo __('Back'); ?> &rarr;</a>
<?php if ($user->loaded()): ?>
<h1 class="page-header"><?php echo __('Edit user'); ?></h1>
<?php else: ?>
<h1 class="page-header"><?php echo __('New user'); ?></h1>
<?php endif; ?>
<div class="row">
<div class="well">
<?php echo __('Under construction'); ?>
</div>
<div class="col-sm-6 col-md-4 col-lg-3">
<?php echo Form::open(NULL, array('role' => 'form')); ?>
<?php echo Form::close(); ?>
</div>
</div>