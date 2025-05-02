<?php
require '../config.php';
require 'GameRankService.php';
header('Content-Type: application/json');

$service = new GameRankService($pdo);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $expectedFields = ['player_id', 'game_id', 'rank'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                exit;
            }
        }
        $newGameRank = $service->createRank($data['player_id'], $data['game_id'], $data['rank']);
        if (!$newGameRank){
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create player']);
            exit;
        }else {
            http_response_code(201);
            echo json_encode(['success' => $newGameRank]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
?>