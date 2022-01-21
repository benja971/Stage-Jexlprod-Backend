<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Vente.php";
require "./VenteManager.php";

$data = json_decode(file_get_contents("php://input"));



if (isset($data->annee)) {


    $ini = parse_ini_file('../config.ini');

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new VenteManager($db);


    $ventes = $manager->getList($data->annee);
    // file_put_contents(
    //     '../log.log',
    //     date('Y-m-d H:i:s') . ' - ' . "test" . PHP_EOL . " \n",
    //     FILE_APPEND
    // );

    echo $ventes;
}
