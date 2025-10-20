<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

if (!isset($_GET['symbol']) || trim($_GET['symbol']) === '') {
    echo '<section class="container"><p>No symbol provided. Go back to <a href="index.php">home</a>.</p></section>';
    echo "</main></body></html>";
    exit;
}
$symbol = trim($_GET['symbol']);

try {
    // Company details
    $sql = "SELECT * FROM stocks WHERE symbol = :symbol LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':symbol' => $symbol]);
    $company = $stmt->fetch();

    if (!$company) {
        echo '<section class="container"><p>Company not found. <a href="index.php">Back to home</a>.</p></section>';
        echo "</main></body></html>";
        exit;
    }

    // History
    $sql = "SELECT date, open, close, high, low, volume FROM history WHERE symbol = :symbol ORDER BY date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':symbol' => $symbol]);
    $history = $stmt->fetchAll();
} catch (Exception $e) {
    echo '<section class="container"><p>Error: ' . htmlspecialchars($e->getMessage()) . '</p></section>';
    echo "</main></body></html>";
    exit;
}
?>

<section class="container">
  <h2><?php echo htmlspecialchars($company['name']); ?> <small class="muted">(<?php echo htmlspecialchars($company['symbol']); ?>)</small></h2>

  <?php if (!empty($company['industry'])): ?>
    <p><strong>Industry:</strong> <?php echo htmlspecialchars($company['industry']); ?></p>
  <?php endif; ?>

  <?php if (!empty($company['summary'])): ?>
    <p><strong>Summary:</strong><br><?php echo nl2br(htmlspecialchars($company['summary'])); ?></p>
  <?php endif; ?>

  <h3>Historical Prices</h3>
  <?php if (empty($history)): ?>
    <p>No historical data found for this company.</p>
  <?php else: ?>
    <table class="history-table">
      <thead>
        <tr><th>Date</th><th>Open</th><th>High</th><th>Low</th><th>Close</th><th>Volume</th></tr>
      </thead>
      <tbody>
        <?php foreach ($history as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['open']); ?></td>
            <td><?php echo htmlspecialchars($row['high']); ?></td>
            <td><?php echo htmlspecialchars($row['low']); ?></td>
            <td><?php echo htmlspecialchars($row['close']); ?></td>
            <td><?php echo htmlspecialchars($row['volume']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

</main>
</body>
</html>
