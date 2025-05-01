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
        echo $service->getPlayerById($id);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        echo $service->updatePlayer($id, $data);
        break;

    case 'DELETE':
        echo $service->deletePlayer($id);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
