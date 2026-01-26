<?php


// dit Laad alle classes
require_once __DIR__ . '/../classes/product.php';
require_once __DIR__ . '/../classes/PhysicalProduct.php';
require_once __DIR__ . '/../classes/DigitalProduct.php';
require_once __DIR__ . '/../classes/DiscountProduct.php';
require_once __DIR__ . '/../classes/shoppingcart.php';


session_start();


// Maak of haalt winkelwagen op
if (!isset($_SESSION['winkelwagen'])) {
    $_SESSION['winkelwagen'] = new ShoppingCart();
}

$winkelwagen = $_SESSION['winkelwagen'];
?>   