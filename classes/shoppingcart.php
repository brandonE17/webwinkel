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
        foreach ($this->items as $index => $item) {
            if ($item['product']->getId() == $productId) {
                if ($newQuantity <= 0) {
                    array_splice($this->items, $index, 1);
                } else {
                    $this->items[$index]['quantity'] = $newQuantity;
                }
                return true;
            }
        }
        return false;
    }
    
    public function clearCart() {
        $this->items = [];
    }
}
?>