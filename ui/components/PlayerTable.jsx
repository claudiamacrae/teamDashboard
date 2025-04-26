"use client";
import { useState } from "react";

const PlayerTable = ({ players }) => {
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

  const sortedPlayers = [...players].sort((a, b) => {
    if (sortConfig.key === null) return 0;

    const aValue = a[sortConfig.key];
    const bValue = b[sortConfig.key];

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
