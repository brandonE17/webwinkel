<?php
// ai test script om te controleren of alle class bestanden bestaan en geladen kunnen worden
echo "<h1>Class Bestanden Check</h1>";

$classes = [
    'Product.php',
    'PhysicalProduct.php',
    'DigitalProduct.php',
    'DiscountProduct.php',
    'ShoppingCart.php'
];

foreach ($classes as $class) {
    $path = 'classes/' . $class;
    echo "<h3>$class</h3>";
    
    if (file_exists($path)) {
        $size = filesize($path);
        $content = file_get_contents($path);
        
        echo "Status: ✅ Bestaat ($size bytes)<br>";
        echo "Eerste 100 characters:<br>";
        echo "<pre style='background:#f0f0f0; padding:10px;'>" . 
             htmlspecialchars(substr($content, 0, 100)) . 
             "...</pre>";
        
        // Check of het een class bevat, zodat het kan regristeren
        if (strpos($content, 'class ') !== false) {
            echo "✅ Bevat een class definitie<br>";
        } else {
            echo "⚠️ Bevat GEEN class definitie (mogelijk leeg)<br>";
        }
    } else {
        echo "Status: ❌ Bestaat NIET<br>";
    }
    echo "<hr>";
}

// Test laden
echo "<h2>Test classes laden:</h2>";
try {
    require_once 'classes/Product.php';
    require_once 'classes/PhysicalProduct.php';
    
    echo "✅ Classes geladen<br>";
    
    // Test maken van een PhysicalProduct
    $product = new PhysicalProduct(1, "Test Product", 9.99, 0.5);
    echo "✅ PhysicalProduct object gemaakt<br>";
    echo "Display: " . $product->display() . "<br>";
    
} catch (Error $e) {
    echo "❌ Fout: " . $e->getMessage() . "<br>";
}
?> 