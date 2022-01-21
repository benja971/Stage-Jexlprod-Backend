<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Vente.php";
require "VenteManager.php";

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data->id_vente)) {


    $ini = parse_ini_file('../config.ini');

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new VenteManager($db);

    echo $manager->delete($data->id_vente);
}
