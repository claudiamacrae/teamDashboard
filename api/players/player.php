<?php
require '../config.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Player ID is required']);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $stmt = $pdo->prepare("SELECT * FROM players WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare("UPDATE players SET first_name=?, last_name=?, position=?, jersey_number=?, height=?, weight=?, age=?, exp=?, college=? WHERE id=?");
        $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['position'],
            $data['jersey_number'],
            $data['height'],
            $data['weight'],
            $data['age'],
            $data['exp'],
            $data['college'],
            $id
        ]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        $stmt = $pdo->prepare("DELETE FROM players WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
