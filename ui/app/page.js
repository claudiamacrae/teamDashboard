import React from "react";
import PlayerTable from "@/components/PlayerTable";

const defaultPlayers = [
  {
    firstName: "Brandon",
    lastName: "Banks",
    number: 33,
    position: "K",
    height: 40,
    weight: 130,
    age: 32,
    exp: 20,
    college: "Northwestern",
    seasonRank: 9,
    gameRank: 1,
  },
  {
    firstName: "Alex",
    lastName: "Morales",
    number: 81,
    position: "TE",
    height: 74,
    weight: 320,
    age: 22,
    exp: 5,
    college: "Penn State",
    seasonRank: 4,
    gameRank: 2,
  },
  {
    firstName: "Chris",
    lastName: "Wilson",
    number: 90,
    position: "QB",
    height: 73,
    weight: 180,
    age: 20,
    exp: 1,
    college: "Kent State",
    seasonRank: 2,
    gameRank: 8,
  },
  {
    firstName: "Seamus",
    lastName: "Long",
    number: 8,
    position: "RB",
    height: 73,
    weight: 330,
    age: 22,
    exp: 2,
    college: "Oklahoma State",
    seasonRank: 1,
    gameRank: 1,
  },
  {
    firstName: "Pinky",
    lastName: "Peterson",
    number: 8,
    position: "RB",
    height: 73,
    weight: 330,
    age: 22,
    exp: 2,
    college: "Oklahoma State",
    seasonRank: 1,
    gameRank: 1,
  },
];

const res = await fetch("http://localhost:3000/api/players", {
  cache: "no-store",
});
const players = await res.json();

const Dashboard = () => {
  return (
    <div className="dashboard">
      <header className="header">
        <div className="header-left">
          <img src="sfLogo.svg" alt="49ers Logo" className="logo" />
          <h1 className="team-name">San Francisco 49ers</h1>
        </div>
        <p className="season-year">2025-2026 SEASON</p>
      </header>

      <main className="main-content">
        <section className="table-section">
          <div className="table-header">
            <input type="text" placeholder="Search" />
            <button>Export Data</button>
          </div>
          <PlayerTable players={players} />
        </section>
      </main>
    </div>
  );
};

export default Dashboard;
