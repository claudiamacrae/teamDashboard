<?php
// Removed header setting to maintain separation of concerns.
class PlayerService
{
    private $pdo;

    /**
     * PlayerService constructor.
     *
     * @param \PDO $pdo The PDO instance for database connection.
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retrieves all players from the database.
     *
     * @return array An array of all players.
     */
    public function getAllPlayers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM players");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieves a player by their ID.
     *
     * @param int $id The ID of the player.
     * @return array|null The player's data or null if not found.
     */
    public function getPlayerById($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return ['error' => 'Invalid player ID'];
        }
        $stmt = $this->pdo->prepare("SELECT * FROM players WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
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

        return ['success' => true];
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

        return $this->pdo->lastInsertId();
    }


    /**
     * Deletes a player by their ID.
     *
     * @param int $id The ID of the player to delete.
     * @return string A JSON-encoded success or error message.
     */
    public function deletePlayer($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return json_encode(['error' => 'Invalid player ID']);
        }
        $stmt = $this->pdo->prepare("DELETE FROM players WHERE id = ?");
        $stmt->execute([$id]);
        return ['success' => true];
    }
}
