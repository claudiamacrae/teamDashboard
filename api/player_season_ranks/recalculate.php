<?php
require '../config.php';
require 'PlayerSeasonRankService.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed. Use POST to recalculate ranks.']);
    exit;
}

$service = new PlayerSeasonRankService($pdo);

try {
    $service->recalculateAllSeasonRanks();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
