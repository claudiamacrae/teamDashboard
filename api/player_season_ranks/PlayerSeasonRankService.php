<?php
class PlayerSeasonRankService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSeasonRanks() {
        $stmt = $this->pdo->query("SELECT * FROM player_season_ranks");
        return $stmt->fetchAll();
    }

    public function getSeasonRankByPlayerId($playerId) {
        $stmt = $this->pdo->prepare("SELECT * FROM player_season_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetch();
    }

    public function getAllGameRanksByPlayerId($playerId) {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateSeasonRank($playerId, $rank) {
        $stmt = $this->pdo->prepare("UPDATE player_season_ranks SET `rank` = ? WHERE player_id = ?");
        return $stmt->execute([$rank, $playerId]);
    }

    public function deleteSeasonRank($playerId) {
        $stmt = $this->pdo->prepare("DELETE FROM player_season_ranks WHERE player_id = ?");
        return $stmt->execute([$playerId]);
    }

    public function calculateSeasonRankForPlayer($playerId) {
        $ranks = $this->getAllGameRanksByPlayerId($playerId);

        if (empty($ranks)) {
            return null;
        }
        return round(array_sum($ranks) / count($ranks), 2);
    }

    public function upsertSeasonRank($playerId) {
        $newRank = $this->calculateSeasonRankForPlayer($playerId);
        if ($newRank === null) {
            throw new Exception("Season rank could not be calculated for player $playerId");
        }
        $currentRank = $this->getSeasonRankByPlayerId($playerId);

        if ($currentRank) {
            $this->updateSeasonRank($playerId, $newRank);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO player_season_ranks (player_id, `rank`) VALUES (?, ?)");
            $stmt->execute([$playerId, $newRank]);
        }
        return $newRank;
    }

    public function recalculateAllSeasonRanks() {
        $stmt = $this->pdo->query("SELECT DISTINCT player_id FROM player_game_ranks");
        $playerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($playerIds as $playerId) {
            $this->upsertSeasonRank($playerId);
        }
    }
}
?>
