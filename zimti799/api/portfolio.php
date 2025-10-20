<?php
// api/portfolio.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../inc/db.php';

if (!isset($_GET['ref']) || trim($_GET['ref']) === '') {
    http_response_code(400);
    echo json_encode(['error' => 'ref (userId) required']);
    exit;
}
$userId = (int) $_GET['ref'];

try {
    // Basic portfolio details joined with stock names.
    $sql = "
        SELECT p.userId, p.symbol, p.amount,
               s.name AS stock_name
        FROM portfolio p
        JOIN stocks s ON p.symbol = s.symbol
        WHERE p.userId = :userId
        ORDER BY s.name
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    $rows = $stmt->fetchAll();

    echo json_encode($rows);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
