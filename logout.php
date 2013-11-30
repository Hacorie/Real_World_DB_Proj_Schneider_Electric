<?php

$expire = time() - 3600; // Expire in the past

// Delete the cookies by expiring them
setcookie('username', '', $expire);
setcookie('token', '', $expire);

// Completely destroy the sessions
// Taken from http://www.php.net/manual/en/function.session-unset.php#107089
session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id(true);

// Redirect to login page
header('Location: index.php');

?>