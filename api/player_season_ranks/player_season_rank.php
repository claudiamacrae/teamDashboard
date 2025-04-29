<?php
require '../config.php';
header('Content-Type: application/json');

$id = $_GET['player_id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Player ID is required']);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM player_season_ranks WHERE player_id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE player_season_ranks SET rank=? WHERE player_id=?");
        $stmt->execute([
            $data['rank'],
            $id
        ]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        $stmt = $pdo->prepare("DELETE FROM player_season_ranks WHERE player_id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
