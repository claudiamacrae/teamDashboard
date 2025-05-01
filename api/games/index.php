<?php
require '../config.php';
require 'GameService.php';
header('Content-Type: application/json');

$service = new GameService($pdo);

# Get full game schedule
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo $service->getAllGames();
}

#create a new game
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    //$expectedFields = ['week_number', 'opponent',  'date', 'location', 'team_score', 'opponent_score'];
    $service->createGame($data);
}
?>