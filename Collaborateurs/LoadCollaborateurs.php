<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require "Collaborateur.php";
require "CollaborateurManager.php";


$db = new PDO("mysql:host=localhost; dbname=stage_jexlprod; charset=utf8", "root", "root");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$collaborateurManager = new CollaborateurManager($db);

$collaborateurs = $collaborateurManager->getList();

echo json_encode($collaborateurs);
