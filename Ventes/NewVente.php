<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/x-www-form-urlencoded;charset=UTF-8');

require "./Vente.php";
require "./VenteManager.php";

$json = file_get_contents('php://input');

$data = json_decode($json);

if (isset($data)) {

    $ini = parse_ini_file('../config.ini');

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new VenteManager($db);

    $vente = new Vente(
        [
            "libele" => $data->adresse,
            "ville" => $data->ville,
            "code_postal" => $data->code_postal,
            "date" => $data->date,
            "frais_agence" => $data->frais_agence,
            "collaborateur" => $data->collaborateur,
        ]
    );

    $manager->add($vente);
}
