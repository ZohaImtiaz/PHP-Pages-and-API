<?php
require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

$symbol = $_GET['ref'] ?? null;
$company = null;
$history = [];

if ($symbol) {
    // Get company info
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE symbol = :symbol");
    $stmt->execute([':symbol' => $symbol]);
    $company = $stmt->fetch();

    // Get history (latest first)
    $stmt2 = $pdo->prepare("SELECT * FROM history WHERE symbol = :symbol ORDER BY date DESC");
    $stmt2->execute([':symbol' => $symbol]);
    $history = $stmt2->fetchAll();

    // Summary values
    if ($history) {
        $high = max(array_column($history, 'high'));
        $low = min(array_column($history, 'low'));
        $totalVolume = array_sum(array_column($history, 'volume'));
        $avgVolume = round($totalVolume / count($history));
    }
}
?>

<div class="container company-layout">
  <?php if (!$symbol): ?>
    <p class="error">No company selected. Please choose one from the portfolio page.</p>

  <?php elseif (!$company): ?>
    <p class="error">No company found with symbol “<?= htmlspecialchars($symbol) ?>”.</p>

  <?php else: ?>
    <h2 class="section-title">Company Info</h2>

    <div class="company-info">
      <p><strong>Symbol:</strong> <?= htmlspecialchars($company['symbol']) ?></p>
      <p><strong>Name:</strong> <?= htmlspecialchars($company['name']) ?></p>
      <p><strong>Sector:</strong> <?= htmlspecialchars($company['sector']) ?></p>
      <p><strong>Subindustry:</strong> <?= htmlspecialchars($company['subindustry']) ?></p>
      <p><strong>Address:</strong> <?= htmlspecialchars($company['address']) ?></p>
      <p><strong>Exchange:</strong> <?= htmlspecialchars($company['exchange']) ?></p>
      <p><strong>Website:</strong> 
        <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank">
          <?= htmlspecialchars($company['website']) ?>
        </a>
      </p>
      <p><strong>Description:</strong> <?= htmlspecialchars($company['description']) ?></p>

      <?php
      // Convert JSON string to readable financial table
      if (!empty($company['financials'])) {
          $financials = json_decode($company['financials'], true);
          if (is_array($financials)) {
              echo "<h3>Financials</h3>";
              echo "<table class='history-table'>";
              echo "<thead><tr><th>Year</th><th>Earnings</th><th>Assets</th><th>Liabilities</th></tr></thead><tbody>";
              foreach ($financials as $year => $data) {
                  echo "<tr>
                        <td>$year</td>
                        <td>" . htmlspecialchars($data['earnings'] ?? 'N/A') . "</td>
                        <td>" . htmlspecialchars($data['assets'] ?? 'N/A') . "</td>
