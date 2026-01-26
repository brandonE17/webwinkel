<?php
// laad de basis Product class
require_once 'Product.php';

class DiscountProduct extends Product {
    private $discountPercentage;
    
    public function __construct($id, $naam, $prijs, $discountPercentage) {
        parent::__construct($id, $naam, $prijs);
        $this->discountPercentage = $discountPercentage;
    }
    
    public function getPrice() {
        // Bereken korting
        $discountAmount = ($this->discountPercentage / 100) * $this->prijs;
        return $this->prijs - $discountAmount;
    }
    
    public function display() {
        $discountedPrice = $this->getPrice();
        return $this->naam . " - €" . number_format($discountedPrice, 2, ',', '.') . 
               " (Korting: " . $this->discountPercentage . "%)";
    }
    
    public function getDiscountPercentage() {
        return $this->discountPercentage;
    }
}
?>