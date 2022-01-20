<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");


require "Collaborateur.php";
require "CollaborateurManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json, true);


if (isset($data)) {

    file_put_contents(
        '../log.log',
        'POST: ' . $data["annee"] . PHP_EOL,
        FILE_APPEND
    );
    $db = new PDO("mysql:host=localhost; dbname=stage_jexlprod; charset=utf8", "root", "root");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $collaborateurManager = new CollaborateurManager($db);

    $collaborateurs = $collaborateurManager->getList($data["annee"]);

    echo json_encode($collaborateurs);
}
