

<?php
// https://www.w3schools.com/css/css3_buttons.asp for styling navigation buttons and how to make it change colour when hovering over it.
//https://www.w3schools.com/css/css3_gradients.asp gradient for title

require_once __DIR__ . '/inc/db.php';
include __DIR__ . '/inc/header.php';

$userRef = $_GET['ref'] ?? null;

// Fetch all users
$users = $pdo->query("SELECT id, firstname, lastname FROM users ORDER BY lastname ASC")->fetchAll();

// If user selected, fetch their portfolio
$portfolio = [];
$summary = [];

if ($userRef) {
    // Get user's portfolio records
    $stmt = $pdo->prepare("
        SELECT p.symbol, s.name, s.sector, p.amount,
               h.close AS latest_close,
               ROUND(p.amount * h.close, 2) AS value
        FROM portfolio p
        JOIN companies s ON p.symbol = s.symbol
        JOIN (
            SELECT symbol, MAX(date) AS maxDate FROM history GROUP BY symbol
        ) latest ON p.symbol = latest.symbol
        JOIN history h ON h.symbol = latest.symbol AND h.date = latest.maxDate
        WHERE p.userId = :uid
        ORDER BY s.name;
    ");
    $stmt->execute([':uid' => $userRef]);
    $portfolio = $stmt->fetchAll();

    // Summary
    if ($portfolio) {
        $summary['companies'] = count($portfolio);
        $summary['shares'] = array_sum(array_column($portfolio, 'amount'));
        $summary['value'] = array_sum(array_column($portfolio, 'value'));
    }
}
?>

<div class="container" style="display: flex; gap: 20px; align-items: flex-start;">
    <!-- Left: Customers list -->
    <div style="flex: 1; background: var(--card); border: 1px solid #d3bfa4; border-radius: 8px; padding: 16px;">
        <h2>Customers</h2>
        <ul class="company-list">
            <?php foreach ($users as $u): ?>
                <li>
                    <strong><?= htmlspecialchars($u['lastname']) ?>, <?= htmlspecialchars($u['firstname']) ?></strong>
                    <a class="btn small" href="index.php?ref=<?= urlencode($u['id']) ?>">Portfolio</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p style="color: var(--muted); font-size: 0.9rem; margin-top: 12px;">
            List of all customers in the users table, sorted by last name.
        </p>
    </div>

    <!-- Right: Portfolio details -->
    <div style="flex: 2; background: var(--card); border: 1px solid #d3bfa4; border-radius: 8px; padding: 16px;">
        <?php if (!$userRef): ?>
            <p style="color: var(--muted); text-align: center;">Select a customer to view their portfolio.</p>
        <?php elseif (!$portfolio): ?>
            <p class="error">This user has no portfolio data.</p>
        <?php else: ?>
            <h2>Portfolio Summary</h2>
            <div class="summary-grid">
                <div class="summary-box">
                    <h3>Companies</h3>
                    <p><?= $summary['companies'] ?></p>
                </div>
                <div class="summary-box">
                    <h3># Shares</h3>
                    <p><?= $summary['shares'] ?></p>
                </div>
                <div class="summary-box">
                    <h3>Total Value</h3>
                    <p>$<?= number_format($summary['value'], 2) ?></p>
                </div>
            </div>

            <h3>Portfolio Details</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Symbol</th>
                        <th>Name</th>
                        <th>Sector</th>
                        <th>Amount</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($portfolio as $row): ?>
                        <tr>
                            <td><a href="company.php?ref=<?= urlencode($row['symbol']) ?>"><?= htmlspecialchars($row['symbol']) ?></a></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['sector']) ?></td>
                            <td><?= htmlspecialchars($row['amount']) ?></td>
                            <td>$<?= number_format($row['value'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<footer>
  © 2025 Stock Viewer — COMP 3512 Assignment 1
</footer>

</main>
</body>
</html>
