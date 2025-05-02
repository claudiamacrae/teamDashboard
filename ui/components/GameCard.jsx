import styles from "./GameCard.module.css";
const GameCard = ({
  opponent,
  location,
  date,
  team_score,
  opp_score,
  isSelected,
  onClick,
}) => {
  const isWin = team_score > opp_score; // Determine if the game was a win or loss
  const result = isWin ? "W" : "L"; // Set the result based on the score comparison

  const formatOpponent = (opponent) => {
    const uppercaseOpponent = opponent.toUpperCase();
    const words = uppercaseOpponent.split(" ");

    // Split before last word: "LOS ANGELES RAMS" → ["LOS ANGELES", "RAMS"]
    const city = words.slice(0, -1).join(" ");
    const team = words[words.length - 1];

    return [city, team];
  };

  const normalizeMascot = (name) => {
    if (!name) return "default"; // handle null/undefined
    return name.trim().toLowerCase();
  };

  const [oppTeamCity, oppTeamMascot] = formatOpponent(opponent); // Split opponent into two parts
  const logoSrc = `/teamLogos/${normalizeMascot(oppTeamMascot) || "default"}.svg`;

  return (
    <div
      className={`${styles.card} ${isSelected ? styles.selected : ""}`} // Add 'selected' class if the card is selected
      onClick={onClick}
    >
      <div className={styles.date}>{date}</div>
      <div className={styles.location}>{location}</div>
      <img src={logoSrc} alt={`${oppTeamMascot} Team Logo`} className={styles.oppLogo} />
      <div className={styles.vs}>VS</div>
      <div className={styles.opponent}>
        <div className={styles.opponentPart1}>{oppTeamCity}</div>
        <div className={styles.opponentPart2}> {oppTeamMascot}</div>
      </div>
      <div className="game-score">
        <span className={styles.result}>{result}</span>
        {" "}
        <span className="score">
          {team_score}-{opp_score}
        </span>
      </div>
    </div>
  );
};
export default GameCard;
