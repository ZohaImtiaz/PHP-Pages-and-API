<?php
// api/companies.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../inc/db.php';

try {
    if (isset($_GET['ref']) && trim($_GET['ref']) !== '') {
        $symbol = trim($_GET['ref']);
        $sql = "SELECT * FROM stocks WHERE symbol = :symbol LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':symbol' => $symbol]);
        $row = $stmt->fetch();
        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Company not found']);
            exit;
        }
        echo json_encode($row);
    } else {
        $sql = "SELECT * FROM stocks ORDER BY name";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();
        echo json_encode($rows);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

