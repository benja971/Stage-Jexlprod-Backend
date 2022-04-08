<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");

require "./SecurityManager.php";

$ini = parse_ini_file("../config.ini");
$db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$securityManager = new SecurityManager($db);

if (isset($_SERVER['HTTP_CLIENT_IP']) && array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ips = array_map('trim', $ips);
    $ip = $ips[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

$ip = filter_var($ip, FILTER_VALIDATE_IP);
$ip = ($ip === false) ? '0.0.0.0' : $ip;
$ip = ($ip === "::1") ? '127.0.0.1' : $ip;

$securityManager->AddEchec($ip);
