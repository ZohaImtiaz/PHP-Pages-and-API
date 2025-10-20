<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

$userId = isset($_GET['userId']) ? (int) $_GET['userId'] : 0;

// Fetch user
$user = $pdo->prepare("SELECT * FROM users WHERE userId = ?");
$user->execute([$userId]);
$user = $user->fetch();

// Fetch portfolio entries
$stmt = $pdo->prepare("SELECT p.*, s.name, s.symbol, s.sector
                       FROM portfolio p
                       JOIN stocks s ON p.symbol = s.symbol
                       WHERE p.userId = ?");
$stmt->execute([$userId]);
$rows = $stmt->fetchAll();

// Portfolio summary calculations
$companies = count($rows);
$shares = array_sum(array_column($rows, 'amount'));
$totalValue = 0;
foreach ($rows as $r) {
    $latest = $pdo->prepare("SELECT close FROM history WHERE symbol=? ORDER BY date DESC LIMIT 1");
    $latest->execute([$r['symbol']]);
    $close = $latest->fetchColumn();
    $totalValue += $close * $r['amount'];
}
?>
<section class="container main-layout">
  <aside class="sidebar">
    <h2>Customers</h2>
    <table class="customer-table">
      <thead><tr><th>Name</th><th></th></tr></thead>
      <tbody>
        <?php
        $list = $pdo->query("SELECT * FROM users ORDER BY lastName ASC")->fetchAll();
        foreach ($list as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['lastName'] . ', ' . $u['firstName']) ?></td>
          <td><a class="btn small" href="portfolio.php?userId=<?= $u['userId'] ?>">Portfolio</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </aside>

  <section class="content-area">
    <?php if (!$user): ?>
      <div class="placeholder"><p>No user selected.</p></div>
    <?php else: ?>
      <h2><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>’s Portfolio</h2>

      <div class="summary-grid">
        <div class="summary-box"><h3>Companies</h3><p><?= $companies ?></p></div>
        <div class="summary-box"><h3># Shares</h3><p><?= $shares ?></p></div>
        <div class="summary-box"><h3>Total Value</h3><p>$<?= number_format($totalValue, 2) ?></p></div>
      </div>

      <h3>Portfolio Details</h3>
      <table class="history-table">
        <thead><tr><th>Symbol</th><th>Name</th><th>Sector</th><th>Amount</th><th>Value</th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): 
            $latest = $pdo->prepare("SELECT close FROM history WHERE symbol=? ORDER BY date DESC LIMIT 1");
            $latest->execute([$r['symbol']]);
            $close = $latest->fetchColumn();
            $value = $close * $r['amount']; ?>
            <tr>
              <td><a href="company.php?symbol=<?= urlencode($r['symbol']) ?>"><?= htmlspecialchars($r['symbol']) ?></a></td>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= htmlspecialchars($r['sector']) ?></td>
              <td><?= $r['amount'] ?></td>
              <td>$<?= number_format($value, 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>
</section>

</main>
</body>
</html>
