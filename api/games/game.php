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
        echo $service->getGameById($id);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        echo $service->updateGame($id, $data);
        break;

    case 'DELETE':
        echo $service->deleteGame($id);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
