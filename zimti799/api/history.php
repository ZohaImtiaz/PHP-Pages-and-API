<?php
// api/history.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../inc/db.php';

if (!isset($_GET['ref']) || trim($_GET['ref']) === '') {
    http_response_code(400);
    echo json_encode(['error' => 'ref (symbol) required']);
    exit;
}
$symbol = trim($_GET['ref']);

try {
    $sql = "SELECT date, open, close, high, low, volume FROM history WHERE symbol = :symbol ORDER BY date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':symbol' => $symbol]);
    $rows = $stmt->fetchAll();
    echo json_encode($rows);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

