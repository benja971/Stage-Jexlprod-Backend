<?php

header("Access-Control-Allow-Origin: *");

$bd = new PDO('mysql:host=localhost;dbname=stage_jexlprod', 'root', 'root');
$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$req = $bd->prepare('SELECT DISTINCT YEAR(date) AS annee FROM ventes ORDER BY annee ASC');
$req->execute();

$years = [];

foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $annee) {
    $years[] = $annee['annee'];
}

echo json_encode($years);
