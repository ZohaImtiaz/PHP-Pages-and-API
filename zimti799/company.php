<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

$symbol = $_GET['symbol'] ?? '';
$company = $pdo->prepare("SELECT * FROM stocks WHERE symbol = ?");
$company->execute([$symbol]);
$c = $company->fetch();

$history = [];
if ($c) {
    $stmt = $pdo->prepare("SELECT * FROM history WHERE symbol = ? ORDER BY date DESC");
    $stmt->execute([$symbol]);
    $history = $stmt->fetchAll();

    $high = max(array_column($history, 'high'));
    $low = min(array_column($history, 'low'));
    $totalVol = array_sum(array_column($history, 'volume'));
    $avgVol = round($totalVol / count($history));
}
?>
<section class="container company-layout">
  <?php if (!$c): ?>
    <div class="placeholder"><p>Company not found.</p></div>
  <?php else: ?>
    <h2><?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['symbol']) ?>)</h2>
    <div class="company-info">
      <p><strong>Sector:</strong> <?= htmlspecialchars($c['sector']) ?></p>
      <p><strong>Industry:</strong> <?= htmlspecialchars($c['subIndustry']) ?></p>
      <p><strong>Exchange:</strong> <?= htmlspecialchars($c['exchange']) ?></p>
      <p><strong>Website:</strong> <a href="<?= htmlspecialchars($c['website']) ?>" target="_blank"><?= htmlspecialchars($c['website']) ?></a></p>
    </div>

    <div class="stats-row">
      <div class="summary-box"><h3>History High</h3><p>$<?= number_format($high, 2) ?></p></div>
      <div class="summary-box"><h3>History Low</h3><p>$<?= number_format($low, 2) ?></p></div>
      <div class="summary-box"><h3>Total Volume</h3><p><?= number_format($totalVol) ?></p></div>
      <div class="summary-box"><h3>Average Volume</h3><p><?= number_format($avgVol) ?></p></div>
    </div>

    <h3>History (3 M)</h3>
    <table class="history-table">
      <thead><tr><th>Date</th><th>Volume</th><th>Open</th><th>Close</th><th>High</th><th>Low</th></tr></thead>
      <tbody>
        <?php foreach ($history as $h): ?>
          <tr>
            <td><?= htmlspecialchars($h['date']) ?></td>
            <td><?= number_format($h['volume']) ?></td>
            <td>$<?= number_format($h['open'], 2) ?></td>
            <td>$<?= number_format($h['close'], 2) ?></td>
            <td>$<?= number_format($h['high'], 2) ?></td>
            <td>$<?= number_format($h['low'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<footer>
  © Stock Viewer — COMP 3512 Assignment 1
</footer>

</main>
</body>
</html>
