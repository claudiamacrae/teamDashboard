import { getApiBaseUrl } from "@/utils/getApiBaseUrl";
export async function GET() {
  const baseUrl = getApiBaseUrl();
  const res = await fetch(`${baseUrl}/games/index.php`);
  const data = await res.json();

  return Response.json(data);
}
