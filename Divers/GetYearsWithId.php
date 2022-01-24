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

    $req = $db->prepare('SELECT DISTINCT YEAR(ventes.date) AS annee FROM ventes join collaborateurs on ventes.collaborateur = collaborateurs.id where ventes.collaborateur = ' . $data["id"] . '  and collaborateurs.actif = 1 ORDER BY annee DESC  ');

    file_put_contents(
        "../.log",
        date("Y-m-d H:i:s") . " - " . $data["id"] . " - " . $req->queryString . "\n\n",
        FILE_APPEND
    );

    $years = [];

    $req->execute();

    foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $annee) {
        $years[] = $annee['annee'];
    }

    echo json_encode($years);
}
