<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Arcade Games</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
    form { margin-bottom: 30px; }
    label { margin-right: 10px; }
    select, input[type="text"], input[type="number"] {
      margin-right: 20px; padding: 5px;
    }
    button { padding: 6px 12px; }
    .game {
      background: #fff; padding: 15px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 0 5px #ccc;
    }
  </style>
</head>
<body>
<h1>Search Arcade Games</h1>

<form method="GET">
  <input type="text" name="title" placeholder="Search by title" value="<?= htmlspecialchars($_GET['title'] ?? '') ?>">

  <label for="genre">Genre:</label>
  <select name="genre">
    <option value="">All</option>
    <?php
    $genres = $pdo->query("SELECT * FROM genres");
    foreach ($genres as $genre) {
      $selected = ($_GET['genre'] ?? '') == $genre['id'] ? 'selected' : '';
      echo "<option value='{$genre['id']}' $selected>{$genre['name']}</option>";
    }
    ?>
  </select>

  <label for="developer">Developer:</label>
  <select name="developer">
    <option value="">All</option>
    <?php
    $developers = $pdo->query("SELECT * FROM developers");
    foreach ($developers as $dev) {
      $selected = ($_GET['developer'] ?? '') == $dev['id'] ? 'selected' : '';
      echo "<option value='{$dev['id']}' $selected>{$dev['name']}</option>";
    }
    ?>
  </select>

  <label for="platform">Platform:</label>
  <select name="platform">
    <option value="">All</option>
    <?php
    $platforms = $pdo->query("SELECT * FROM platforms");
    foreach ($platforms as $plat) {
      $selected = ($_GET['platform'] ?? '') == $plat['id'] ? 'selected' : '';
      echo "<option value='{$plat['id']}' $selected>{$plat['name']}</option>";
    }
    ?>
  </select>

  <label for="year">Year:</label>
  <input type="number" name="year" placeholder="e.g., 1980" value="<?= htmlspecialchars($_GET['year'] ?? '') ?>">

  <button type="submit">Search</button>
</form>

<?php
// Build query with filters
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

$whereClause = '';
if (count($conditions) > 0) {
  $whereClause = 'WHERE ' . implode(' AND ', $conditions);
}

$sql = "SELECT games.*, genres.name AS genre, developers.name AS developer, platforms.name AS platform
        FROM games
        JOIN genres ON games.genre_id = genres.id
        JOIN developers ON games.developer_id = developers.id
        JOIN platforms ON games.platform_id = platforms.id
        $whereClause";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Display results
while ($game = $stmt->fetch()):
?>
  <div class="game">
    <h3><?= htmlspecialchars($game['title']) ?> (<?= $game['year'] ?>)</h3>
    <p>
      Genre: <?= htmlspecialchars($game['genre']) ?> |
      Developer: <?= htmlspecialchars($game['developer']) ?> |
      Platform: <?= htmlspecialchars($game['platform']) ?>
    </p>
    <iframe src="<?= htmlspecialchars($game['embed_url']) ?>" width="100%" height="400" allowfullscreen></iframe>
  </div>
<?php endwhile; ?>

</body>
</html>

