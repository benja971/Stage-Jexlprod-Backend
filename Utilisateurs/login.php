<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Utilisateur.php";
require "./UtilisateurManager.php";


$json = file_get_contents('php://input');
$data = json_decode($json, true);


if (isset($data['email']) && isset($data['password'])) {

    $ini = parse_ini_file("../config.ini");

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user = new Utilisateur($data);

    $userManager = new UtilisateurManager($db);

    echo $userManager->getUtilisateur($user);
}
