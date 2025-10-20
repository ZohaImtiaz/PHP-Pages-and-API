<?php include __DIR__ . '/inc/header.php'; ?>
<section class="container api-layout">
  <h2>API List</h2>
  <table class="history-table">
    <thead><tr><th>URL</th><th>Description</th></tr></thead>
    <tbody>
      <tr><td><a href="api/companies.php" target="_blank">/api/companies.php</a></td><td>Returns all companies/stocks</td></tr>
      <tr><td><a href="api/companies.php?ref=aapl" target="_blank">/api/companies.php?ref=aapl</a></td><td>Returns a specific company/stock</td></tr>
      <tr><td><a href="api/portfolio.php?ref=1" target="_blank">/api/portfolio.php?ref=1</a></td><td>Returns all portfolios for a specific customer</td></tr>
      <tr><td><a href="api/history.php?ref=aapl" target="_blank">/api/history.php?ref=aapl</a></td><td>Returns stock history for a specific company</td></tr>
    </tbody>
  </table>
  <p class="hint">Each URL above links to the API endpoint returning JSON data.</p>
</section>
<footer>
  © 2025 Stock Viewer — COMP 3512 Assignment 1
</footer>
</main>
</body>
</html>
