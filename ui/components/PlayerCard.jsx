import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  Tooltip,
  ResponsiveContainer,
} from "recharts";
import { useEffect , useState } from "react";

const PlayerCard = ({ player }) => {
  const [rankData, setRankData] = useState([]);
  const maxRank = 60;

  // Fetch game ranks for the player
  useEffect(() => {
    const fetchGameRanks = async () => {
      try {
        const response = await fetch(`/api/game_ranks/?player_id=${player.id}`);
        if (!response.ok) {
          throw new Error("Failed to fetch game ranks");
        }
        const data = await response.json();

        // Transform the data into the required format for the chart
        const transformedData = data.map((rankData, index) => ({
          week: index + 1,
          rank: rankData.rank,
          barHeight: maxRank - rankData.rank, // Invert rank for bar height
        }));
        console.log("Transformed data:", transformedData);
        setRankData(transformedData);
      } catch (error) {
        console.error("Error fetching game ranks:", error);
      }
    };

    fetchGameRanks();
  }, [player.id]);

  return (
    <div className="player-card border rounded-xl shadow-md overflow-hidden">
      <div className="bg-[#940000] p-2 text-white relative">
        <div className="player-graph" style={{ width: "100%", height: 100 }}>
          <ResponsiveContainer width="100%" height="100%">
            <BarChart data={rankData}>
              <YAxis hide domain={[1, 60]} />
              <XAxis hide dataKey="week" />
              <Tooltip
                cursor={{ fill: "transparent" }}
                content={({ active, payload }) => {
                  if (active && payload && payload.length) {
                    const { rank, week } = payload[0].payload;
                    return (
                      <div className="bg-white border p-2 text-xs shadow-md rounded">
                        <div>Week {week}</div>
                        <div>rank: {rank}</div>
                      </div>
                    );
                  }
                  return null;
                }}
              />
              <Bar
                dataKey="barHeight"
                fill="var(--gold) "
                barSize={10}
                activeBar={{
                  fill: "#ffffff",
                }}
              />
            </BarChart>
          </ResponsiveContainer>
        </div>
        <h3 className="text-lg font-semibold mb-2">
          {player.first_name} {player.last_name}
        </h3>
      </div>
    </div>
  );
};
export default PlayerCard;
