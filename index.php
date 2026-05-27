<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Arcade Vault</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <style>
    body {
  background: linear-gradient(-45deg, #0a0014, #1c0033, #2e004f, #0a0014);
  background-size: 400% 400%;
  animation: darkPulse 15s ease infinite;
  height: 100vh;
  margin: 0;
  font-family: 'Press Start 2P', monospace;
  color: white;
}

@keyframes darkPulse {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}



    }

    h1, .glow-text {
  color: #00bfff; /* Bright neon blue */
  text-shadow:
    0 0 5px #00bfff,
    0 0 10px #00bfff,
    0 0 20px #00bfff,
    0 0 40px #00bfff;
}


    form {
      margin-bottom: 2rem;
    }

    select, input {
      margin-right: 10px;
      padding: 0.4rem;
      font-family: inherit;
    }

    button {
      padding: 0.4rem 1rem;
      font-family: inherit;
      background-color: #0ff;
      color: #000;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 0 8px #0ff;
    }

    .grid {
      display: grid;
      gap: 2rem;
    }

    .game-card {
      border: 2px solid #0ff;
      border-radius: 10px;
      padding: 1rem;
      background-color: #111;
      box-shadow: 0 0 15px rgba(0, 255, 255, 0.3);
    }

    .game-card h2 {
      color: #fff;
    }


    .game-card img {
      width: 300px;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .game-card button {
      padding: 0.5rem 1rem;
      background-color: #0ff;
      color: #000;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 1rem;
      box-shadow: 0 0 10px #0ff;
    }

    .fullscreen-btn {
      padding: 0.25rem 0.75rem;
      background-color: #0ff;
      color: #000;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.8rem;
      font-weight: bold;
      box-shadow: 0 0 6px #0ff;
      margin-bottom: 0.5rem;
    }

    .iframe-wrapper {
      margin-top: 1rem;
    }

    iframe {
      border: 2px solid #0ff;
      border-radius: 10px;
      box-shadow: 0 0 20px #0ff;
      background-color: #000;
      width: 100%;
      height: 600px;
    }
  </style>
</head>
<body>

<h1>Edgar's Arcade Archives</h1>

<!-- 🔍 SEARCH AND FILTER FORM -->
<form method="GET">
  <input type="text" name="title" placeholder="Search title" value="<?= htmlspecialchars($_GET['title'] ?? '') ?>">

  <select name="genre">
    <option value="">All Genres</option>
    <?php
      $genres = $pdo->query("SELECT * FROM genres");
      foreach ($genres as $g) {
        $selected = ($_GET['genre'] ?? '') == $g['id'] ? 'selected' : '';
        echo "<option value='{$g['id']}' $selected>{$g['name']}</option>";
      }
    ?>
  </select>

  <select name="developer">
    <option value="">All Developers</option>
    <?php
      $devs = $pdo->query("SELECT * FROM developers");
      foreach ($devs as $d) {
        $selected = ($_GET['developer'] ?? '') == $d['id'] ? 'selected' : '';
        echo "<option value='{$d['id']}' $selected>{$d['name']}</option>";
      }
    ?>
  </select>

  <select name="platform">
    <option value="">All Platforms</option>
    <?php
      $plats = $pdo->query("SELECT * FROM platforms");
      foreach ($plats as $p) {
        $selected = ($_GET['platform'] ?? '') == $p['id'] ? 'selected' : '';
        echo "<option value='{$p['id']}' $selected>{$p['name']}</option>";
      }
    ?>
  </select>

  <input type="number" name="year" placeholder="Year" value="<?= htmlspecialchars($_GET['year'] ?? '') ?>">

  <button type="submit">Search</button>
</form>

<!--  GAME GRID -->
<div class="grid">
<?php
  // Build query dynamically
  $conditions = [];
  $params = [];

  if (!empty($_GET['title'])) {
    $conditions[] = "games.title LIKE ?";
    $params[] = "%" . $_GET['title'] . "%";
  }
  if (!empty($_GET['genre'])) {
    $conditions[] = "games.genre_id = ?";
    $params[] = $_GET['genre'];
  }
  if (!empty($_GET['developer'])) {
    $conditions[] = "games.developer_id = ?";
    $params[] = $_GET['developer'];
  }
  if (!empty($_GET['platform'])) {
    $conditions[] = "games.platform_id = ?";
    $params[] = $_GET['platform'];
  }
  if (!empty($_GET['year'])) {
    $conditions[] = "games.year = ?";
    $params[] = $_GET['year'];
  }

  $where = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

  $stmt = $pdo->prepare("SELECT games.*, genres.name AS genre, platforms.name AS platform, developers.name AS developer 
                         FROM games 
                         JOIN genres ON games.genre_id = genres.id 
                         JOIN platforms ON games.platform_id = platforms.id 
                         JOIN developers ON games.developer_id = developers.id 
                         $where");
  $stmt->execute($params);

  while ($game = $stmt->fetch()):
?>
  <div class="game-card">
    <h2><?= htmlspecialchars($game['title']) ?> (<?= $game['year'] ?>)</h2>
    <img src="<?= $game['image_url'] ?>" alt="<?= htmlspecialchars($game['title']) ?>">
    <p><strong>Genre:</strong> <?= htmlspecialchars($game['genre']) ?></p>
    <p><strong>Developer:</strong> <?= htmlspecialchars($game['developer']) ?></p>
    <p><strong>Platform:</strong> <?= htmlspecialchars($game['platform']) ?></p>
    <button onclick="toggleIframe(<?= $game['id'] ?>)">Play Now</button>
    <div style="text-align: right;">
      <button class="fullscreen-btn" onclick="handleFullscreen(<?= $game['id'] ?>)">⛶ Fullscreen</button>
    </div>
    <div id="frame-container-<?= $game['id'] ?>" class="iframe-wrapper"></div>
  </div>
<?php endwhile; ?>
</div>

<script>
  let activeGameId = null;

  const gameUrls = {
<?php
  $stmt2 = $pdo->query("SELECT id, embed_url FROM games");
  while ($row = $stmt2->fetch()):
?>
    <?= $row['id'] ?>: "<?= $row['embed_url'] ?>",
<?php endwhile; ?>
  };

  function toggleIframe(id) {
    const container = document.getElementById("frame-container-" + id);
    if (container.innerHTML.trim() !== "") {
      container.innerHTML = "";
      activeGameId = null;
    } else {
      document.querySelectorAll(".iframe-wrapper").forEach(div => div.innerHTML = "");
      const iframe = document.createElement("iframe");
      iframe.id = "game-" + id;
      iframe.src = gameUrls[id];
      iframe.width = "100%";
      iframe.height = "600";
      iframe.style.border = "2px solid #0ff";
      iframe.style.borderRadius = "10px";
      iframe.style.boxShadow = "0 0 20px #0ff";
      iframe.style.backgroundColor = "#000";
      iframe.allowFullscreen = true;
      container.appendChild(iframe);
      activeGameId = id;
    }
  }

  function handleFullscreen(gameId) {
    const iframe = document.getElementById("game-" + gameId);
    if (iframe?.requestFullscreen) iframe.requestFullscreen();
    else if (iframe?.webkitRequestFullscreen) iframe.webkitRequestFullscreen();
    else if (iframe?.mozRequestFullScreen) iframe.mozRequestFullScreen();
    else if (iframe?.msRequestFullscreen) iframe.msRequestFullscreen();
  }
</script>

</body>
</html>
