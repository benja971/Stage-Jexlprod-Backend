<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require "./Vente.php";
require "./VenteManager.php";


$db = new PDO('mysql:host=localhost;dbname=stage_jexlprod;charset=utf8', 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$manager = new VenteManager($db);

echo $manager->getList();
