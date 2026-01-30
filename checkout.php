<?php

// laad init bestand
require_once 'includes/init.php';

// VERWERK BESTELLING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $voornaam = htmlspecialchars($_POST['voornaam'] ?? '');
    $achternaam = htmlspecialchars($_POST['achternaam'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $adres = htmlspecialchars($_POST['adres'] ?? '');
    $plaats = htmlspecialchars($_POST['plaats'] ?? '');
    $postcode = htmlspecialchars($_POST['postcode'] ?? '');
    
    // Basis validatie
    if (!$voornaam || !$achternaam || !$email || !$adres || !$plaats || !$postcode) {
        $error = "Vul alle velden in!";
    } elseif (empty($winkelwagen->getItems())) {
        $error = "Winkelwagen is leeg!";
    } else {
        // Bestelling succesvol - in echte app zou je dit in database opslaan
        $_SESSION['order_placed'] = true;
        $_SESSION['order_data'] = [
            'voornaam' => $voornaam,
            'achternaam' => $achternaam,
            'email' => $email,
            'adres' => $adres,
            'plaats' => $plaats,
            'postcode' => $postcode
        ];
        $_SESSION['winkelwagen'] = new ShoppingCart();
        $winkelwagen = $_SESSION['winkelwagen'];
        header("Location: bevestiging.php");
        exit;
    }
}

// Bereken totaal en verzendkosten
$subtotaal = 0;
$verzendkosten = 0;

foreach ($winkelwagen->getItems() as $item) {
    $product = $item['product'];
    $quantity = $item['quantity'];
    $subtotaal += $product->getPrice() * $quantity;
    
    // Voeg verzendkosten toe voor fysieke producten
    if (method_exists($product, 'calculateShipping')) {
        $verzendkosten += $product->calculateShipping() * $quantity;
    }
}

$totaal = $subtotaal + $verzendkosten;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Afrekenen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>💳 Afrekenen</h1>
            <a href="cart.php" class="terug-link">← Terug</a>
        </header>
        
        <main>
            <?php if (empty($winkelwagen->getItems())): ?>
                <div class="lege-winkelwagen">
                    <p>Je winkelwagen is leeg. Je kunt niet afrekenen.</p>
                    <a href="index.php" class="btn">Ga winkelen</a>
                </div>
            <?php else: ?>
                <div class="checkout-container">
                    <!-- Links: Bestelling Samenvatting -->
                    <div class="checkout-summary">
                        <h2>Bestelling Samenvatting</h2>
                        <div class="order-items">
                            <?php foreach ($winkelwagen->getItems() as $item): ?>
                                <?php
                                    $product = $item['product'];
                                    $quantity = $item['quantity'];
                                    $prijs = $product->getPrice();
                                    $subtotaal_item = $prijs * $quantity;
                                ?>
                                <div class="order-item">
                                    <span><?php echo $product->getNaam(); ?> x<?php echo $quantity; ?></span>
                                    <span class="price">€<?php echo number_format($subtotaal_item, 2, ',', '.'); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-totals">
                            <div class="order-total-line">
                                <span>Subtotaal:</span>
                                <span>€<?php echo number_format($subtotaal, 2, ',', '.'); ?></span>
                            </div>
                            <div class="order-total-line">
                                <span>Verzendkosten:</span>
                                <span>€<?php echo number_format($verzendkosten, 2, ',', '.'); ?></span>
                            </div>
                            <div class="order-total-line total">
                                <span>Totaal:</span>
                                <span>€<?php echo number_format($totaal, 2, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rechts: Adresformulier -->
                    <div class="checkout-form">
                        <h2>Afleveradres</h2>
                        
                        <?php if (isset($error)): ?>
                            <div class="error-message">
                                ⚠️ <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="voornaam">Voornaam *</label>
                                    <input type="text" id="voornaam" name="voornaam" required class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="achternaam">Achternaam *</label>
                                    <input type="text" id="achternaam" name="achternaam" required class="form-input">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-mailadres *</label>
                                <input type="email" id="email" name="email" required class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label for="adres">Adres *</label>
                                <input type="text" id="adres" name="adres" placeholder="Straat + huisnummer" required class="form-input">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="postcode">Postcode *</label>
                                    <input type="text" id="postcode" name="postcode" placeholder="1234 AB" required class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="plaats">Plaats *</label>
                                    <input type="text" id="plaats" name="plaats" required class="form-input">
                                </div>
                            </div>
                            
                            <button type="submit" name="place_order" value="1" class="checkout-btn">
                                Bestelling plaatsen - €<?php echo number_format($totaal, 2, ',', '.'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
