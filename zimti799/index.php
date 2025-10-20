

<?php
// https://www.w3schools.com/css/css3_buttons.asp for styling navigation buttons and how to make it change colour when hovering over it.

require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

$sql = "SELECT userId, firstName, lastName FROM users";
$result = $pdo->query($sql);
?>

<div class="container">
  <h2>Users</h2>
  <ul class="company-list">
    <?php foreach ($result as $u): ?>
      <li>
        <strong><?= htmlspecialchars($u['firstName'] . ' ' . $u['lastName']) ?></strong>
        <a class="btn small" href="portfolio.php?ref=<?= urlencode($u['userId']) ?>">Portfolio</a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>

<footer>
  © 2025 Stock Viewer — COMP 3512 Assignment 1
</footer>
</main>
</body>
</html>
