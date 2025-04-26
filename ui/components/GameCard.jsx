const GameCard = ({ opponent, location, date, team_score, opp_score, isSelected, onClick }) => {
  const isWin = team_score > opp_score; // Determine if the game was a win or loss
    return (
    <div
      className={`card ${isSelected ? "selected" : ""}`} // Add 'selected' class if the card is selected
      onClick={onClick}
    >
      <h1>{opponent}</h1>
      <h2>{location}</h2>
      <h2>{date}</h2>
      <h2>{team_score} - {opp_score}</h2>
    </div>
  );
};
export default GameCard;
