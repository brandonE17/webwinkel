<?php

// laad init bestand
require_once 'includes/init.php';

// Check of bestelling succesvol was
if (!isset($_SESSION['order_placed'])) {
    header("Location: index.php");
    exit;
}

$order = $_SESSION['order_data'];
unset($_SESSION['order_placed']);
unset($_SESSION['order_data']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestellingbevestiging</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>✅ Bedankt voor je bestelling!</h1>
        </header>
        
        <main>
            <div class="confirmation-container">
                <div class="confirmation-message">
                    <h2>Je bestelling is geplaatst</h2>
                    <p>Je ontvangt binnenkort een bevestigingsmail op het volgende emailadres:</p>
                    <p class="email-display"><strong><?php echo $order['email']; ?></strong></p>
                </div>
                
                <div class="order-details">
                    <h3>Aflevergegevens</h3>
                    <p>
                        <strong><?php echo $order['voornaam'] . " " . $order['achternaam']; ?></strong><br>
                        <?php echo $order['adres']; ?><br>
                        <?php echo $order['postcode'] . " " . $order['plaats']; ?>
                    </p>
                </div>
                
                <div class="confirmation-actions">
                    <a href="index.php" class="btn">Verder winkelen</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
