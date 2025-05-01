<?php
require '../config.php';
require 'GameService.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Game ID is required']);
    exit;
}
$service = new GameService($pdo);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $game = $service->getGameById($id);
        if (!$game) {
            http_response_code(404);
            echo json_encode(['error' => 'Game not found']);
        } else {
            echo json_encode($game);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $service->updateGame($id, $data);
        if (!$success) {
            http_response_code(400);
            echo json_encode(['error' => 'Failed to update game']);
        } else {
            echo json_encode(['success' => true]);
        }
        break;

    case 'DELETE':
        $success = $service->deleteGame($id);
        echo json_encode($success);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
