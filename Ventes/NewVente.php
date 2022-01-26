<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

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
            "commission_ht" => $data->prix,
            "commission_ttc" => $data->prix * 0.8,
            "collaborateur" => $data->collaborateur,
        ]
    );

    $manager->add($vente);
}
