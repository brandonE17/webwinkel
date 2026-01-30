<?php
// classes/DatabaseProduct.php

class DatabaseProduct extends Product {
    private $db;
    private $type;
    private $gewicht;
    private $bestandsgrootte;
    private $korting_procent;
    private $beschrijving;
    
    public function __construct($db, $id, $naam, $prijs, $categorie, $type, $gewicht = null, $bestandsgrootte = null, $korting_procent = null, $beschrijving = null) {
        parent::__construct($id, $naam, $prijs, $categorie);
        $this->db = $db;
        $this->type = $type;
        $this->gewicht = $gewicht;
        $this->bestandsgrootte = $bestandsgrootte;
        $this->korting_procent = $korting_procent;
        $this->beschrijving = $beschrijving;
    }
    
    public static function getProductsFromDB($db) {
        try {
            $stmt = $db->query("SELECT * FROM producten WHERE actief = 1 ORDER BY naam");
            $producten = [];
            
            while ($row = $stmt->fetch()) {
                $producten[$row['id']] = new DatabaseProduct(
                    $db,
                    $row['id'],
                    $row['naam'],
                    $row['prijs'],
                    $row['categorie'],
                    $row['type'],
                    $row['gewicht'],
                    $row['bestandsgrootte'],
                    $row['korting_procent'],
                    $row['beschrijving']
                );
            }
            
            return $producten;
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getGewicht() {
        return $this->gewicht;
    }
    
    public function getBestandsgrootte() {
        return $this->bestandsgrootte;
    }
    
    public function getKortingProcent() {
        return $this->korting_procent;
    }
    
    public function getBeschrijving() {
        return $this->beschrijving;
    }
    
    public function display() {
        if ($this->type === 'physical') {
            return $this->naam . " - €" . number_format($this->prijs, 2, ',', '.') . " (" . $this->gewicht . "kg)";
        } elseif ($this->type === 'digital') {
            return $this->naam . " - €" . number_format($this->prijs, 2, ',', '.') . " [" . $this->bestandsgrootte . "MB]";
        } elseif ($this->type === 'discount') {
            $discounted = $this->prijs * (1 - $this->korting_procent / 100);
            return $this->naam . " - €" . number_format($discounted, 2, ',', '.') . " (Korting: " . $this->korting_procent . "%)";
        }
        return $this->naam . " - €" . number_format($this->prijs, 2, ',', '.');
    }
    
    public function getPrice() {
        if ($this->type === 'discount') {
            return $this->prijs * (1 - $this->korting_procent / 100);
        }
        return $this->prijs;
    }
    
    public function calculateShipping() {
        if ($this->type === 'physical') {
            return $this->gewicht * 2.50;
        }
        return 0;
    }
}
?>
