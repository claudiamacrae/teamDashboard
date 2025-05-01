<?php
require '../config.php';
require 'GameService.php';
header('Content-Type: application/json');

$service = new GameService($pdo);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $success = $service->getAllGames();
        echo json_encode($success);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $expectedFields = ['week_number', 'opponent',  'date', 'location', 'team_score', 'opponent_score'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                exit;
            }
        }
        $newGame = $service->createGame($data);
        if (!$newGame) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create game']);
            exit;
        } else {
            http_response_code(201);
            echo json_encode(['id' => $newGame]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>