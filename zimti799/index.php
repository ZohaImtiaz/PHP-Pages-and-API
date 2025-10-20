

<?php
// https://www.w3schools.com/css/css3_buttons.asp for styling navigation buttons and how to make it change colour when hovering over it.
//https://www.w3schools.com/css/css3_gradients.asp gradient for title

<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

// Fetch all users
$sql = "SELECT id, firstname, lastname FROM users ORDER BY lastname ASC";
$result = $pdo->query($sql);
$users = $result->fetchAll();
?>

<div class="container">
  <h2>Customers</h2>
  <hr style="border:1px solid #b23a48; margin:10px 0;">

  <?php if (count($users) > 0): ?>
    <ul class="company-list">
      <?php foreach ($users as $u): ?>
        <li>
          <strong><?= htmlspecialchars($u['lastname']) ?>, <?= htmlspecialchars($u['firstname']) ?></strong>
          <a class="btn small" href="portfolio.php?ref=<?= urlencode($u['id']) ?>">Portfolio</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="error">No users found in database.</p>
  <?php endif; ?>
</div>

<footer>
  © 2025 Stock Viewer — COMP 3512 Assignment 1
</footer>

</main>
</body>
</html>
