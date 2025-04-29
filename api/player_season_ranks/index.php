<?php
require '../../config.php';
require 'PlayerSeasonRankService.php';
header('Content-Type: application/json');

$service = new PlayerSeasonRankService($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($service->getAll());
}
