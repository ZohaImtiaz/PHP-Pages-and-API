<?php
// inc/db.php
// PDO connection for SQLite. 

$dbPath = __DIR__ . '/../data/stocks.db'; 

if (!file_exists($dbPath)) {
    http_response_code(500);
    echo "Database file not found at: " . htmlspecialchars($dbPath);
    exit;
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo "DB connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
