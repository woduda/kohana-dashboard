<?php defined('SYSPATH') OR die('No direct access allowed.');
echo "Hello {$user->first_name},"; ?>


Click the following link to reset your password:

<?php echo Route::url('default', array('controller' => 'users', 'action' => 'hash', 'id' => $hashlink->hash), TRUE); ?>



Thanks,
<?php echo $dashboard_config->get("name"); ?>
