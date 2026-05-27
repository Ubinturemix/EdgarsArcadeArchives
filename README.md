# Edgar's Arcade Archives

A retro arcade catalog web app built with PHP + PDO + SQL, featuring searchable game metadata and in-page gameplay embeds.

Designed to be demo-friendly: it uses MySQL as the primary database and automatically falls back to local SQLite if MySQL is unavailable.

## Features

- Browse classic arcade titles with image previews.
- Filter by title, genre, developer, platform, and year.
- Play games in-page via embedded URLs.
- Fullscreen support for gameplay iframe.
- TinyURL-safe gameplay launch via local `play.php` redirect endpoint.
- Dual search UIs:
  - `index.php` (styled "Arcade Vault" experience)
  - `search.php` (simple utility search page)

## Tech Stack

- **Backend/UI:** PHP (server-rendered pages)
- **Data Access:** PDO with prepared statements
- **Primary DB:** MySQL (`arcade_catalog`)
- **Fallback DB:** SQLite (`arcade_catalog.sqlite`)
- **Schema Source:** `arcade_schema.sql`

## Project Structure

- `index.php` - main UI and game cards
- `search.php` - alternate search/filter page
- `play.php` - resolves game launch URL and redirects for iframe compatibility
- `db.php` - DB connection + fallback logic + SQLite bootstrap
- `arcade_schema.sql` - MySQL schema and full seed data
- `arcade_catalog.sqlite` - local fallback database file (auto-created/used)

## Prerequisites

- PHP 8.1+ with PDO extensions
- Optional: MySQL server (for primary database mode)

## Quick Start (Demo Mode)

Use this if you need the app running fast for a presentation/interview.

```bash
cd /Users/edgarperez/Desktop/DBSFINALPROPEREZ
php -S 127.0.0.1:8000
```

Open:

- <http://127.0.0.1:8000/>
- <http://127.0.0.1:8000/search.php>

If MySQL is down, the app will still run using SQLite fallback data seeded from `arcade_schema.sql`.

## Run with MySQL (Primary Mode)

1) Create the database and load schema:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS arcade_catalog;"
mysql -u root -p arcade_catalog < arcade_schema.sql
```

2) Configure environment variables (optional; defaults shown):

```bash
export DB_HOST=localhost
export DB_NAME=arcade_catalog
export DB_USER=root
export DB_PASS=root
export DB_CHARSET=utf8mb4
```

3) Start server:

```bash
php -S 127.0.0.1:8000
```

## Verification Commands

Use these before a demo/interview:

```bash
php -l db.php && php -l index.php && php -l search.php
curl -I http://127.0.0.1:8000/
curl -I "http://127.0.0.1:8000/search.php?title=Pac&genre=1"
```

Expected result: HTTP `200` for both endpoints.

## 5-10 Minute Demo Script

1. Open home page and introduce the project goal.
2. Show filters (title/genre/developer/platform/year).
3. Search for "Pac" and explain dynamic query filtering.
4. Open a game with **Play Now**.
5. Trigger **Fullscreen** to show interactive UX.
6. Explain architecture: server-rendered PHP + normalized SQL schema.
7. Mention reliability: MySQL primary + SQLite fallback.

## Architecture Notes

- `db.php` initializes a single PDO connection.
- `play.php` prevents TinyURL iframe issues by redirecting to final game URLs.
- Query filtering is built dynamically using condition arrays and bound parameters.
- Data model is normalized:
  - `games` references `genres`, `platforms`, and `developers`.
- UI is rendered server-side, minimizing frontend complexity for class-project scope.

## Security and Reliability Notes

- Prepared statements are used for user-provided filters.
- Output is HTML-escaped in key dynamic text fields.
- App degrades gracefully to SQLite when MySQL is unavailable.

## Known Limitations (and Next Steps)

- No authentication/user session flow yet.
- No automated test suite yet (manual smoke checks documented above).
- Local SQLite fallback can differ slightly from MySQL if SQL dialect handling changes.
- Potential improvements:
  - Add pagination and DB indexing for scale.
  - Add integration tests for filtering behavior.
  - Add API/service layer if expanding beyond server-rendered pages.

## Interview Talking Points

- Why PDO + prepared statements matter (security + maintainability).
- Tradeoff between rapid server-rendered delivery and SPA complexity.
- Schema normalization and join strategy in search queries.
- Pragmatic resilience: fallback DB for demo continuity.

