<?php
// classes/Product.php 
class Product {
    protected $id;
    protected $naam;
    protected $prijs;
    
    public function __construct($id, $naam, $prijs) {
        $this->id = $id;
        $this->naam = $naam;
        $this->prijs = $prijs; 
    }
    
    public function getNaam() {
        return $this->naam;
    }
    
    public function getPrice() {
        return $this->prijs;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function display() {
        return $this->naam . " - €" . number_format($this->prijs, 2, ',', '.');
    }
}
?>  