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

  $pdo->exec("
    INSERT INTO genres (id, name) VALUES
      (1, 'Maze'),
      (2, 'Shooter'),
      (3, 'Platformer'),
      (4, 'Fighting'),
      (5, 'Puzzle');

    INSERT INTO platforms (id, name) VALUES
      (1, 'Arcade');

    INSERT INTO developers (id, name) VALUES
      (1, 'Namco'),
      (2, 'Nintendo'),
      (3, 'Taito'),
      (4, 'Capcom'),
      (5, 'Atari Inc.'),
      (6, 'Konami'),
      (12, 'Data East'),
      (18, 'SNK');

    INSERT INTO games (id, title, year, genre_id, platform_id, developer_id, embed_url, image_url) VALUES
      (1, 'Pac-Man', 1980, 1, 1, 1, 'https://tinyurl.com/2yuy5j34', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Pac-Man%20(bootleg%2C%20Video%20Game%20SA).png'),
      (2, 'Galaga', 1981, 2, 1, 1, 'https://tinyurl.com/27h2nr7c', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Galaga%20(Midway%20set%201%20with%20fast%20shoot%20hack).png'),
      (3, 'Street Fighter II', 1991, 4, 1, 4, 'https://tinyurl.com/23hu62kh', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Street%20Fighter%20II%20-%20Champion%20Edition%20(Alpha%20Magic-F%20bootleg%20set%201%2C%20920313%20etc).png'),
      (4, 'Donkey Kong', 1981, 3, 1, 2, 'https://tinyurl.com/22jfqm83', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Donkey%20Kong%20(2600%20graphics%2C%20hack).png'),
      (5, 'Bubble Bobble', 1986, 3, 1, 3, 'https://tinyurl.com/2773nufa', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Bubble%20Bobble%20(boolteg%20with%2068705%2C%20set%201).png'),
      (6, 'Frogger', 1981, 1, 1, 6, 'https://tinyurl.com/26v9wpj5', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Frogger.png'),
      (7, 'Asteroids', 1979, 2, 1, 5, 'https://tinyurl.com/25q27ckn', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Asteroids%20(bootleg%20on%20Lunar%20Lander%20hardware%2C%20set%201).png'),
      (8, 'Final Fight', 1989, 4, 1, 4, 'https://tinyurl.com/23y78s79', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Final%20Fight%20(900112%20Japan).png'),
      (9, 'Marvel vs Capcom', 1998, 4, 1, 4, 'https://tinyurl.com/29e73sgz', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-fbneo-images/master/Named_Snaps/output/Marvel%20vs%20Capcom%20-%20clash%20of%20super%20heroes%20(971222%20USA).png'),
      (10, 'The King of Fighters 98', 1998, 4, 1, 18, 'https://tinyurl.com/26fshker', 'https://raw.githubusercontent.com/webrcade-assets/webrcade-assets-neogeo-images/master/Named_Snaps/output/The%20King%20of%20Fighters%20%2798%20-%20The%20Slugfest%20_%20King%20of%20Fighters%20%2798%20-%20dream%20match%20never%20ends%20(Korean%20board%2C%20set%201).png');
  ");
}
?>
