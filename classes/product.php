<?php
// classes/Product.php 
class Product {
    protected $id;
    protected $naam;
    protected $prijs;
    protected $kategorie;
    
    public function __construct($id, $naam, $prijs, $kategorie = "Overig") {
        $this->id = $id;
        $this->naam = $naam;
        $this->prijs = $prijs;
        $this->kategorie = $kategorie;
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
    
    public function getCategorie() {
        return $this->kategorie;
    }
    
    public function display() {
        return $this->naam . " - €" . number_format($this->prijs, 2, ',', '.');
    }
}
?>  