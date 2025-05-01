<?php
require '../config.php';
require 'GameRankService.php';
header('Content-Type: application/json');

$service = new GameRankService($pdo);

try {
    $week = isset($_GET['week']) ? (int)$_GET['week'] : null;
    $playerId = isset($_GET['player_id']) ? (int)$_GET['player_id'] : null;

    if ($week && $playerId) {
        echo $service->getRank($playerId, $week);
    }
    elseif ($week) {
        echo $service->getRanksByGame($week);
    }
    elseif ($playerId) {
        echo $service->getRanksByPlayer($playerId);
    }
    else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid parameters']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>