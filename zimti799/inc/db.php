<?php

// correct path to your database file
$dbPath = __DIR__ . '/../data/stocks.db';


try {
    // Create a PDO connection to SQLite
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    http_response_code(500);
    echo "<p style='color:red; font-weight:bold;'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>
