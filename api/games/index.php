<?php
require '../config.php';
header('Content-Type: application/json');


# Get full game schedule
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $pdo->query("SELECT * FROM games");
    echo json_encode($stmt->fetchAll());
}

#create a new game
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $expectedFields = ['week_number', 'opponent',  'date', 'location', 'team_score', 'opponent_score'];
    
    $stmt = $pdo->prepare("INSERT INTO games (opponent, date, location, team_score, opponent_score) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['opponent'],
        $data['date'],
        $data['location'],
        $data['team_score'],
        $data['opponent_score']
    ]);
    echo json_encode(['id' => $pdo->lastInsertId()]);
}
?>