<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

// Fetch companies for listing
try {
    $sql = "SELECT symbol, name FROM stocks ORDER BY name";
    $stmt = $pdo->query($sql);
    $companies = $stmt->fetchAll();
} catch (Exception $e) {
    $companies = [];
    $error = $e->getMessage();
}
?>
<section class="container">
  <h2>Welcome</h2>
  <p>This site is Assignment #1 for COMP3512 at Mount Royal University. Browse companies below.</p>

  <?php if (!empty($error)): ?>
    <div class="error">Error fetching companies: <?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <ul class="company-list">
    <?php foreach ($companies as $c): ?>
      <li>
        <a href="company.php?symbol=<?php echo urlencode($c['symbol']); ?>">
          <?php echo htmlspecialchars($c['name']); ?> <span class="muted">(<?php echo htmlspecialchars($c['symbol']); ?>)</span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

  <section class="notes">
    <h3>Notes</h3>
    <p>APIs (not used by pages in this assignment) are available under the <code>/api/</code> folder. See the API Tester link in the header to view sample JSON outputs.</p>
  </section>
</section>

</main>
</body>
</html>

