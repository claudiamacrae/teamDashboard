import { getApiBaseUrl } from "@/utils/getApiBaseUrl";
export async function GET() {
  const baseUrl = getApiBaseUrl();
  const res = await fetch(`${baseUrl}/player_season_ranks/index.php`);
  const data = await res.json();

  return Response.json(data);
}
