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

async function getPlayerRanks(gameId) {
  let ranksF = await fetch(`http://localhost:3000/api/ranks?week=${gameId}`, {
    cache: "no-store",
  });
  if (!ranksF.ok) {
    throw new Error("Failed to fetch player ranks");
  }
  let data = ranksF.json(); // assuming your API returns JSON
  console.log("Fetched ranks:", data);
  return data;
}

const Dashboard = () => {
  const [selectedGame, setSelectedGame] = useState(null);
  const [selectedPlayer, setSelectedPlayer] = useState(null);
  const [ranks, setRanks] = useState([]);

  const handleGameCardClick = (gameId) => {
    setSelectedGame(gameId === selectedGame ? null : gameId);
  };

  useEffect(() => {
    const fetchRanks = async () => {
      if (selectedGame) {
        const fetchedRanks = await getPlayerRanks(selectedGame);
        setRanks(fetchedRanks.data);
      } else {
        setRanks(null);
      }
    };
    fetchRanks();
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
          <PlayerTable players={players} ranks={ranks} />
        </section>
      </main>
    </div>
  );
};

export default Dashboard;
