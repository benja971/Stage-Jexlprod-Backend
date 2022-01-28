<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['id'])) {

    $ini = parse_ini_file('../config.ini');

    $db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = sprintf(
        "SELECT DISTINCT YEAR(ventes.date) AS annee FROM ventes JOIN collaborateurs ON ventes.id_collaborateur = collaborateurs.id_collaborateur WHERE ventes.id_collaborateur = %d  AND collaborateurs.actif = 1 AND ventes.actif = 1 ORDER BY annee DESC;",
        $data['id']
    );

    $req = $db->prepare($sql);

    $years = [];

    $req->execute();

    foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $annee) {
        $years[] = $annee['annee'];
    }

    if (count($years) === 0) {
        $date = new DateTime("NOW");
        $years[] = $date->format('Y');
    }

    echo json_encode($years);
}
