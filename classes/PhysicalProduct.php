<?php



require_once 'product.php';

class PhysicalProduct extends Product {
    private $gewicht;
    
    public function __construct($id, $naam, $prijs, $gewicht) {
        // Roep de constructor van de parent class aan
        parent::__construct($id, $naam, $prijs);
        $this->gewicht = $gewicht;
    }
    
    public function display() {
        // Gebruik de display() method van Product en voeg gewicht toe
        return parent::display() . " (" . $this->gewicht . "kg)";
    }
    
    public function getGewicht() {
        return $this->gewicht;
    }
    
    public function calculateShipping() {
        
        return $this->gewicht * 2.50;
    }
}
?>