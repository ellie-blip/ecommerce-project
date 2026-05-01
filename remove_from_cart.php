<?php
session_start();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // If the item exists in the cart, vaporize it
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
}

// Redirect right back to the cart
header("Location: cart.php");
exit;
?>