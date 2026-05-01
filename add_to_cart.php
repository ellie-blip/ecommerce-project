<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check if an ID was sent in the URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++; // Add another one
    } else {
        $_SESSION['cart'][$id] = 1; // First time adding this product
    }
}

// ⚠️ TEAMMATE CHECK: Redirect back to Mariem's homepage. 
// Change 'index.php' if her storefront file is named differently.
header("Location: index.php"); 
exit;
?>