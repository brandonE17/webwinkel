<?php

require_once 'includes/init.php';

// VERWIJDEREN van item  winkelwagen 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $productId = $_POST['product_id'] ?? 0;
    $winkelwagen->removeProduct($productId);
    header("Location: cart.php");
    exit;
}

// WINKELWAGEN LEGEN
if (isset($_GET['clear']) && $_GET['clear'] == 1) {
    $_SESSION['winkelwagen'] = new ShoppingCart();
    $winkelwagen = $_SESSION['winkelwagen'];
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Winkelwagen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🛍️ Mijn Winkelwagen</h1>
            <a href="index.php" class="back-link">← Terug</a>
        </header>
        
        <main>
            <?php if (empty($winkelwagen->getItems())): ?>
                <div class="empty-cart">
                    <p>Je winkelwagen is leeg.</p>
                    <a href="index.php" class="btn">Ga winkelen</a>
                </div>
            <?php else: ?>
                <table class="cart-table">
                    <tr>
                        <th>Product</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                        <th>Totaal</th>
                        <th>Actie</th>
                    </tr>
                    
                    <?php
                    $totaal = 0;
                    foreach ($winkelwagen->getItems() as $item):
                        $product = $item['product'];
                        $quantity = $item['quantity'];
                        $prijs = $product->getPrice();
                        $subtotaal = $prijs * $quantity;
                        $totaal += $subtotaal;
                    ?>
                    <tr>
                        <td><?php echo $product->display(); ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td>€<?php echo number_format($prijs, 2, ',', '.'); ?></td>
                        <td>€<?php echo number_format($subtotaal, 2, ',', '.'); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="remove_item" value="1">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <button type="submit" class="remove-btn">Verwijder</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <tr>
                        <td colspan="3"><strong>Totaal</strong></td>
                        <td colspan="2"><strong>€<?php echo number_format($totaal, 2, ',', '.'); ?></strong></td>
                    </tr>
                </table>
                
                <div class="cart-actions">
                    <a href="index.php" class="btn">Verder winkelen</a>
                    <a href="cart.php?clear=1" class="btn clear-btn">Winkelwagen legen</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>