<?php
// inc/db.php
// PDO connection for SQLite. 

/* */
/*directory path to current file.*/
$dbPath = __DIR__ . '/../data/stocks.db'; 

/* if file doesnt exits at this path*/
if (!file_exists($dbPath)) {
    //show error mssg
    http_response_code(500);
    echo "Database file not found at: " . htmlspecialchars($dbPath);
    exit;
}


try {
    //object tells which database file to open
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    //internat server error mssf if try block fails 
    http_response_code(500);
     // Output a user safe version of the error message.
    echo "DB connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
