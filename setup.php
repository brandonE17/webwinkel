<?php
/**
 * Setup pagina voor webwinkel
 * Voert database setup uit
 */

session_start();

// Controleer of MySQL draait en verbind
require_once 'config/database.php';

$status = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'setup') {
    if (setupDatabase()) {
        connectDatabase();
        if ($db !== null) {
            $success = 'Database succesvol aangemaakt en ingesteld!';
            $_SESSION['setup_complete'] = true;
        } else {
            $error = 'Database aangemaakt maar kon niet verbinden. Check XAMPP MySQL service.';
        }
    } else {
        $error = 'Kon database niet aanmaken. Start MySQL in XAMPP Control Panel.';
    }
}

// Controleer database status
if ($db === null) {
    $status = 'VERBINDING VERBROKEN';
} else {
    try {
        $result = $db->query("SELECT COUNT(*) as count FROM producten");
        $products = $result->fetch()['count'];
        $status = 'VERBONDEN ✓';
        $success = $success ?: "Database actief met {$products} producten.";
    } catch (Exception $e) {
        $status = 'TABEL NIET GEVONDEN';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webwinkel Setup</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .status-box {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            color: #2e7d32;
        }
        .status-box.error {
            background: #ffebee;
            border-color: #f44336;
            color: #c62828;
        }
        .info {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #1565c0;
            line-height: 1.6;
        }
        .info h3 {
            margin-top: 0;
            color: #1565c0;
        }
        button {
            background: #4caf50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        button:hover {
            background: #45a049;
        }
        .steps {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #856404;
        }
        .steps h3 {
            margin-top: 0;
        }
        .steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .steps li {
            margin: 8px 0;
        }
        a {
            color: #4caf50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .success {
            background: #c8e6c9;
            border-color: #4caf50;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #2e7d32;
        }
        .error {
            background: #ffcdd2;
            border-color: #f44336;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Webwinkel Setup</h1>
        
        <div class="status-box <?php echo $status === 'VERBONDEN ✓' ? '' : 'error'; ?>">
            Status: <?php echo htmlspecialchars($status); ?>
        </div>

        <?php if ($success): ?>
            <div class="success">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error">✗ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($status === 'VERBONDEN ✓'): ?>
            <div class="info">
                <h3>✓ Alles klaar!</h3>
                <p>Je webwinkel database is gereed. Je kunt nu:</p>
                <ul>
                    <li><a href="index.php">Terug naar winkel →</a></li>
                    <li><a href="admin.php">Admin panel openen →</a> (Login: admin / admin123)</li>
                </ul>
            </div>
        <?php else: ?>
            <div class="steps">
                <h3>⚠️ Database Setup Vereist</h3>
                <p><strong>Stap 1:</strong> Start MySQL in XAMPP</p>
                <ol>
                    <li>Open XAMPP Control Panel (start_xampp.exe in c:\xamppp)</li>
                    <li>Klik op "Start" bij MySQL module</li>
                    <li>Wacht tot het groen wordt (draait op poort 3306)</li>
                </ol>
                <p><strong>Stap 2:</strong> Database aanmaken</p>
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="setup">
                <button type="submit">Database Aanmaken en Setup</button>
            </form>

            <div class="info" style="margin-top: 20px;">
                <h3>🔧 Troubleshooting</h3>
                <p><strong>Error: "Kan geen verbinding maken"?</strong></p>
                <ul>
                    <li>MySQL service draait niet → Start het in XAMPP Control Panel</li>
                    <li>Poort 3306 is al in gebruik → Check of ander programma het gebruikt</li>
                    <li>Wachtwoord onjuist → Edit config/database.php met correct wachtwoord</li>
                </ul>
                <p><strong>Wil je handmatig setup?</strong></p>
                <ul>
                    <li>Open phpMyAdmin: <a href="http://localhost/phpmyadmin/">http://localhost/phpmyadmin/</a></li>
                    <li>Maak database "webwinkel" aan</li>
                    <li>Import database.sql bestand</li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>