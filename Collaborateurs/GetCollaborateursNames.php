<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$ini = parse_ini_file('../config.ini');

$db = new PDO($ini['DB_URL'], $ini['DB_USER'], $ini['DB_PASSWORD']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT id, CONCAT(prenom, ' ', nom) AS nom FROM collaborateurs WHERE actif = 1 ORDER BY prenom";
$stmt = $db->prepare($sql);
$stmt->execute();
$collaborateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($collaborateurs);
