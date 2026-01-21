<?php

session_start();

$cookieName = 'customer_auth';
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
setcookie($cookieName, '', [
	'expires'  => time() - 42000,
	'path'     => '/',
	'secure'   => $isHttps,
	'httponly' => true,
	'samesite' => 'Lax',
]);


session_destroy();


header('Location: ../View/login.php');
exit();

?>