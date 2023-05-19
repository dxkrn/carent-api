<?php

require_once "../models/User.php";

$user = new User();
$request_method = $_SERVER["REQUEST_METHOD"];


if ($request_method == 'GET') {
    if (!empty($_GET["email"]) && !empty($_GET["passwd"])) {
        $email = $_GET["email"];
        $passwd = $_GET["passwd"];
        $user->login($email, $passwd);
    } else {
        // $user->login();
    }
} else {
    header("HTTP/1.0 405 Method Not Allowed");
}
