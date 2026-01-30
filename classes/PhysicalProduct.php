<?php


//laden van de basis Product class
require_once 'product.php';

class PhysicalProduct extends Product {
    private $gewicht;
    
    public function __construct($id, $naam, $prijs, $gewicht, $kategorie = "Overig") {
        // Roep de constructor van de class aan
        parent::__construct($id, $naam, $prijs, $kategorie);
        $this->gewicht = $gewicht;
    }
    
    public function display() { 
        return parent::display() . " (" . $this->gewicht . "kg)";
    }
    
    public function getGewicht() {
        return $this->gewicht;
    }
    // reken verzendkosten op basis van gewicht
    
    public function calculateShipping() {
        
        return $this->gewicht * 2.50;
    }
}
?>