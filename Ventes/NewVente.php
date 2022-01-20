<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

require "./Vente.php";
require "./VenteManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json);


if (isset($data)) {


    $db = new PDO('mysql:host=localhost;dbname=stage_jexlprod;charset=utf8', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new VenteManager($db);

    $vente = new Vente(
        [
            "libele" => $data->adresse,
            "ville" => $data->ville,
            "code_postal" => $data->code_postal,
            "date" => $data->date,
            "prix" => $data->prix,
            "collaborateur" => $data->collaborateur,
        ]
    );

    $vente->__toString();

    file_put_contents(
        '../log.log',
        date('Y-m-d H:i:s') . ' ==> ' . $vente->__toString() . "\n",
        FILE_APPEND
    );

    $manager->add($vente);
}
