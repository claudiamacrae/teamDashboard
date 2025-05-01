<?php
class GameRankService
{
    private $pdo;
    /**
     * GameRankService constructor.
     *
     * @param \PDO $pdo The PDO instance for database connection.
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves a ranks for a given game.
     *
     * @param int $id The ID of the game.
     * @return array|null An array of ranks or null if not found.
     */
    public function getRanksByGame($gameId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE game_id = ?");
        $stmt->execute([$gameId]);
        return $stmt->fetchAll();
    }

    /**
     * Retrieves a ranks across all games for a given player.
     *
     * @param int $id The ID of the player.
     * @return array|null An array of ranks or null if not found.
     */
    public function getRanksByPlayer($playerId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetchAll();
    }

    /**
     * Retrieves a rank for a given player in a specific game.
     *
     * @param int $playerId The ID of the player.
     * @param int $gameId The ID of the game.
     * @return array|null An array of rank or null if not found.
     */
    public function getRank($playerId, $gameId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM player_game_ranks WHERE player_id = ? AND game_id = ?");
        $stmt->execute([$playerId, $gameId]);
        return $stmt->fetch();
    }
}
