<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "Collaborateur.php";
require "CollaborateurManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data)) {
    $db = new PDO("mysql:host=localhost; dbname=stage_jexlprod; charset=utf8", "root", "root");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new CollaborateurManager($db);

    $collaborateur = new Collaborateur(
        [
            'id' => $data->id,
            'civilite' => $data->civilite,
            'nom' => $data->nom,
            'prenom' => $data->prenom,
            'email' => $data->email,
            'statut' => $data->statut,
        ]
    );

    $manager->update($collaborateur);
}
