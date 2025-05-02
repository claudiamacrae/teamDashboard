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
        $stmt = $this->pdo->prepare("SELECT `rank` FROM player_season_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetch();
    }

    public function getAllGameRanksByPlayerId($playerId) {
        $stmt = $this->pdo->prepare("SELECT `rank` FROM player_game_ranks WHERE player_id = ?");
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
        $res = round(array_sum($ranks) / count($ranks), 2);
        error_log("Ranks for player $playerId: " . implode(", ", $ranks));
        error_log("Calculated new season rank for player $playerId: $res");
        return $res;
    }

    public function upsertSeasonRank($playerId, $newRank) {
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
        // Fetch all players and calculate their total game ranks
        $stmt = $this->pdo->query("SELECT DISTINCT player_id FROM player_game_ranks");
        $playerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $playerRanks = [];
        foreach ($playerIds as $playerId) {
            //sum of all game ranks for the player
            $sum = $this->getAllGameRanksByPlayerId($playerId);
            if ($sum) {
                $playerRanks[] = ['player_id' => $playerId, 'rank_sum' => $sum];;
            }
        }
        // Sort players by their total ranks
        usort($playerRanks, function ($a, $b){
            return $a['rank_sum'] <=> $b['rank_sum'];
        });

        //Assign unique ranks sequentially
        $rank = 1;
        foreach ($playerRanks as $player) {
            $this->upsertSeasonRank($player['player_id'], $rank);
            $rank++;
        }
        // Return the updated ranks
        return $this->getAllSeasonRanks();
    }
}
?>
