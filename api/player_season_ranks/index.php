<?php
require '../config.php';
require 'PlayerSeasonRankService.php';
header('Content-Type: application/json');

$service = new PlayerSeasonRankService($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($service->getAllSeasonRanks());
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['player_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Player ID is required']);
        exit;
    }
    $playerId = $data['player_id'];
    try {
        $rank = $service->upsertSeasonRank($playerId);
        echo json_encode(['success' => true, 'rank' => $rank]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
