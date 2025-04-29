<?php
class PlayerSeasonRankService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM player_season_ranks");
        return $stmt->fetchAll();
    }

    public function calculateSeasonRankForPlayer($playerId) {
        $stmt = $this->pdo->prepare("SELECT rank FROM player_game_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        $ranks = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($ranks)) {
            return null;
        }
        return round(array_sum($ranks) / count($ranks), 2);
    }

    public function upsertSeasonRank($playerId) {
        $rank = $this->calculateSeasonRankForPlayer($playerId);
        if ($rank === null) {
            throw new Exception("No game ranks for player $playerId");
        }

        $stmt = $this->pdo->prepare("SELECT id FROM player_season_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        $exists = $stmt->fetch();

        if ($exists) {
            $stmt = $this->pdo->prepare("UPDATE player_season_ranks SET rank = ? WHERE player_id = ?");
            $stmt->execute([$rank, $playerId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO player_season_ranks (player_id, rank) VALUES (?, ?)");
            $stmt->execute([$playerId, $rank]);
        }

        return $rank;
    }

    public function recalculateAllPlayers() {
        $stmt = $this->pdo->query("SELECT DISTINCT player_id FROM player_game_ranks");
        $playerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($playerIds as $playerId) {
            $this->upsertSeasonRank($playerId);
        }
    }
}
?>
