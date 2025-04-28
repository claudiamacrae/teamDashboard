<?php
require_once '../config.php';
header('Content-Type: application/json');

try {
    $week = isset($_GET['week']) ? (int)$_GET['week'] : null;
    $playerId = isset($_GET['player_id']) ? (int)$_GET['player_id'] : null;

    // Start building the SQL
    $sql = "SELECT `id`, `player_id`, `game_id`, `rank` FROM player_game_ranks";

    $conditions = [];
    $params = [];

    if ($week !== null) {
        $conditions[] = 'game_id = :week';
        $params['week'] = $week;
    }
    if ($playerId !== null) {
        $conditions[] = 'player_id = :playerId';
        $params['playerId'] = $playerId;
    }
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $ranks = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => $ranks
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>