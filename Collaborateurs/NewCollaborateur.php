<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "Collaborateur.php";
require "CollaborateurManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data)) {

    file_put_contents(
        "../log.log",
        date("Y-m-d H:i:s") . " ===> " . print_r($data, true) . "\n",
        FILE_APPEND
    );


    $db = new PDO("mysql:host=localhost; dbname=stage_jexlprod; charset=utf8", "root", "root");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new CollaborateurManager($db);

    $collaborateur = new Collaborateur(
        [
            "civilite" => $data->civilite,
            "nom" => $data->nom,
            "prenom" => $data->prenom,
            "email" => $data->email,
            "statut" => $data->statut,

        ]
    );


    file_put_contents(
        "../log.log",
        date("Y-m-d H:i:s") . " ===> " . $collaborateur->__toString() . "\n",
        FILE_APPEND
    );


    $manager->add($collaborateur);
}
