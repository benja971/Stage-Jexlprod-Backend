<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "Collaborateur.php";
require "CollaborateurManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data)) {

    $ini = parse_ini_file('../config.ini');

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
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

    $manager->add($collaborateur);
}
