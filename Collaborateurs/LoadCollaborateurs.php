<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");


require "Collaborateur.php";
require "CollaborateurManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);


if (isset($data)) {


    $ini = parse_ini_file('../config.ini');

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $collaborateurManager = new CollaborateurManager($db);

    $collaborateurs = $collaborateurManager->getList($data["annee"]);

    file_put_contents(
        '../log.log',
        date("Y-m-d H:i:s") . " - " . print_r($data, true) . "\n",
        FILE_IGNORE_NEW_LINES
    );


    echo json_encode($collaborateurs);
}
