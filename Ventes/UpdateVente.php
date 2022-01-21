<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Vente.php";
require "./VenteManager.php";


$json = file_get_contents('php://input');
$data = json_decode($json, true);


if (isset($data["id"]) && isset($data["adresse"]) && isset($data["ville"]) && isset($data["code_postal"]) && isset($data["date"]) && isset($data["prix"]) && isset($data["collaborateur"])) {

    $ini = parse_ini_file('../config.ini');
    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);

    $vente = new Vente([
        "id" => $data["id"],
        "libele" => $data["adresse"],
        "ville" => $data["ville"],
        "code_postal" => $data["code_postal"],
        "date" => $data["date"],
        "prix" => $data["prix"],
        "collaborateur" => $data["collaborateur"],
    ]);

    file_put_contents(
        '../log.log',
        $vente->getId() . ' ' . $vente->getLibele() . ' ' . $vente->getVille() . ' ' . $vente->getCode_postal() . ' ' . $vente->getDate() . ' ' . $vente->getPrix() . ' ' . $vente->getCollaborateur() . "\n",
        FILE_APPEND
    );

    $venteManager = new VenteManager($db);

    $venteManager->update($vente);
}
