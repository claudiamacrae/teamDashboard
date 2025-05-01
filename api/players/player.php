<?php
require '../config.php';
require 'PlayerService.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Player ID is required']);
    exit;
}

$service = new PlayerService($pdo);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $player = $service->getPlayerById($id);
        if (!$player) {
            http_response_code(404);
            echo json_encode(['error' => 'Player not found']);
        } else {
            echo json_encode($player);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $service->updatePlayer($id, $data);
        if (!$success) {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update player']);
        } else {
            echo json_encode(['success' => true]);
        }
        break;

    case 'DELETE':
        echo json_encode($service->deletePlayer($id));
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
