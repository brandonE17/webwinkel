<?php
// classes/DigitalProduct.php
require_once 'product.php';

class DigitalProduct extends Product {
    private $fileSize;
    
    public function __construct($id, $naam, $prijs, $fileSize) {
        parent::__construct($id, $naam, $prijs);
        $this->fileSize = $fileSize;
    }
     
    public function display() {
        return parent::display() . " [" . $this->fileSize . "MB]";
    }
    
    public function getFileSize() {
        return $this->fileSize;
    }
    
    public function calculateShipping() {
        return 0; // Geen verzendkosten voor digitale producten
    }
}
?> 