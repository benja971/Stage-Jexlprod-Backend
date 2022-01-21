<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$ini = parse_ini_file('../config.ini');

$db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_USER']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->prepare("SELECT * FROM roles");
$stmt->execute();

$roles = [];

foreach ($stmt->fetchAll() as $row) {
    $roles[] = [
        'id' => $row['id'],
        'nom' => $row['nom']
    ];
}

echo json_encode($roles);
