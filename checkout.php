<?php

// laad init bestand
require_once 'includes/init.php';

// Validatiefunctie
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePostcode($postcode) {
    // Nederlands/Belgisch postcode format (1234 AB of 1234AB)
    return preg_match('/^[0-9]{4}\s?[A-Z]{2}$/i', trim($postcode));
}

function validatePhoneNumber($phone) {
    // Telefoonnummer met cijfers en spaties/streepjes
    return preg_match('/^[\d\s\-\+\(\)]{7,}$/', trim($phone));
}

function validateName($name) {
    // Minimaal 2 karakters, alleen letters en spaties
    return preg_match('/^[a-zA-ZÀ-ÿ\s]{2,}$/u', trim($name));
}

// VERWERK BESTELLING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $voornaam = htmlspecialchars(trim($_POST['voornaam'] ?? ''));
    $achternaam = htmlspecialchars(trim($_POST['achternaam'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $adres = htmlspecialchars(trim($_POST['adres'] ?? ''));
    $plaats = htmlspecialchars(trim($_POST['plaats'] ?? ''));
    $postcode = htmlspecialchars(trim($_POST['postcode'] ?? ''));
    $telefoon = htmlspecialchars(trim($_POST['telefoon'] ?? ''));
    
    $errors = [];
    
    // Validaties
    if (!$voornaam) {
        $errors[] = "Voornaam is verplicht.";
    } elseif (!validateName($voornaam)) {
        $errors[] = "Voornaam mag alleen letters bevatten (minimaal 2 karakters).";
    }
    
    if (!$achternaam) {
        $errors[] = "Achternaam is verplicht.";
    } elseif (!validateName($achternaam)) {
        $errors[] = "Achternaam mag alleen letters bevatten (minimaal 2 karakters).";
    }
    
    if (!$email) {
        $errors[] = "E-mailadres is verplicht.";
    } elseif (!validateEmail($email)) {
        $errors[] = "Voer een geldig e-mailadres in (bijv. naam@voorbeeld.nl).";
    }
    
    if (!$adres) {
        $errors[] = "Adres is verplicht.";
    } elseif (strlen($adres) < 5) {
        $errors[] = "Adres moet minstens 5 karakters lang zijn.";
    }
    
    if (!$plaats) {
        $errors[] = "Plaats is verplicht.";
    } elseif (!validateName($plaats)) {
        $errors[] = "Plaats mag alleen letters bevatten.";
    }
    
    if (!$postcode) {
        $errors[] = "Postcode is verplicht.";
    } elseif (!validatePostcode($postcode)) {
        $errors[] = "Voer een geldige postcode in (bijv. 1234 AB).";
    }
    
    if ($telefoon && !validatePhoneNumber($telefoon)) {
        $errors[] = "Voer een geldig telefoonnummer in.";
    }
    
    if (empty($winkelwagen->getItems())) {
        $errors[] = "Je winkelwagen is leeg!";
    }
    
    if (empty($errors)) {
        // Bestelling succesvol - voeg toe aan ordergeschiedenis
        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }
        
        $order_id = uniqid('ORDER_');
        $order_details = [
            'order_id' => $order_id,
            'voornaam' => $voornaam,
            'achternaam' => $achternaam,
            'email' => $email,
            'adres' => $adres,
            'plaats' => $plaats,
            'postcode' => $postcode,
            'telefoon' => $telefoon,
            'items' => $winkelwagen->getItems(),
            'subtotaal' => 0,
            'verzendkosten' => 0,
            'totaal' => 0,
            'datum' => date('Y-m-d H:i:s')
        ];
        
        // Bereken totalen voor order
        foreach ($order_details['items'] as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $order_details['subtotaal'] += $product->getPrice() * $quantity;
            if (method_exists($product, 'calculateShipping')) {
                $order_details['verzendkosten'] += $product->calculateShipping() * $quantity;
            }
        }
        $order_details['totaal'] = $order_details['subtotaal'] + $order_details['verzendkosten'];
        
        $_SESSION['orders'][] = $order_details;
        $_SESSION['order_placed'] = true;
        $_SESSION['order_data'] = $order_details;
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
                    <p>Je winkelwagen is leeg.. Je kunt niet afrekenen.</p>
                    <a href="index.php" class="btn">Ga winkelen</a>
                </div>
            <?php else: ?>
                <div class="checkout-container">
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
                    
                    <!-- Adresformulier -->
                    <div class="checkout-form">
                        <h2>Afleveradres</h2>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="error-container">
                                <h3>⚠️ Controleer de volgende fouten:</h3>
                                <ul class="error-list">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="voornaam">Voornaam *</label>
                                    <input type="text" id="voornaam" name="voornaam" required class="form-input" value="<?php echo isset($voornaam) ? $voornaam : ''; ?>" pattern="[a-zA-ZÀ-ÿ\s]{2,}">
                                </div>
                                <div class="form-group">
                                    <label for="achternaam">Achternaam *</label>
                                    <input type="text" id="achternaam" name="achternaam" required class="form-input" value="<?php echo isset($achternaam) ? $achternaam : ''; ?>" pattern="[a-zA-ZÀ-ÿ\s]{2,}">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-mailadres *</label>
                                <input type="email" id="email" name="email" required class="form-input" value="<?php echo isset($email) ? $email : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="telefoon">Telefoonnummer</label>
                                <input type="tel" id="telefoon" name="telefoon" class="form-input" placeholder="+31 6 12345678" value="<?php echo isset($telefoon) ? $telefoon : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="adres">Adres *</label>
                                <input type="text" id="adres" name="adres" placeholder="Straat + huisnummer" required class="form-input" value="<?php echo isset($adres) ? $adres : ''; ?>" minlength="5">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="postcode">Postcode *</label>
                                    <input type="text" id="postcode" name="postcode" placeholder="1234 AB" required class="form-input" value="<?php echo isset($postcode) ? $postcode : ''; ?>" pattern="[0-9]{4}\s?[A-Z]{2}">
                                </div>
                                <div class="form-group">
                                    <label for="plaats">Plaats *</label>
                                    <input type="text" id="plaats" name="plaats" required class="form-input" value="<?php echo isset($plaats) ? $plaats : ''; ?>" pattern="[a-zA-ZÀ-ÿ\s]{2,}">
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
