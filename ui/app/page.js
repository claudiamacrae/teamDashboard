"use client";
import { useState, useEffect, useMemo } from "react";
import PlayerTable from "@/components/PlayerTable";
import GameCard from "@/components/GameCard";
import PlayerCard from "@/components/PlayerCard";

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
  const [minExpFilter, setMinExpFilter] = useState(0);
  const [maxExpFilter, setMaxExpFilter] = useState(Infinity);

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
        if (!Array.isArray(fetchedGameRanks)) {
          throw new Error("Invalid rank data format");
        }
        setGameRanks(fetchedGameRanks);
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
    if (
      !searchQuery &&
      positionFilter === "" &&
      maxExpFilter === "" &&
      minExpFilter === ""
    )
      return players;

    const searchQueryLower = searchQuery.toLowerCase();
    return players.filter((player) => {
      const nameMatch = searchQueryLower
        ? `${player.first_name} ${player.last_name} ${player.college}`
            .toLowerCase()
            .includes(searchQueryLower)
        : true;

      const positionMatch = positionFilter
        ? player.position === positionFilter
        : true;

      const expMatch =
        player.exp >= (minExpFilter || 0) &&
        player.exp <= (maxExpFilter !== Infinity ? maxExpFilter : Infinity);

      return nameMatch && positionMatch && expMatch;
    });
  }, [players, searchQuery, positionFilter, minExpFilter, maxExpFilter]);

  return (
    <div className="dashboard">
      <div className="gradient-bg">
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
      </div>
      <main className="main-content flex relative">
        {showFilters && (
          <aside className="filter-panel w-64 bg-gray-100 border-r p-4 transition-all duration-300">
            <div className="flex justify-between items-center mb-4">
              <h2 className="text-lg font-semibold">Filters</h2>
              <button onClick={() => setShowFilters(false)}>&times;</button>
            </div>

            <div className="position-drop-down mb-4">
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
            <div>
              <input
                type="number"
                placeholder="Min Exp"
                className="border p-2 rounded w-24"
                value={minExpFilter !== 0 ? minExpFilter : ""}
                onChange={(e) => {
                  const minVal = e.target.value;
                  setMinExpFilter(minVal === "" ? 0 : parseInt(minVal, 10));
                }}
              />
              <input
                type="number"
                placeholder="Max Exp"
                className="border p-2 rounded w-24"
                value={maxExpFilter !== Infinity ? maxExpFilter : ""}
                onChange={(e) => {
                  const maxVal = e.target.value;
                  setMaxExpFilter(
                    maxVal === "" ? Infinity : parseInt(maxVal, 10)
                  );
                }}
              />
            </div>
          </aside>
        )}

        <section className="table-section flex-1 relative">
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
          <aside className="player-info-aside absolute bg-white rounded-xl shadow-lg p-4 z-20 transition-all">
            <PlayerCard player={selectedPlayerData}></PlayerCard>
          </aside>
        )}
      </main>
    </div>
  );
};

export default Dashboard;
