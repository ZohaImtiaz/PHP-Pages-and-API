<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

if (!isset($_GET['ref'])) {
    echo "<p class='error'>No user selected.</p>";
    exit;
}

$userId = intval($_GET['ref']);

$sql = "SELECT u.firstName, u.lastName, s.symbol, s.name, p.amount, 
               h.close AS latestClose
        FROM portfolio p
        JOIN users u ON u.userId = p.userId
        JOIN stocks s ON s.symbol = p.symbol
        JOIN history h ON h.symbol = s.symbol
        WHERE p.userId = :id
        GROUP BY s.symbol";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $userId]);
$rows = $stmt->fetchAll();
?>
<div class="container">
  <h2>Portfolio Details</h2>
  <?php if ($rows): ?>
  <table class="history-table">
    <thead>
      <tr>
        <th>Company</th>
        <th>Symbol</th>
        <th>Shares</th>
        <th>Last Close ($)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['symbol']) ?></td>
        <td><?= htmlspecialchars($r['amount']) ?></td>
        <td><?= htmlspecialchars(number_format($r['latestClose'], 2)) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <p>No portfolio data found for this user.</p>
  <?php endif; ?>
</div>
