<?php
header('Content-Type: application/json');
class GameService
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllGames()
    {
        $stmt = $this->pdo->query("SELECT * FROM games");
        return json_encode($stmt->fetchAll());
    }

    public function getGameById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$id]);
        return json_encode($stmt->fetch());
    }

    public function createGame($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO games (opponent, date, location, team_score, opponent_score) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['opponent'],
            $data['date'],
            $data['location'],
            $data['team_score'],
            $data['opponent_score']
        ]);
        return json_encode(['id' => $this->pdo->lastInsertId()]);
    }

    public function updateGame($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE games SET opponent=?, date=?, location=?, team_score=?, opponent_score=? WHERE id=?");
        $stmt->execute([
            $data['opponent'],
            $data['date'],
            $data['location'],
            $data['team_score'],
            $data['opponent_score'],
            $id
        ]);
        return json_encode(['success' => true]);
    }

    public function deleteGame($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM games WHERE id = ?");
        $stmt->execute([$id]);
        return json_encode(['success' => true]);
    }
}
