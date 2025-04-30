export async function GET(request) {
  console.log("requst", request.url);
  const { searchParams } = new URL(request.url);
  const week = searchParams.get('week');
  console.log("week", week);
  const player_id = searchParams.get('player_id');

  let apiUrl = 'http://localhost:8000/player_game_ranks/rank.php';

  // Build query string dynamically
  const queryParams = new URLSearchParams();
  if (week) queryParams.append('week', week);
  if (player_id) queryParams.append('player_id', player_id);

  if (queryParams.toString()) {
    apiUrl += `?${queryParams.toString()}`;
  }

  const res = await fetch(apiUrl);
  const data = await res.json();

  return Response.json(data);
}