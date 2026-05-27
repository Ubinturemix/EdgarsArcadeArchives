<?php
declare(strict_types=1);

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];

$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: 'arcade_catalog';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'root';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';
$sqlitePath = __DIR__ . '/arcade_catalog.sqlite';

try {
  $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Throwable $mysqlError) {
  // Demo-safe fallback when MySQL is unavailable.
  $pdo = new PDO("sqlite:{$sqlitePath}", null, null, $options);
  initializeSqliteIfNeeded($pdo);
}

function initializeSqliteIfNeeded(PDO $pdo): void
{
  $tableExists = (int) $pdo->query("
    SELECT COUNT(*)
    FROM sqlite_master
    WHERE type = 'table' AND name = 'games'
  ")->fetchColumn();

  if ($tableExists > 0) {
    return;
  }

  $pdo->exec("
    CREATE TABLE genres (
      id INTEGER PRIMARY KEY,
      name TEXT NOT NULL
    );

    CREATE TABLE platforms (
      id INTEGER PRIMARY KEY,
      name TEXT NOT NULL
    );

    CREATE TABLE developers (
      id INTEGER PRIMARY KEY,
      name TEXT NOT NULL
    );

    CREATE TABLE games (
      id INTEGER PRIMARY KEY,
      title TEXT NOT NULL,
      year INTEGER NOT NULL,
      genre_id INTEGER,
      platform_id INTEGER,
      developer_id INTEGER,
      embed_url TEXT,
      image_url TEXT,
      FOREIGN KEY (genre_id) REFERENCES genres(id),
      FOREIGN KEY (platform_id) REFERENCES platforms(id),
      FOREIGN KEY (developer_id) REFERENCES developers(id)
    );

    CREATE TABLE user_favorites (
      id INTEGER PRIMARY KEY,
      user TEXT,
      game_id INTEGER,
      FOREIGN KEY (game_id) REFERENCES games(id)
    );
  ");

  seedSqliteFromSchema($pdo);
}

function seedSqliteFromSchema(PDO $pdo): void
{
  $schemaPath = __DIR__ . '/arcade_schema.sql';
  if (!is_file($schemaPath)) {
    throw new RuntimeException('arcade_schema.sql not found for SQLite seed.');
  }

  $schemaSql = (string) file_get_contents($schemaPath);
  if ($schemaSql === '') {
    throw new RuntimeException('arcade_schema.sql is empty.');
  }

  if (!preg_match_all('/INSERT INTO\\s+(genres|platforms|developers|games)\\s*\\([^;]+;/is', $schemaSql, $matches)) {
    throw new RuntimeException('No INSERT statements found in arcade_schema.sql.');
  }

  foreach ($matches[0] as $insertStatement) {
    // Convert MySQL-style escaped apostrophes to SQLite-safe escapes.
    $sqliteStatement = str_replace("\\'", "''", $insertStatement);
    $pdo->exec($sqliteStatement);
  }
}
?>
