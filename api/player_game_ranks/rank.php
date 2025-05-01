<?php
require '../config.php';
require 'GameRankService.php';
header('Content-Type: application/json');

$service = new GameRankService($pdo);

try {
    // Validate query parameters
    $game_id = isset($_GET['game_id']) ? filter_var($_GET['game_id'], FILTER_VALIDATE_INT) : null;
    $playerId = isset($_GET['player_id']) ? filter_var($_GET['player_id'], FILTER_VALIDATE_INT) : null;

    if ($game_id === false || $playerId === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid query parameters']);
        exit;
    }

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($game_id && $playerId) {
                $success = $service->getRank($playerId, $game_id);
                if(!$success) {
                    http_response_code(404);
                    echo json_encode(['error' => 'No rank found for this player in this game']);
                    exit;
                }
            } elseif ($game_id) {
                $success = $service->getRanksByGame($game_id);
                if(!$success) {
                    http_response_code(404);
                    echo json_encode(['error' => 'No ranks found for this game']);
                    exit;
                }
            } elseif ($playerId) {
                $success = $service->getRanksByPlayer($playerId);
                if(!$success) {
                    http_response_code(404);
                    echo json_encode(['error' => 'No ranks found for this player']);
                    exit;
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid parameters']);
                exit;
            }
            echo json_encode($success);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
