<?php
// index.php - PRODUCTEN + TOEVOEGEN
require_once 'includes/init.php';

// VERWERK TOEVOEGEN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    
    // Maak product aan
    $producten = [
        1 => new PhysicalProduct(1, "PHP Boek", 49.99, 0.5),
        2 => new DigitalProduct(2, "PHP Cursus", 79.99, 250),
        3 => new PhysicalProduct(3, "Gaming Mouse", 69.99, 0.3),
        4 => new DiscountProduct(4, "Web Bundle", 199.99, 15)
    ];
    
    if (isset($producten[$productId])) {
        $winkelwagen->addProduct($producten[$productId], $quantity);
    }
    
    // Refresh pagina
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Webwinkel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🛒 Mijn Webwinkel</h1>
            <div class="cart-info">
                <a href="cart.php">Bekijk Winkelwagen (<?php echo count($winkelwagen->getItems()); ?> items)</a>
            </div>
        </header>
        
        <main>
            <h2>Onze Producten</h2>
            
            <div class="product-grid">
                <div class="product-card">
                    <h3>PHP Boek</h3>
                    <p>Leer PHP programmeren</p>
                    <p class="price">€49,99</p>
                    <p>Gewicht: 0.5kg</p>
                    
                    <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="1">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>
                    </form>
                </div>
                

                <!-- PRODUCT 2 -->
                <div class="product-card">
                    <h3>PHP Cursus</h3>
                    <p>Online cursus</p>
                    <p class="price">€79,99</p>
                    <p>Bestand: 250MB</p>
                    
                    <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="2">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>
                    </form>
                </div>
                
                <!-- PRODUCT 3 -->
                <div class="product-card">
                    <h3>Gaming Mouse</h3>
                    <p>Precisie muis</p>
                    <p class="price">€69,99</p>
                    <p>Gewicht: 0.3kg</p>
                    
                    <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="3">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>
                    </form>
                </div>
                
                <!-- PRODUCT 4 -->
                <div class="product-card">
                    <h3>Web Bundle</h3>
                    <p>Complete bundle</p>
                    <p class="price"><s>€199,99</s> €169,99</p>
                    <p>15% korting</p>

               
                    
                    <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="4">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>
                    </form>

                     <!-- product 5 -->
                <div class="product-card">
                    <h3>Digital Art Pack</h3>
                    <p>Collectie van digitale kunstwerken</p>
                    <p class="price"><s>200</s> 160</s> </p> 
                    <p>20% korting</p>


                         <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="4">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>
                    </form>

                    <div class="product-card">
                     <h3> resident evil 7</h3>
                    <p>horror game</p>
                    <p class="price">20.00</p>
                    
                        <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="5">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>

                        </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>