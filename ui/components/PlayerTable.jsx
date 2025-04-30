"use client";
import { useState, useMemo } from "react";

const PlayerTable = ({ players, gameRanks, seasonRanks }) => {
  const headers = [
    { label: "SEASON RANK", key: "season_rank", width: "80px" },
    { label: "GAME RANK", key: "game_rank", width: "80px" },
    { label: "#", key: "jersey_number", width: "" },
    { label: "FIRST NAME", key: "first_name", width: "" },
    { label: "LAST NAME", key: "last_name", width: "" },
    { label: "POS", key: "position", width: "" },
    { label: "HT", key: "height", width: "" },
    { label: "WT", key: "weight", width: "" },
    { label: "AGE", key: "age", width: "" },
    { label: "EXP", key: "exp", width: "" },
    { label: "COLLEGE", key: "college", width: "" },
  ];

  const [sortConfig, setSortConfig] = useState({
    key: null,
    direction: "ascending",
  });

  const playersWithGameRanks = useMemo(() => {
    if (!gameRanks) return players; // <- prevent crash when ranks is null
    const gameRankMap = new Map();
    gameRanks.forEach((gRank) => {
      gameRankMap.set(gRank.player_id, gRank.rank); // player_id -> game_rank
    });

    return players.map((player) => ({
      ...player,
      game_rank: gameRankMap.get(player.id) || "-", // Add game_rank if found
    }));
  }, [players, gameRanks]);

  const playersWithAllRanks = useMemo(() => {
    if (!seasonRanks) return playersWithGameRanks; // <- prevent crash when ranks is null
    const seasonRankMap = new Map();
    seasonRanks.forEach((sRank) => {
      seasonRankMap.set(sRank.player_id, sRank.rank);
    });

    return playersWithGameRanks.map((player) => ({
      ...player,
      season_rank: seasonRankMap.get(player.id) || "-",
    }));
  }, [playersWithGameRanks, seasonRanks]);
  

  const sortedPlayers = [...playersWithAllRanks].sort((a, b) => {
    if (sortConfig.key === null) return 0;

    const aValue = a[sortConfig.key];
    const bValue = b[sortConfig.key];

    const isNumericKey = ["game_rank", "season_rank", "jersey_number", "height", "weight", "age", "exp"].includes(sortConfig.key);
    if (isNumericKey) {
      const aNum = aValue === "-" ? Infinity : Number(aValue) || 0; // Treat "-" as a high value
      const bNum = bValue === "-" ? Infinity : Number(bValue) || 0;

      return sortConfig.direction === "ascending"
      ? aNum - bNum
      : bNum - aNum;
    }
    if (aValue < bValue) return sortConfig.direction === "ascending" ? -1 : 1;
    if (aValue > bValue) return sortConfig.direction === "ascending" ? 1 : -1;
    return 0;
  });

  const handleSort = (key) => {
    let direction = "ascending";
    if (sortConfig.key === key && sortConfig.direction === "ascending") {
      direction = "descending";
    }
    setSortConfig({ key, direction });
  };

  return (
    <table className="player-table">
      <thead>
        <tr>
          {headers.map((header, index) => (
            <th
              key={index}
              style={{ width: header.width, cursor: "pointer" }}
              onClick={() => handleSort(header.key)}
            >
              {header.label}
              {sortConfig.key === header.key
                ? sortConfig.direction === "ascending"
                  ? " 🔼"
                  : " 🔽"
                : ""}
            </th>
          ))}
        </tr>
      </thead>
      <tbody>
        {sortedPlayers.map((player, index) => (
          <tr key={index}>
            {headers.map((header) => (
              <td key={header.key}>{player[header.key]}</td>
            ))}
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default PlayerTable;
