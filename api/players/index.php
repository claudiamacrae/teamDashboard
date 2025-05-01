<?php
require '../config.php';
require 'PlayerService.php';
header('Content-Type: application/json');

$service = new PlayerService($pdo);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode($service->getAllPlayers());
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $expectedFields = ['first_name', 'last_name', 'position', 'jersey_number', 'height', 'weight', 'college', 'exp', 'age'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Missing required field: $field"]);
                exit;
            }
        }
        $newPlayer = $service->createPlayer($data);
        if (!$newPlayer){
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create player']);
            exit;
        }else {
            http_response_code(201);
            echo json_encode(['id' => $newPlayer]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
?>