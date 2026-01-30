<?php

// laad init bestand
require_once 'includes/init.php';

// Alle producten met categorieën
$alle_producten = [
    1 => new PhysicalProduct(1, "PHP Boek", 49.99, 0.5, "Boeken"),
    2 => new DigitalProduct(2, "PHP Cursus", 79.99, 250, "Cursussen"),
    3 => new PhysicalProduct(3, "Gaming Mouse", 69.99, 0.3, "Hardware"),
    4 => new DiscountProduct(4, "Web Bundle", 199.99, 15, "Bundels")
];

// voeg product toe aan winkelwagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;
    
    if (isset($alle_producten[$productId])) {
        $winkelwagen->addProduct($alle_producten[$productId], $quantity);
    }
    
    // Refresh pagina
    header("Location: index.php");
    exit;
}

// Haal filter/zoek parameters op
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;

// Filter producten
$gefilterde_producten = array_filter($alle_producten, function($product) use ($search, $selected_category, $min_price, $max_price) {
    $prijs = $product->getPrice();
    $naam = strtolower($product->getNaam());
    $kategorie = $product->getKategorie();
    
    // Check zoeken
    if ($search && strpos($naam, $search) === false) {
        return false;
    }
    
    // Check categorie
    if ($selected_category && $kategorie !== $selected_category) {
        return false;
    }
    
    // Check prijs
    if ($prijs < $min_price || $prijs > $max_price) {
        return false;
    }
    
    return true;
});

// Haal unieke categorieën op
$categorieën = array_unique(array_map(function($p) { return $p->getKategorie(); }, $alle_producten));
sort($categorieën);
?>
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
            <div class="winkelwagen-info">
                <a href="cart.php">Bekijk Winkelwagen (<?php echo count($winkelwagen->getItems()); ?> items)</a>
            </div>
        </header>
        
        <main>
            <h2>Onze Producten</h2>
            
            <!-- Zoeken & Filteren -->
            <div class="filter-container">
                <form method="get" class="filter-form">
                    <div class="filter-group">
                        <input type="text" name="search" placeholder="Zoeken..." value="<?php echo htmlspecialchars($search); ?>" class="search-input">
                        <button type="submit" class="search-btn">🔍</button>
                    </div>
                    
                    <div class="filter-group">
                        <label for="category">Categorie:</label>
                        <select name="category" id="category">
                            <option value="">Alle</option>
                            <?php foreach ($categorieën as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($selected_category === $cat) ? 'selected' : ''; ?>>
                                    <?php echo $cat; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="min_price">Min prijs:</label>
                        <input type="number" name="min_price" id="min_price" value="<?php echo $min_price; ?>" min="0" step="0.01">
                    </div>
                    
                    <div class="filter-group">
                        <label for="max_price">Max prijs:</label>
                        <input type="number" name="max_price" id="max_price" value="<?php echo $max_price; ?>" min="0" step="0.01">
                    </div>
                    
                    <button type="submit" class="filter-btn">Filter</button>
                    <a href="index.php" class="reset-btn">Reset</a>
                </form>
            </div>
            
            <!-- Producten -->
            <?php if (empty($gefilterde_producten)): ?>
                <div class="no-results">
                    <p>Geen producten gevonden met deze filters.</p>
                </div>
            <?php else: ?>
            <div class="product-rooster">
                <?php foreach ($gefilterde_producten as $product): ?>
                <div class="product-card">
                    <span class="product-category"><?php echo $product->getKategorie(); ?></span>
                    <h3><?php echo $product->getNaam(); ?></h3>
                    <p><?php echo $product->display(); ?></p>
                    <p class="price">€<?php echo number_format($product->getPrice(), 2, ',', '.'); ?></p>
                    
                    <form method="post">
                        <input type="hidden" name="add_to_cart" value="1">
                        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                        <input type="number" name="quantity" value="1" min="1" class="qty-input">
                        <button type="submit" class="add-btn">Toevoegen</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>