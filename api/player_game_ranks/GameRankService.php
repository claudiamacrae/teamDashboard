<?php
header('Content-Type: application/json');
class GameRankService
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRanksByGame($gameId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE game_id = ?");
        $stmt->execute([$gameId]);
        return json_encode($stmt->fetchAll());
    }

    public function getRanksByPlayer($playerId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return json_encode($stmt->fetchAll());
    }

    public function getRank($playerId, $gameId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE player_id = ? AND game_id = ?");
        $stmt->execute([$playerId, $gameId]);
        return json_encode($stmt->fetchAll());
    }

    public function deletePlayer($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM players WHERE id = ?");
        $stmt->execute([$id]);
        return json_encode(['success' => true]);
    }
}
