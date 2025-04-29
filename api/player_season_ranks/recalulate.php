<?php
require '../../config.php';
require 'PlayerSeasonRankService.php';
header('Content-Type: application/json');

$service = new PlayerSeasonRankService($pdo);

try {
    $service->recalculateAllPlayers();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
