<?php defined('SYSPATH') OR die('No direct access allowed.');
echo "Hello {$user->first_name},"; ?>


New user account was created for you at <?php echo $dashboard_config->get("name"); ?>.

The application address is <?php echo URL::base(TRUE); ?>

Your username: <?php echo $user->username; ?>


Click the link below to set your own password and activate your account:

<?php echo Route::url('default', array('controller' => 'users', 'action' => 'hash', 'id' => $hashlink->hash), TRUE); ?>
