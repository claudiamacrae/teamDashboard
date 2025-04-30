<?php
require '../config.php';
require 'PlayerSeasonRankService.php';
header('Content-Type: application/json');

$service = new PlayerSeasonRankService($pdo);

$playerId = $_GET['player_id'] ?? null;
if (!$playerId) {
    http_response_code(400);
    echo json_encode(['error' => 'Player ID is required']);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $success = $service->getSeasonRankByPlayerId($playerId);
        echo json_encode($success);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['rank'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Rank value is required']);
            exit;
        }
        $success = $service->updateSeasonRank($playerId, $data['rank']);
        echo json_encode(['success' => $success]);
        break;

    case 'DELETE':
        $success = $service->deleteSeasonRank($playerId);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
