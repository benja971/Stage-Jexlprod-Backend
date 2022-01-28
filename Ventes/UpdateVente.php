<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Vente.php";
require "./VenteManager.php";


$json = file_get_contents('php://input');
$data = json_decode($json, true);

file_put_contents(
    "../.log",
    "POST: " . print_r($data, true) . "\n",
    FILE_APPEND
);

if (isset($data["id_vente"]) && isset($data["adresse"]) && isset($data["ville"]) && isset($data["code_postal"]) && isset($data["date"]) && isset($data["frais_agence"]) && isset($data["id_collaborateur"])) {

    file_put_contents(
        "../.log",
        "if ok\n",
        FILE_APPEND
    );

    $ini = parse_ini_file('../config.ini');
    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);

    $venteManager = new VenteManager($db);

    file_put_contents(
        "../.log",
        "manager ok\n",
        FILE_APPEND
    );

    $vente = new Vente([
        "id" => $data["id_vente"],
        "libele" => $data["adresse"],
        "ville" => $data["ville"],
        "code_postal" => $data["code_postal"],
        "date" => $data["date"],
        "collaborateur" => $data["id_collaborateur"],
        "frais_agence" => $data["frais_agence"],
    ]);


    $venteManager->update($vente);
}
