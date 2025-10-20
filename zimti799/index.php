<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY lastName ASC");
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    $users = [];
    $error = $e->getMessage();
}
?>
<section class="container main-layout">
  <aside class="sidebar">
    <h2>Customers</h2>
    <table class="customer-table">
      <thead><tr><th>Name</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['lastName'] . ', ' . $u['firstName']) ?></td>
          <td><a class="btn small" href="portfolio.php?userId=<?= $u['userId'] ?>">Portfolio</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p class="hint">List of all the customers in the users table, sorted by last name.</p>
  </aside>

  <section class="content-area">
    <div class="placeholder">
      <p><em>Provide message that user needs to select a customer’s portfolio.</em></p>
    </div>
  </section>
</section>

</main>
</body>
</html>
