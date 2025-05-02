"use client";
import React, { useState, useMemo } from "react";
import { FaSort, FaSortUp, FaSortDown } from "react-icons/fa";

const PlayerTable = ({
  players,
  gameRanks,
  seasonRanks,
  selectedPlayer,
  onRowClick,
}) => {
  const headers = [
    { label: "SEASON RANK", key: "season_rank", width: "150px" },
    { label: "GAME RANK", key: "game_rank", width: "120px" },
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

    const isNumericKey = [
      "game_rank",
      "season_rank",
      "jersey_number",
      "height",
      "weight",
      "age",
      "exp",
    ].includes(sortConfig.key);
    if (isNumericKey) {
      const aNum = aValue === "-" ? Infinity : Number(aValue) || 0; // Treat "-" as a high value
      const bNum = bValue === "-" ? Infinity : Number(bValue) || 0;

      return sortConfig.direction === "ascending" ? aNum - bNum : bNum - aNum;
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
              className={`${header.key === "game_rank" ? "game-rank" : ""} ${
                header.key === "season_rank" ? "season-rank" : ""
              } ${sortConfig.key === header.key ? "sorted" : ""}`}
              style={{ width: header.width, cursor: "pointer" }}
              onClick={() => handleSort(header.key)}
            >
              <span className="header-label">{header.label}</span>
              <span className="sort-icons">
                <FaSortUp
                  style={{
                    color:
                      sortConfig.key === header.key &&
                      sortConfig.direction === "ascending"
                        ? "gold"
                        : "gray",
                  }}
                />
                <FaSortDown
                  style={{
                    color:
                      sortConfig.key === header.key &&
                      sortConfig.direction === "descending"
                        ? "gold"
                        : "gray",
                  }}
                />
              </span>
            </th>
          ))}
          <th
            className="w-[300px] px-4 py-2 border-b border-gray-200 bg-white"
            aria-hidden="true"
          ></th>
        </tr>
      </thead>
      <tbody>
        {sortedPlayers.map((player, index) => (
          <tr
            key={index}
            onClick={() => onRowClick(player.id)}
            className={`hover:bg-gray-50 px-4 py-2 ${
              selectedPlayer === player.id ? "selected" : ""
            }`}
            style={{ cursor: "pointer" }}
          >
            {headers.map((header) => (
              <td
                key={header.key}
                className={`${header.key === "game_rank" ? "game-rank" : ""} ${
                  header.key === "season_rank" ? "season-rank" : ""
                }`}
              >
                {player[header.key]}
              </td>
            ))}
            <td className="w-[300px]"></td>
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default PlayerTable;
