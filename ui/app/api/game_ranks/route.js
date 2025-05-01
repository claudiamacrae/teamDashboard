export async function GET(request) {
  console.log("requst", request.url);
  const { searchParams } = new URL(request.url);
  const week = searchParams.get("week");
  const player_id = searchParams.get("player_id");

  let apiUrl = "http://localhost:8000/player_game_ranks/rank.php";
  if (week) {
    apiUrl += `?week=${encodeURIComponent(week)}`;
  }
  if (player_id) {
    apiUrl += `&player_id=${encodeURIComponent(player_id)}`;
  }

  try {
    const res = await fetch(apiUrl);
    const data = await res.json();
    return Response.json(data);
  } catch (error) {
    return Response.json(
      { error: error.message || "An unexpected error occurred." },
      { status: 500 }
    );
  }
}
