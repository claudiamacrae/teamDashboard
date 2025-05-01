<?php
header('Content-Type: application/json');
class PlayerService
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllPlayers()
    {
        $stmt = $this->pdo->query("SELECT * FROM players");
        return json_encode($stmt->fetchAll());
    }

    public function getPlayerById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM players WHERE id = ?");
        $stmt->execute([$id]);
        return json_encode($stmt->fetch());
    }

    public function updatePlayer($id, $data)
    {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $position = $data['position'];
        $jersey_number = $data['jersey_number'];
        $height = $data['height'];
        $weight = $data['weight'];
        $age = $data['age'];
        $exp = $data['exp'];
        $college = $data['college'];

        $stmt = $this->pdo->prepare("UPDATE players SET first_name=?, last_name=?, position=?, jersey_number=?, height=?, weight=?, age=?, exp=?, college=? WHERE id=?");
        $stmt->execute([$first_name, $last_name, $position, $jersey_number, $height, $weight, $age, $exp, $college, $id]);

        return json_encode(['success' => true]);
    }

    public function createPlayer($data)
    {
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $position = $data['position'];
        $jersey_number = $data['jersey_number'];
        $height = $data['height'];
        $weight = $data['weight'];
        $age = $data['age'];
        $exp = $data['exp'];
        $college = $data['college'];


        $stmt = $this->pdo->prepare("INSERT INTO players (first_name, last_name, position, jersey_number, height, weight, age, exp, college) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $position, $jersey_number, $height, $weight, $age, $exp, $college]);

        return json_encode(['id' => $this->pdo->lastInsertId()]);
    }

    public function deletePlayer($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM players WHERE id = ?");
        $stmt->execute([$id]);
        return json_encode(['success' => true]);
    }
}
