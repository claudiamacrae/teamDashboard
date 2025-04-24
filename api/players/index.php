<?php
require '../config.php';
header('Content-Type: application/json');


# Get all players
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $pdo->query("SELECT * FROM players");
    echo json_encode($stmt->fetchAll());
}

#create a new player
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    #$expectedFields = ['first_name', 'last_name', 'position', 'jersey_number', 'height', 'weight', 'college', 'exp', 'age'];
   
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $position = $data['position'];
    $jersey_number = $data['jersey_number'];
    $height = $data['height'];
    $weight = $data['weight'];
    $age = $data['age'];
    $exp = $data['exp'];
    $college = $data['college'];
    

    $stmt = $pdo->prepare("INSERT INTO players (first_name, last_name, position, jersey_number, height, weight, age, exp, college) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $position, $jersey_number, $height, $weight, $age, $exp, $college]);

    echo json_encode(['id' => $pdo->lastInsertId()]);
}
?>