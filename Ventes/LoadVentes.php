<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Vente.php";
require "./VenteManager.php";

$data = json_decode(file_get_contents("php://input"));

if (isset($data)) {

    file_put_contents("../log.log", print_r($data, true));

    $db = new PDO('mysql:host=localhost;dbname=stage_jexlprod;charset=utf8', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $manager = new VenteManager($db);

    echo $manager->getList($data->annee);
}
