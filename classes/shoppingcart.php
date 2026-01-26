<?php
// classes/ShoppingCart.php
class ShoppingCart {
    private $items = [];
    
    public function addProduct(Product $product, $quantity = 1) {
        $this->items[] = [
            'product' => $product,
            'quantity' => $quantity
        ];
    }
    
    public function removeProduct($productId) {
        foreach ($this->items as $index => $item) {
            if ($item['product']->getId() == $productId) {
                array_splice($this->items, $index, 1);
                return true;
            }
        }
        return false;
    }
    
    
    public function getItems() {
        return $this->items;
    }
    
    
    public function updateQuantity($productId, $newQuantity) {
        // implementatie
    }
    
    public function clearCart() {
        $this->items = [];
    }
}
?>