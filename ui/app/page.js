"use client";
import { useState, useEffect } from "react";
import PlayerTable from "@/components/PlayerTable";
import GameCard from "@/components/GameCard";

const playersF = await fetch("http://localhost:3000/api/players", {
  cache: "no-store",
});
const players = await playersF.json();

const gamesF = await fetch("http://localhost:3000/api/games", {
  cache: "no-store",
});
const games = await gamesF.json();

const seasonRanksF = await fetch("http://localhost:3000/api/season_ranks", {
  cache: "no-store",
});
const seasonRanks = await seasonRanksF.json();

async function getPlayerGameRanks(gameId) {
  let ranksF = await fetch(
    `http://localhost:3000/api/game_ranks?week=${gameId}`,
    {
      cache: "no-store",
    }
  );
  if (!ranksF.ok) {
    throw new Error("Failed to fetch player ranks");
  }
  let data = await ranksF.json(); // assuming your API returns JSON
  // console.log("Fetched ranks:", data); // Debugging statement removed for production
  return data;
}

const Dashboard = () => {
  const [selectedGame, setSelectedGame] = useState(null);
  const [selectedPlayer, setSelectedPlayer] = useState(null);
  const [gameRanks, setGameRanks] = useState([]);

  const handleGameCardClick = (gameId) => {
    setSelectedGame(gameId === selectedGame ? null : gameId);
  };

  useEffect(() => {
    const fetchGameRanks = async () => {
      if (selectedGame) {
        const fetchedGameRanks = await getPlayerGameRanks(selectedGame);
        if (fetchedGameRanks && fetchedGameRanks.data) {
          setGameRanks(fetchedGameRanks.data);
        } else {
          console.error(
            "Invalid response structure for game ranks:",
            fetchedGameRanks
          );
          setGameRanks([]);
        }
      } else {
        setGameRanks([]);
      }
    };
    fetchGameRanks();
  }, [selectedGame]);

  return (
    <div className="dashboard">
      <header className="header">
        <div className="header-left">
          <img src="sfLogo.svg" alt="49ers Logo" className="logo" />
          <h1 className="team-name">San Francisco 49ers</h1>
        </div>
        <p className="season-year">2025-2026 SEASON</p>
      </header>

      <section className="game-bar">
        {games.map((game, index) => (
          <GameCard
            key={index}
            opponent={game.opponent}
            location={game.location}
            date={game.date}
            team_score={game.team_score}
            opp_score={game.opponent_score}
            isSelected={game.id === selectedGame}
            onClick={() => handleGameCardClick(game.id)}
          />
        ))}
      </section>

      <main className="main-content">
        <section className="table-section">
          <div className="table-header">
            <input type="text" placeholder="Search" />
            <button>Export Data</button>
          </div>
          <PlayerTable
            players={players}
            gameRanks={gameRanks}
            seasonRanks={seasonRanks}
          />
        </section>
      </main>
    </div>
  );
};

export default Dashboard;
