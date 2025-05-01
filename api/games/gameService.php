<?php
class GameService
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves all games from the database.
     *
     * @return array An array of all games.
     */
    public function getAllGames()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves a game by its ID.
     *
     * @param int $id The ID of the game - week number the game is played in.
     * @return array|null The game's data or null if not found.
     */
    public function getGameById($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['error' => 'Invalid game ID'];
        }
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
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
        return $this->pdo->lastInsertId();
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
        return ['success' => true];
    }

    /**
     * Deletes a game by it's ID.
     *
     * @param int $id The ID of the game to delete.
     * @return string A success or error message.
     */
    public function deleteGame($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return json_encode(['error' => 'Invalid player ID']);
        }
        $stmt = $this->pdo->prepare("DELETE FROM games WHERE id = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
}
