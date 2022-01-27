<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


$ini = parse_ini_file('../config.ini');

$db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT DISTINCT YEAR(ventes.date) AS annee FROM ventes join collaborateurs on ventes.id_collaborateur = collaborateurs.id_collaborateur where collaborateurs.actif = 1 ORDER BY annee DESC  ";
$req = $db->prepare($sql);
$req->execute();

$years = [];

foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $annee) {
    $years[] = $annee['annee'];
}

echo json_encode($years);
