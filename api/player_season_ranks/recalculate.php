<?php
require '../config.php';
require 'PlayerSeasonRankService.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use POST to recalculate ranks.']);
    exit;
}

$service = new PlayerSeasonRankService($pdo);

try {
    $seasonRanks = $service->recalculateAllSeasonRanks();
    if (!$seasonRanks){
        throw new Exception('Failed to recalculate ranks. Service is not initialized properly.');
    }
    echo json_encode($seasonRanks);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
