<?php
require '../config.php';
require 'PlayerService.php';
header('Content-Type: application/json');

$service = new PlayerService($pdo);

# Get all players
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo $service->getAllPlayers();
}

#create a new player
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    #$expectedFields = ['first_name', 'last_name', 'position', 'jersey_number', 'height', 'weight', 'college', 'exp', 'age'];
    echo $service->createPlayer($data);
}
?>