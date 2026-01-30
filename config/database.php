<?php

// Database configuratie
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webwinkel');

// Maak verbinding
try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database verbindingsfout: " . $e->getMessage());
}

// Admin credential (simpele demonstratie - in productie: gehashed!)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

?>
