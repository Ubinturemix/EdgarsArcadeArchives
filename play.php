<?php
declare(strict_types=1);

require __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(400);
  echo "Missing or invalid game id.";
  exit;
}

$stmt = $pdo->prepare("SELECT embed_url FROM games WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();

if (!$row || empty($row['embed_url'])) {
  http_response_code(404);
  echo "Game not found.";
  exit;
}

$url = (string) $row['embed_url'];

// TinyURL commonly blocks being embedded in iframes; resolve to the final destination.
if (preg_match('/^https?:\\/\\/tinyurl\\.com\\//i', $url)) {
  $resolved = resolveKnownTinyUrl($url) ?? resolveRedirectUrl($url);
  if ($resolved !== null) {
    $url = $resolved;
  }
}

header('Location: ' . $url, true, 302);
exit;

function resolveKnownTinyUrl(string $url): ?string
{
  // Hardcoded map for the demo seed set so it works even if runtime redirect
  // resolution is blocked by network policies.
  static $map = [
    'https://tinyurl.com/2yuy5j34' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyUGFjLU1hbiUyMiUyQyUyMmFwcCUyMiUzQSUyMkFyY2FkZSUyMiUyQyUyMmljb24lMjIlM0ElMjJodHRwcyUzQSUyRiUyRnJhdy5naXRodWJ1c2VyY29udGVudC5jb20lMkZ3ZWJyY2FkZS1hc3NldHMlMkZ3ZWJyY2FkZS1hc3NldHMtZmJuZW8taW1hZ2VzJTJGbWFzdGVyJTJGTmFtZWRfVGl0bGVzJTJGcmVzaXplZCUyRlBhYy1NYW4lMjUyMChib290bGVnJTI1MkMlMjUyMFZpZGVvJTI1MjBHYW1lJTI1MjBTQSkucG5nJTIyJTJDJTIycm9tJTIyJTNBJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZHJvcGJveC5jb20lMkZzY2wlMkZmaSUyRmpiODFmODdwa2c3bHd6am8yaHBnZCUyRnBhY21hbi56aXAlM0ZybGtleSUzRDJyNG9taDIycmR1YzQyYjM0cnk0NDRtMHUlMjZzdCUzRDR6Z3RianNhJTI2ZGwlM0QwJTIyJTJDJTIyYWRkaXRpb25hbFJvbXMlMjIlM0ElNUIlMjJodHRwcyUzQSUyRiUyRnd3dy5kcm9wYm94LmNvbSUyRnNjbCUyRmZpJTJGamI4MWY4N3BrZzdsd3pqbzJocGdkJTJGcGFjbWFuLnppcCUzRnJsa2V5JTNEMnI0b21oMjJyZHVjNDJiMzRyeTQ0NG0wdSUyNnN0JTNENHpndGJqc2ElMjZkbCUzRDAlMjIlMkMlMjJodHRwcyUzQSUyRiUyRnd3dy5kcm9wYm94LmNvbSUyRnNjbCUyRmZpJTJGOWR0bjg3enZhNmMxYXA2MWJuNjh5JTJGcHVja21hbi56aXAlM0ZybGtleSUzRHJwbnZvMWpvendzampveDZldjNsYnVzNjklMjZkbCUzRDAlMjIlNUQlN0Q%3D&ctx=standalone',
    'https://tinyurl.com/27h2nr7c' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyR2FsYWdhJTIyJTJDJTIyYXBwJTIyJTNBJTIyQXJjYWRlJTIyJTJDJTIyaWNvbiUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGcmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSUyRndlYnJjYWRlLWFzc2V0cyUyRndlYnJjYWRlLWFzc2V0cy1mYm5lby1pbWFnZXMlMkZtYXN0ZXIlMkZOYW1lZF9UaXRsZXMlMkZyZXNpemVkJTJGR2FsYWdhJTI1MjAoTWlkd2F5JTI1MjBzZXQlMjUyMDElMjUyMHdpdGglMjUyMGZhc3QlMjUyMHNob290JTI1MjBoYWNrKS5wbmclMjIlMkMlMjJyb20lMjIlM0ElMjJodHRwcyUzQSUyRiUyRnd3dy5kcm9wYm94LmNvbSUyRnNjbCUyRmZpJTJGZmUydTg2enF0NDc0cWZ4czkwdGw4JTJGZ2FsYWdhLnppcCUzRnJsa2V5JTNEMmdsc3dtYjZoZ2tpOHhuc3hoNW5wbXZlcyUyNnN0JTNENXR3cWs4d2olMjZkbCUzRDAlMjIlN0Q%3D&ctx=standalone',
    'https://tinyurl.com/23hu62kh' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyU3RyZWV0JTIwRmlnaHRlciUyMElJJTIwLSUyMENoYW1waW9uJTIwRWRpdGlvbiUyMiUyQyUyMmFwcCUyMiUzQSUyMkFyY2FkZSUyMiUyQyUyMmljb24lMjIlM0ElMjJodHRwcyUzQSUyRiUyRnJhdy5naXRodWJ1c2VyY29udGVudC5jb20lMkZ3ZWJyY2FkZS1hc3NldHMlMkZ3ZWJyY2FkZS1hc3NldHMtZmJuZW8taW1hZ2VzJTJGbWFzdGVyJTJGTmFtZWRfVGl0bGVzJTJGcmVzaXplZCUyRlN0cmVldCUyNTIwRmlnaHRlciUyNTIwSUklMjUyMC0lMjUyMENoYW1waW9uJTI1MjBFZGl0aW9uJTI1MjAoQWxwaGElMjUyME1hZ2ljLUYlMjUyMGJvb3RsZWclMjUyMHNldCUyNTIwMSUyNTJDJTI1MjA5MjAzMTMlMjUyMGV0YykucG5nJTIyJTJDJTIycm9tJTIyJTNBJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZHJvcGJveC5jb20lMkZzY2wlMkZmaSUyRnR4aXFkdzB6a3o2ODNteWl3b3loOCUyRnNmMmNlLnppcCUzRnJsa2V5JTNEemw4YWt2bDc4bzBtZWg2MDIzeTlxZzRzZCUyNnN0JTNEajRhcnB2anUlMjZkbCUzRDAlMjIlN0Q%3D&ctx=standalone',
    'https://tinyurl.com/22jfqm83' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyRG9ua2V5JTIwS29uZyUyMiUyQyUyMmFwcCUyMiUzQSUyMkFyY2FkZSUyMiUyQyUyMmljb24lMjIlM0ElMjJodHRwcyUzQSUyRiUyRnJhdy5naXRodWJ1c2VyY29udGVudC5jb20lMkZ3ZWJyY2FkZS1hc3NldHMlMkZ3ZWJyY2FkZS1hc3NldHMtZmJuZW8taW1hZ2VzJTJGbWFzdGVyJTJGTmFtZWRfVGl0bGVzJTJGcmVzaXplZCUyRkRvbmtleSUyNTIwS29uZyUyNTIwKDI2MDAlMjUyMGdyYXBoaWNzJTI1MkMlMjUyMGhhY2spLnBuZyUyMiUyQyUyMnJvbSUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGd3d3LmRyb3Bib3guY29tJTJGc2NsJTJGZmklMkZubWNkamJmYnlpMG42ZGl4cjF0ZHElMkZka29uZy56aXAlM0ZybGtleSUzRGtsa2lmOTFoZmc4anBrZDlpZ3g0MHh0ZGklMjZzdCUzRGo4bW03em50JTI2ZGwlM0QwJTIyJTdE&ctx=standalone',
    'https://tinyurl.com/2773nufa' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyQnViYmxlJTIwQm9iYmxlJTIyJTJDJTIyYXBwJTIyJTNBJTIyQXJjYWRlJTIyJTJDJTIyaWNvbiUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGcmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSUyRndlYnJjYWRlLWFzc2V0cyUyRndlYnJjYWRlLWFzc2V0cy1mYm5lby1pbWFnZXMlMkZtYXN0ZXIlMkZOYW1lZF9UaXRsZXMlMkZyZXNpemVkJTJGQnViYmxlJTI1MjBCb2JibGUlMjUyMChib29sdGVnJTI1MjB3aXRoJTI1MjA2ODcwNSUyNTJDJTI1MjBzZXQlMjUyMDEpLnBuZyUyMiUyQyUyMnJvbSUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGd3d3LmRyb3Bib3guY29tJTJGc2NsJTJGZmklMkY3MXl1amptaG83Y3gyY203MDdnbjglMkZidWJsYm9ibC56aXAlM0ZybGtleSUzRHN0amV1MmxkYjB2N3N0NnRpNnVybXcxcmclMjZzdCUzRDUwM20wbTYzJTI2ZGwlM0QwJTIyJTdE&ctx=standalone',
    'https://tinyurl.com/26v9wpj5' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyRnJvZ2dlciUyMiUyQyUyMmFwcCUyMiUzQSUyMkFyY2FkZSUyMiUyQyUyMmljb24lMjIlM0ElMjJodHRwcyUzQSUyRiUyRnJhdy5naXRodWJ1c2VyY29udGVudC5jb20lMkZ3ZWJyY2FkZS1hc3NldHMlMkZ3ZWJyY2FkZS1hc3NldHMtZmJuZW8taW1hZ2VzJTJGbWFzdGVyJTJGTmFtZWRfVGl0bGVzJTJGcmVzaXplZCUyRkZyb2dnZXIucG5nJTIyJTJDJTIycm9tJTIyJTNBJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZHJvcGJveC5jb20lMkZzY2wlMkZmaSUyRmgxN2tuZ3hxbGJvZ2Z0ZTIwdTBnOCUyRmZyb2dnZXIuemlwJTNGcmxrZXklM0R4ZW1ldmxhY290ZzNmZG9qdHNyOW4zOGU3JTI2c3QlM0RwdXIzb2tldiUyNmRsJTNEMCUyMiU3RA%3D%3D&ctx=standalone',
    'https://tinyurl.com/25q27ckn' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyQXN0ZXJvaWRzJTIyJTJDJTIyYXBwJTIyJTNBJTIyQXJjYWRlJTIyJTJDJTIyaWNvbiUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGcmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSUyRndlYnJjYWRlLWFzc2V0cyUyRndlYnJjYWRlLWFzc2V0cy1mYm5lby1pbWFnZXMlMkZtYXN0ZXIlMkZOYW1lZF9UaXRsZXMlMkZyZXNpemVkJTJGQXN0ZXJvaWRzJTI1MjAoYm9vdGxlZyUyNTIwb24lMjUyMEx1bmFyJTI1MjBMYW5kZXIlMjUyMGhhcmR3YXJlJTI1MkMlMjUyMHNldCUyNTIwMSkucG5nJTIyJTJDJTIycm9tJTIyJTNBJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZHJvcGJveC5jb20lMkZzY2wlMkZmaSUyRmIwbGk3cHdma3g1NHBkcmR0MGxhMCUyRmFzdGVyb2lkLnppcCUzRnJsa2V5JTNEaHJ6YjZ5aWV1ZXBzN2E4bXB4aWpkcHlsayUyNnN0JTNEdTl1Z3JzdnIlMjZkbCUzRDAlMjIlN0Q%3D&ctx=standalone',
    'https://tinyurl.com/23y78s79' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyRmluYWwlMjBGaWdodCUyMiUyQyUyMmFwcCUyMiUzQSUyMkFyY2FkZSUyMiUyQyUyMmljb24lMjIlM0ElMjJodHRwcyUzQSUyRiUyRnJhdy5naXRodWJ1c2VyY29udGVudC5jb20lMkZ3ZWJyY2FkZS1hc3NldHMlMkZ3ZWJyY2FkZS1hc3NldHMtZmJuZW8taW1hZ2VzJTJGbWFzdGVyJTJGTmFtZWRfVGl0bGVzJTJGcmVzaXplZCUyRkZpbmFsJTI1MjBGaWdodCUyNTIwKDkwMDExMiUyNTIwSmFwYW4pLnBuZyUyMiUyQyUyMnJvbSUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGd3d3LmRyb3Bib3guY29tJTJGc2NsJTJGZmklMkZvNTdkMjl2MXY3M2d1Zm92NDkzMGglMkZmZmlnaHQuemlwJTNGcmxrZXklM0RrempxN2RkZ2g1Y2d5dTFreGJjaXV0bGJ2JTI2c3QlM0Q5amc3eWI1bSUyNmRsJTNEMCUyMiU3RA%3D%3D&ctx=standalone',
    'https://tinyurl.com/29e73sgz' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyTWFydmVsJTIwdnMlMjBDYXBjb20lMjAtJTIwY2xhc2glMjBvZiUyMHN1cGVyJTIwaGVyb2VzJTIyJTJDJTIyYXBwJTIyJTNBJTIyQXJjYWRlJTIyJTJDJTIyaWNvbiUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGcmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSUyRndlYnJjYWRlLWFzc2V0cyUyRndlYnJjYWRlLWFzc2V0cy1mYm5lby1pbWFnZXMlMkZtYXN0ZXIlMkZOYW1lZF9UaXRsZXMlMkZyZXNpemVkJTJGTWFydmVsJTI1MjB2cyUyNTIwQ2FwY29tJTI1MjAtJTI1MjBjbGFzaCUyNTIwb2YlMjUyMHN1cGVyJTI1MjBoZXJvZXMlMjUyMCg5NzEyMjIlMjUyMFVTQSkucG5nJTIyJTJDJTIycm9tJTIyJTNBJTIyaHR0cHMlM0ElMkYlMkZ3d3cuZHJvcGJveC5jb20lMkZzY2wlMkZmaSUyRmdkeGlmMXQwcGxpazltNGRjZDNpZiUyRm12c2MuemlwJTNGcmxrZXklM0QweDZwdW1tanJkd2k1cDQxaWJ0OWkzbTh1JTI2c3QlM0RrNDI5d29hYiUyNmRsJTNEMCUyMiU3RA%3D%3D&ctx=standalone',
    'https://tinyurl.com/26fshker' => 'https://play.webrcade.com/app/standalone/?app=app%2Fneo%2F&props=JTdCJTIydHlwZSUyMiUzQSUyMmZibmVvLWFyY2FkZSUyMiUyQyUyMnRpdGxlJTIyJTNBJTIyVGhlJTIwS2luZyUyMG9mJTIwRmlnaHRlcnMlMjAnOTglMjAtJTIwVGhlJTIwU2x1Z2Zlc3QlMjAlMkYlMjBLaW5nJTIwb2YlMjBGaWdodGVycyUyMCc5OCUyMC0lMjBkcmVhbSUyMG1hdGNoJTIwbmV2ZXIlMjBlbmRzJTIyJTJDJTIyYXBwJTIyJTNBJTIyQXJjYWRlJTIyJTJDJTIyaWNvbiUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGcmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSUyRndlYnJjYWRlLWFzc2V0cyUyRndlYnJjYWRlLWFzc2V0cy1uZW9nZW8taW1hZ2VzJTJGbWFzdGVyJTJGTmFtZWRfVGl0bGVzJTJGcmVzaXplZCUyRlRoZSUyNTIwS2luZyUyNTIwb2YlMjUyMEZpZ2h0ZXJzJTI1MjAnOTglMjUyMC0lMjUyMFRoZSUyNTIwU2x1Z2Zlc3QlMjUyMF8lMjUyMEtpbmclMjUyMG9mJTI1MjBGaWdodGVycyUyNTIwJzk4JTI1MjAtJTI1MjBkcmVhbSUyNTIwbWF0Y2glMjUyMG5ldmVyJTI1MjBlbmRzJTI1MjAoS29yZWFuJTI1MjBib2FyZCUyNTJDJTI1MjBzZXQlMjUyMDEpLnBuZyUyMiUyQyUyMnJvbSUyMiUzQSUyMmh0dHBzJTNBJTJGJTJGd3d3LmRyb3Bib3guY29tJTJGc2NsJTJGZmklMkZlMG0xM2R6d2t6NDY1MTBtbGFnYXYlMkZrb2Y5OC56aXAlM0ZybGtleSUzRDlodWlkYnZ4YXAxc20weGYwNmt6eXQ2enIlMjZzdCUzRDM2cnh1cDlqJTI2ZGwlM0QwJTIyJTJDJTIyYWRkaXRpb25hbFJvbXMlMjIlM0ElNUIlMjJodHRwcyUzQSUyRiUyRnd3dy5kcm9wYm94LmNvbSUyRnNjbCUyRmZpJTJGa3lwYmthZ2ZndTZheWhsNnoyeHZmJTJGbmVvZ2VvLnppcCUzRnJsa2V5JTNEN3Vobm5xNTQwcnh4c3pkamhreGtvOWo2aiUyNnN0JTNEeGhqYzhyMGQlMjZkbCUzRDAlMjIlNUQlN0Q%3D&ctx=standalone',
  ];

  if (isset($map[$url])) {
    return $map[$url];
  }

  $normalized = preg_replace('/\\?.*$/', '', $url);
  if (is_string($normalized) && isset($map[$normalized])) {
    return $map[$normalized];
  }

  return null;
}

function resolveRedirectUrl(string $url): ?string
{
  if (!function_exists('curl_init')) {
    return null;
  }

  $ch = curl_init($url);
  if ($ch === false) {
    return null;
  }

  curl_setopt_array($ch, [
    CURLOPT_NOBODY => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 5,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_USERAGENT => 'EdgarsArcadeArchives/1.0',
  ]);

  curl_exec($ch);
  $effective = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
  curl_close($ch);

  if (!is_string($effective) || $effective === '') {
    return null;
  }

  return $effective;
}

