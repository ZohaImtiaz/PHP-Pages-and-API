<?php

// correct path to your database file
$dbPath = __DIR__ . '/../data/stocks.db';

// Check if database file exists
if (!file_exists($dbPath)) {
    http_response_code(500);
    echo "<p style='color:red; font-weight:bold;'>❌ Database file not found at: " . htmlspecialchars($dbPath) . "</p>";
    exit;
}

// confirm which database file PHP
echo "<p style='color:red; font-weight:bold;'>DEBUG: Using database at: " . htmlspecialchars($dbPath) . "</p>";

try {
    // Create a PDO connection to SQLite
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    //  number of users in DB
    $count = $pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'];
    echo "<p style='color:red;'>DEBUG: Found $count users in the database.</p>";

} catch (PDOException $e) {
    http_response_code(500);
    echo "<p style='color:red; font-weight:bold;'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
