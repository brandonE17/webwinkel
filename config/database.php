<?php

// Database configuratie - AANPASBAAR
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webwinkel');

// Globale database verbinding
$db = null;

// Functie om database verbinding te maken
function connectDatabase() {
    global $db;
    
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8mb4';
        
        $db = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 5
            ]
        );
        
        return true;
    } catch (PDOException $e) {
        // Stille mislukkingsfallback
        return false;
    }
}

// Functie om database aan te maken
function setupDatabase() {
    global $db;
    
    try {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8mb4';
        $temp_db = new PDO($dsn, DB_USER, DB_PASS);
        
        // Maak database aan
        $temp_db->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Selecteer database
        $temp_db->exec("USE " . DB_NAME);
        
        // Maak tabellen aan
        $temp_db->exec("
            CREATE TABLE IF NOT EXISTS producten (
                id INT PRIMARY KEY AUTO_INCREMENT,
                naam VARCHAR(255) NOT NULL,
                prijs DECIMAL(10, 2) NOT NULL,
                categorie VARCHAR(100) NOT NULL,
                type ENUM('physical', 'digital', 'discount') NOT NULL DEFAULT 'physical',
                gewicht DECIMAL(8, 2),
                bestandsgrootte INT,
                korting_procent INT,
                beschrijving TEXT,
                actief BOOLEAN DEFAULT 1,
                aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                bijgewerkt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");
        
        // Voeg voorbeelddata toe (als leeg)
        $result = $temp_db->query("SELECT COUNT(*) as count FROM producten");
        $count = $result->fetch()['count'];
        
        if ($count == 0) {
            $temp_db->exec("
                INSERT INTO producten (naam, prijs, categorie, type, gewicht, beschrijving) VALUES
                ('PHP Boek', 49.99, 'Boeken', 'physical', 0.5, 'Leer PHP programmeren'),
                ('Gaming Mouse', 69.99, 'Hardware', 'physical', 0.3, 'Precisie muis voor gamers'),
                ('PHP Cursus', 79.99, 'Cursussen', 'digital', NULL, 'Online PHP cursus met video''s'),
                ('Web Bundle', 199.99, 'Bundels', 'discount', NULL, 'Complete web development bundle')
            ");
            
            $temp_db->exec("UPDATE producten SET korting_procent = 15 WHERE naam = 'Web Bundle'");
            $temp_db->exec("UPDATE producten SET bestandsgrootte = 250 WHERE naam = 'PHP Cursus'");
        }
        
        // Maak orders tabel
        $temp_db->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT PRIMARY KEY AUTO_INCREMENT,
                order_id VARCHAR(50) UNIQUE NOT NULL,
                voornaam VARCHAR(100) NOT NULL,
                achternaam VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                telefoon VARCHAR(20),
                adres VARCHAR(255) NOT NULL,
                postcode VARCHAR(10) NOT NULL,
                plaats VARCHAR(100) NOT NULL,
                subtotaal DECIMAL(10, 2) NOT NULL,
                verzendkosten DECIMAL(10, 2) NOT NULL,
                totaal DECIMAL(10, 2) NOT NULL,
                status VARCHAR(50) DEFAULT 'verwerkt',
                aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Maak order_items tabel
        $temp_db->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                id INT PRIMARY KEY AUTO_INCREMENT,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                hoeveelheid INT NOT NULL,
                prijs DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(id)
            )
        ");
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Probeer verbinding te maken
if (!connectDatabase()) {
    // Probeer setup uit te voeren
    @setupDatabase();
    // Probeer opnieuw verbinding
    @connectDatabase();
}

// Admin credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

?>
