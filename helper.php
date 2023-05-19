<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB', 'carent_app');

$conn = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to connect');
$mysqli = new mysqli(HOST, USER, PASS, DB);

header('Content-Type: application/json');
