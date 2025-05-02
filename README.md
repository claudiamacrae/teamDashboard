## **Set-up Instructions**
### **Prerequisites**
- PHP (8.x recommended)
- MySQL
- Composer
- Node.js (v18 or higher)
- npm

### **Backend Setup (PHP API)**
1. **Clone the repository**, if you haven't already
    ```bash
    git clone https://github.com/claudiamacrae/teamDashboard.git
    cd api
    ```
2. **Install PHP dependencies**
    ```bash
    composer install
    ```
3. **Set up database**
    - Create a new MySQL database (e.g. leaderboard_db)
    - Import the schema (see “Sample Dataset” section below)

    Example:
    ```bash
    mysql -u root -p dashboard_db < ../sample_data/sample_schema.sql
    ```
4. **Configure environment settings**
    - Rename `env.example` to `.env` and update with your DB credentials
5. **Run the PHP server**
    ```bash
    php -S localhost:8000
    ```

### **Frontend Setup (React + Next.js)**
1. **Open a new terminal window** and navigate to frontend directory
    ```bash
    cd ui
    ```
2. **Install Node dependencies**
    ```bash
    npm install
    ```
3. **Configure environment variables**

4. **Run the development server**
    ```bash
    npm run dev
    ```
    - The frontend will be available at http://localhost:3000

## **Sample Dataset**
You can find a sample schema file under `sample_data/sample_schema.sql`.

## **API Documentation**

Here’s a brief overview of the available API endpoints to interact with the `games`, `players`, `player_game_ranks`, and `player_season_ranks` tables.


### **1. Games**

| Endpoint      | Method | Description                           |
| ------------- | ------ | ------------------------------------- |
| `/games`      | GET    | Retrieves a list of all games         |
| `/games/{id}` | GET    | Retrieves a specific game by its `id` |
| `/games`      | POST   | Creates a new game                    |
| `/games/{id}` | PUT    | Updates a specific game by its `id`   |
| `/games/{id}` | DELETE | Deletes a specific game by its `id`   |

#### **Example Responses**
#### **GET /games**
Retrieves a list of all games.

##### **Response:**

```json
[
  {
    "id": 1,
    "opponent": "Dallas Cowboys",
    "date": "2025-01-01",
    "location": "Levi's Stadium",
    "team_score": 24,
    "opponent_score": 17
  },
  ...
]
```

#### **GET /games/{id}**
Retrieves a specific game by its `id`.

##### **Parameters:**
* `id` (integer): The ID of the game.

##### **Response:**

```json
{
  "id": 1,
  "opponent": "Dallas Cowboys",
  "date": "2025-01-01",
  "location": "Levi's Stadium",
  "team_score": 24,
  "opponent_score": 17
}
```

#### **POST /games**

Creates a new game record.

##### **Request Body:**

```json
{
  "opponent": "Dallas Cowboys",
  "date": "2025-01-01",
  "location": "Levi's Stadium",
  "team_score": 24,
  "opponent_score": 17
}
```

##### **Response:**

```json
{
  "id": 21,
  "opponent": "Dallas Cowboys",
  "date": "2025-01-01",
  "location": "Levi's Stadium",
  "team_score": 24,
  "opponent_score": 17
}
```

#### **PUT /games/{id}**

Updates a game record.

**Parameters:**

* `id` (integer): The ID of the game.

##### **Request Body:**

```json
{
  "opponent": "Dallas Cowboys",
  "date": "2025-01-01",
  "location": "Levi's Stadium",
  "team_score": 28,
  "opponent_score": 17
}
```

##### **Response:**

```json
{
  "id": 1,
  "opponent": "Dallas Cowboys",
  "date": "2025-01-01",
  "location": "Levi's Stadium",
  "team_score": 28,
  "opponent_score": 17
}
```

#### **DELETE /games/{id}**

Deletes a game record.

**Parameters:**

* `id` (integer): The ID of the game.

##### **Response:**

```json
{
  "message": "Game deleted successfully"
}
```

---

### **2. Players**

| Endpoint        | Method | Description                         |
| --------------- | ------ | ----------------------------------- |
| `/players`      | GET    | Retrieves a list of all players     |
| `/players/{id}` | GET    | Retrieves a specific player by `id` |
| `/players`      | POST   | Creates a new player                |
| `/players/{id}` | PUT    | Updates a player by `id`            |
| `/players/{id}` | DELETE | Deletes a player by `id`            |

#### **Example Response**

#### **GET /players**

Retrieves a list of all players.

##### **Response:**

```json
[
  {
    "id": 1,
    "first_name": "Joe",
    "last_name": "Smith",
    "jersey_number": 12,
    "position": "Quarterback",
    "height": 75,
    "weight": 220,
    "age": 25,
    "exp": 3,
    "college": "Stanford"
  },
  ...
]
```

#### **GET /players/{id}**

Retrieves a specific player by their `id`.

**Parameters:**

* `id` (integer): The ID of the player.

##### **Response:**

```json
{
  "id": 1,
  "first_name": "Joe",
  "last_name": "Smith",
  "jersey_number": 12,
  "position": "Quarterback",
  "height": 75,
  "weight": 220,
  "age": 25,
  "exp": 3,
  "college": "Stanford"
}
```

#### **POST /players**

Creates a new player record.

##### **Request Body:**

```json
{
  "first_name": "Joe",
  "last_name": "Smith",
  "jersey_number": 12,
  "position": "Quarterback",
  "height": 75,
  "weight": 220,
  "age": 25,
  "exp": 3,
  "college": "Stanford"
}
```

##### **Response:**

```json
{
  "id": 65,
  "first_name": "Joe",
  "last_name": "Smith",
  "jersey_number": 12,
  "position": "Quarterback",
  "height": 75,
  "weight": 220,
  "age": 25,
  "exp": 3,
  "college": "Stanford"
}
```

#### **PUT /players/{id}**

Updates a player record.

**Parameters:**

* `id` (integer): The ID of the player.

##### **Request Body:**

```json
{
  "first_name": "Joe",
  "last_name": "Smith",
  "jersey_number": 12,
  "position": "Quarterback",
  "height": 75,
  "weight": 225,
  "age": 26,
  "exp": 4,
  "college": "Stanford"
}
```

##### **Response:**

```json
{
  "id": 1,
  "first_name": "Joe",
  "last_name": "Smith",
  "jersey_number": 12,
  "position": "Quarterback",
  "height": 75,
  "weight": 225,
  "age": 26,
  "exp": 4,
  "college": "Stanford"
}
```

#### **DELETE /players/{id}**

Deletes a player record.

**Parameters:**

* `id` (integer): The ID of the player.

##### **Response:**

```json
{
  "message": "Player deleted successfully"
}
```

---

### **3. Player Game Ranks**

| Endpoint                  | Method | Description                           |
| ------------------------- | ------ | ------------------------------------- |
| `/player_game_ranks`      | GET    | Retrieves a list of player game ranks |
| `/player_game_ranks/{id}` | GET    | Retrieves a specific player game rank |
| `/player_game_ranks`      | POST   | Creates a new player game rank        |
| `/player_game_ranks/{id}` | PUT    | Updates a player game rank            |
| `/player_game_ranks/{id}` | DELETE | Deletes a player game rank            |

#### **Example Responses**

#### **GET /player\_game\_ranks**

Retrieves a list of player ranks for each game.

##### **Response:**

```json
[
  {
    "id": 1,
    "player_id": 1,
    "game_id": 1,
    "rank": 1
  },
  ...
]
```

#### **GET /player\_game\_ranks/{id}**

Retrieves a specific player game rank by `id`.

**Parameters:**

* `id` (integer): The ID of the player game rank.

##### **Response:**

```json
{
  "id": 1,
  "player_id": 1,
  "game_id": 1,
  "rank": 1
}
```

#### **POST /player\_game\_ranks**

Creates a new player game rank record.

##### **Request Body:**

```json
{
  "player_id": 1,
  "game_id": 1,
  "rank": 1
}
```

##### **Response:**

```json
{
  "id": 14878,
  "player_id": 1,
  "game_id": 1,
  "rank": 1
}
```

#### **PUT /player\_game\_ranks/{id}**

Updates a player game rank record.

**Parameters:**

* `id` (integer): The ID of the player game rank.

##### **Request Body:**

```json
{
  "player_id": 1,
  "game_id": 1,
  "rank": 2
}
```

##### **Response:**

```json
{
  "id": 1,
  "player_id": 1,
  "game_id": 1,
  "rank": 2
}
```

#### **DELETE /player\_game\_ranks/{id}**

Deletes a player game rank record.

**Parameters:**

* `id` (integer): The ID of the player game rank.

##### **Response:**

```json
{
  "message": "Player game rank deleted successfully"
}
```

---


### **4. Player Season Ranks**

| Endpoint                    | Method | Description                             |
| --------------------------- | ------ | --------------------------------------- |
| `/player_season_ranks`      | GET    | Retrieves a list of player season ranks |
| `/player_season_ranks/{id}` | GET    | Retrieves a specific player season rank |
| `/player_season_ranks`      | POST   | Creates a new player season rank        |
| `/player_season_ranks/{id}` | PUT    | Updates a player season rank            |
| `/player_season_ranks/{id}` | DELETE | Deletes a player season rank            |
| `/player_season_ranks/recalculate.php` | POST | Triggers recalculation of all season ranks            |

#### **GET /player\_season\_ranks**

Retrieves a list of player season ranks.


#### **GET /player\_season\_ranks/{id}**

Retrieves a specific player season rank by `id`.

**Parameters:**

* `id` (integer): The ID of the player season rank.

##### **Response:**

```json
{
  "id": 1,
  "player_id": 1,
  "rank": 5
}
```

#### **POST /player\_season\_ranks**

Creates a new player season rank record.


#### **PUT /player\_season\_ranks/{id}**

Updates a player season rank record.

**Parameters:**

* `id` (integer): The ID of the player season rank.

##### **Request Body:**

```json
{
  "player_id": 1,
  "rank": 6
}
```

##### **Response:**

```json
{
  "id": 1,
  "player_id": 1,
  "rank": 6
}
```

#### **DELETE /player\_season\_ranks/{id}**

Deletes a player season rank record.

**Parameters:**

* `id` (integer): The ID of the player season rank.

##### **Response:**

```json
{
  "message": "Player season rank deleted successfully"
}
```



* **GET /games**

```json
[
  {
    "id": 1,
    "opponent": "Dallas Cowboys",
    "date": "2025-01-01",
    "location": "Levi's Stadium",
    "team_score": 24,
    "opponent_score": 17
  },
  ...
]
```

* **POST /games**

```json
{
}
```


* **GET /players**

```json
[
  {
    "id": 1,
    "first_name": "Joe",
    "last_name": "Smith",
    "jersey_number": 12,
    "position": "Quarterback",
    "height": 75,
    "weight": 220,
    "age": 25,
    "exp": 3,
    "college": "Stanford"
  },
  ...
]
```




* **GET /player\_game\_ranks**

```json
[
  {
    "id": 1,
    "player_id": 1,
    "game_id": 1,
    "rank": 1
  },
  ...
]
```

* **POST /player\_game\_ranks**

```json
{
  "id": 14878,
  "player_id": 1,
  "game_id": 1,
  "rank": 1
}
```


##### **Example Responses**

* **GET /player\_season\_ranks**

```json
[
  {
    "id": 1,
    "player_id": 1,
    "rank": 5
  },
  ...
]
```

* **POST /player\_season\_ranks**

```json
{
  "id": 61,
  "player_id": 1,
  "rank": 5
}
```


