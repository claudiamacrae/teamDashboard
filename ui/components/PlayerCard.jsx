const PlayerCard = ({ player }) => {
  return (
    <div className="player-card">
      <h3 className="text-lg font-semibold mb-2">
        {player.first_name} {player.last_name}
      </h3>
      <p>
        <strong>Position:</strong> {player.position}
      </p>
      <p>
        <strong>Height:</strong> {player.height}
      </p>
      <p>
        <strong>Weight:</strong> {player.weight}
      </p>
      <p>
        <strong>Age:</strong> {player.age}
      </p>
      <p>
        <strong>College:</strong> {player.college}
      </p>
      <div className="player-graph">{"graph"}</div>
    </div>
  );
};
export default PlayerCard;
