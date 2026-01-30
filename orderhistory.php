<?php

// laad init bestand
require_once 'includes/init.php';

// Haal ordergeschiedenis op
$orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : [];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Bestellingen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Mijn Bestellingen</h1>
            <a href="index.php" class="terug-link">← Terug</a>
        </header>
        
        <main>
            <?php if (empty($orders)): ?>
                <div class="lege-winkelwagen">
                    <p>Je hebt nog geen bestellingen geplaatst.</p>
                    <a href="index.php" class="btn">Ga winkelen</a>
                </div>
            <?php else: ?>
                <div class="orders-container">
                    <h2>Je hebt <?php echo count($orders); ?> bestelling(en)</h2>
                    
                    <?php foreach (array_reverse($orders) as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Bestelling #<?php echo substr($order['order_id'], -8); ?></h3>
                                <p class="order-date">📅 <?php echo date('d-m-Y H:i', strtotime($order['datum'])); ?></p>
                            </div>
                            <div class="order-status">
                                <span class="status-badge">✅ Verwerkt</span>
                                <p class="order-total">€<?php echo number_format($order['totaal'], 2, ',', '.'); ?></p>
                            </div>
                        </div>
                        
                        <div class="order-details">
                            <div class="customer-info">
                                <h4>Afleveradres</h4>
                                <p>
                                    <strong><?php echo htmlspecialchars($order['voornaam'] . " " . $order['achternaam']); ?></strong><br>
                                    <?php echo htmlspecialchars($order['adres']); ?><br>
                                    <?php echo htmlspecialchars($order['postcode'] . " " . $order['plaats']); ?><br>
                                    <?php if ($order['telefoon']): ?>
                                        📱 <?php echo htmlspecialchars($order['telefoon']); ?><br>
                                    <?php endif; ?>
                                    📧 <?php echo htmlspecialchars($order['email']); ?>
                                </p>
                            </div>
                            
                            <div class="order-items">
                                <h4>Bestelde producten</h4>
                                <table class="items-table">
                                    <tr>
                                        <th>Product</th>
                                        <th>Aantal</th>
                                        <th>Prijs</th>
                                        <th>Totaal</th>
                                    </tr>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <?php
                                            $product = $item['product'];
                                            $quantity = $item['quantity'];
                                            $prijs = $product->getPrice();
                                            $subtotaal = $prijs * $quantity;
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product->display()); ?></td>
                                            <td><?php echo $quantity; ?></td>
                                            <td>€<?php echo number_format($prijs, 2, ',', '.'); ?></td>
                                            <td>€<?php echo number_format($subtotaal, 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            
                            <div class="order-summary">
                                <p>
                                    <strong>Subtotaal:</strong> €<?php echo number_format($order['subtotaal'], 2, ',', '.'); ?><br>
                                    <strong>Verzendkosten:</strong> €<?php echo number_format($order['verzendkosten'], 2, ',', '.'); ?><br>
                                    <strong style="color: #4CAF50; font-size: 1.1em;">Totaal: €<?php echo number_format($order['totaal'], 2, ',', '.'); ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
