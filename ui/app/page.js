"use client";
import { useState, useEffect, useMemo } from "react";
import PlayerTable from "@/components/PlayerTable";
import GameCard from "@/components/GameCard";

async function fetchInitialData() {
  const [playersF, gamesF, seasonRanksF] = await Promise.all([
    fetch("http://localhost:3000/api/players", { cache: "no-store" }),
    fetch("http://localhost:3000/api/games", { cache: "no-store" }),
    fetch("http://localhost:3000/api/season_ranks", { cache: "no-store" }),
  ]);

  if (!playersF.ok || !gamesF.ok || !seasonRanksF.ok) {
    throw new Error("Failed to fetch one or more dashboard datasets");
  }

  const [players, games, seasonRanks] = await Promise.all([
    playersF.json(),
    gamesF.json(),
    seasonRanksF.json(),
  ]);

  return { players, games, seasonRanks };
}

async function getPlayerGameRanks(gameId) {
  let ranksF = await fetch(
    `http://localhost:3000/api/game_ranks?week=${gameId}`,
    { cache: "no-store" }
  );
  if (!ranksF.ok) {
    throw new Error("Failed to fetch player ranks");
  }
  let data = ranksF.json();
  return data;
}

const Dashboard = () => {
  const [players, setPlayers] = useState([]);
  const [games, setGames] = useState([]);
  const [seasonRanks, setSeasonRanks] = useState([]);
  const [selectedGame, setSelectedGame] = useState(null);
  const [selectedPlayer, setSelectedPlayer] = useState(null);
  const [gameRanks, setGameRanks] = useState([]);
  const [searchQuery, setSearchQuery] = useState("");
  const [showFilters, setShowFilters] = useState(false);
  const [positionFilter, setPositionFilter] = useState("");

  const handleGameCardClick = (gameId) => {
    setSelectedGame(gameId === selectedGame ? null : gameId);
  };
  const handleRowClick = (playerId) => {
    setSelectedPlayer(playerId === selectedPlayer ? null : playerId);
  };

  useEffect(() => {
    const fetchData = async () => {
      try {
        const { players, games, seasonRanks } = await fetchInitialData();
        setPlayers(players);
        setGames(games);
        setSeasonRanks(seasonRanks);
      } catch (err) {
        console.error("Error loading initial dashboard data:", err);
      }
    };
    fetchData();
  }, []);

  useEffect(() => {
    const fetchGameRanks = async () => {
      if (!selectedGame) {
        setGameRanks([]);
        return;
      }
      try {
        const fetchedGameRanks = await getPlayerGameRanks(selectedGame);
        setGameRanks(fetchedGameRanks ?? []);
      } catch (error) {
        console.error("Error fetching game ranks:", error);
        setGameRanks([]);
      }
    };
    fetchGameRanks();
  }, [selectedGame]);

  const selectedPlayerData = useMemo(() => {
    return players.find((player) => player.id === selectedPlayer);
  }, [players, selectedPlayer]);

  const filteredPlayers = useMemo(() => {
    return players.filter((player) => {
      const nameMatch = `${player.first_name} ${player.last_name}`
        .toLowerCase()
        .includes(searchQuery.toLowerCase());
      const positionMatch = positionFilter === "" || player.position === positionFilter;
      return nameMatch && positionMatch;
  });
  }, [players, searchQuery]);

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
        {games.map((game) => (
          <GameCard
            key={game.id}
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

      <main className="main-content flex">
        {showFilters && (
          <aside className="w-64 min-w-[16rem] bg-white border-r p-4 transition-all">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-lg font-semibold">Filters</h2>
              <button onClick={() => setShowFilters(false)}>&times;</button>
            </div>

            <div className="mb-4">
              <label className="block text-sm mb-1">Position</label>
              <select
                value={positionFilter}
                onChange={(e) => setPositionFilter(e.target.value)}
                className="w-full border px-2 py-1 rounded text-sm"
              >
                <option value="">All</option>
                <option value="QB">QB</option>
                <option value="RB">RB</option>
                <option value="WR">WR</option>
                <option value="TE">TE</option>
              </select>
            </div>
          </aside>
        )}

        <section className={`table-section flex-1 transition-all duration-300 ${showFilters ? "ml-0" : ""}`}>
          <div className="table-toolbar flex items-center justify-between p-4 border-b bg-white">
            <button
              onClick={() => setShowFilters(!showFilters)}
              className="px-4 py-2 border rounded-md text-sm bg-gray-100 hover:bg-gray-200"
            >
              Filter
            </button>

            <input
              type="text"
              placeholder="Search players"
              className="input px-3 py-2 border rounded-md text-sm w-64"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />

            <div className="ml-auto">
              <button className="btn px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                Export Data
              </button>
            </div>
          </div>

          <PlayerTable
            players={filteredPlayers}
            gameRanks={gameRanks}
            seasonRanks={seasonRanks}
            selectedPlayer={selectedPlayer}
            onRowClick={handleRowClick}
          />
        </section>

        {selectedPlayerData && (
          <aside className="player-info-aside">
            <div className="player-info-card">
              <h3>
                {selectedPlayerData.first_name} {selectedPlayerData.last_name}
              </h3>
              <p>
                <strong>Position:</strong> {selectedPlayerData.position}
              </p>
              <p>
                <strong>Height:</strong> {selectedPlayerData.height}
              </p>
              <p>
                <strong>Weight:</strong> {selectedPlayerData.weight}
              </p>
              <p>
                <strong>Age:</strong> {selectedPlayerData.age}
              </p>
              <p>
                <strong>College:</strong> {selectedPlayerData.college}
              </p>
              <div className="player-graph"></div>
            </div>
          </aside>
        )}
      </main>
    </div>
  );
};

export default Dashboard;
