<?php
session_start();
require_once 'connect.php';

// If they try to bypass the cart and come here with an empty cart, kick them back
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// When they submit the checkout form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    
    // Grab what they typed in the form
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // ⚠️ TEAMMATE CHECK: If Sarah's login system saves the user's ID in a different session variable, update this!
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL'; 
    $status = 'Pending';
    
    // Calculate the total securely by checking the database again
    $total_price = 0;
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $query = "SELECT price FROM products WHERE id = $id";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $total_price += $row['price'] * $quantity;
        }
    }

    // 1. SAVE THE RECEIPT (Insert into orders table)
    $order_query = "INSERT INTO orders (user_id, customer_name, email, address, total_price, status, created_at) 
                    VALUES ($user_id, '$name', '$email', '$address', $total_price, '$status', NOW())";
    
    if (mysqli_query($conn, $order_query)) {
        
        // Get the ID of the order we JUST created
        $new_order_id = mysqli_insert_id($conn); 

        // 2. SAVE THE ITEMS (Insert into order_items table)
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            
            // Get the price at the time of purchase
            $p_query = "SELECT price FROM products WHERE id = $product_id";
            $p_result = mysqli_query($conn, $p_query);
            $p_row = mysqli_fetch_assoc($p_result);
            $price = $p_row['price'];

            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                           VALUES ($new_order_id, $product_id, $quantity, $price)";
            mysqli_query($conn, $item_query);
        }

        // 3. EMPTY THE CART AND CELEBRATE
        $_SESSION['cart'] = array();
        echo "<script>
                alert('Success! Your order has been placed. Order ID: #$new_order_id');
                window.location.href='index.php';
              </script>";
        exit;
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div style="padding: 20px;">
        <h2>Checkout Details</h2>
        <form method="POST" action="checkout.php" style="max-width: 400px;">
            <div style="margin-bottom: 10px;">
                <label>Full Name:</label><br>
                <input type="text" name="customer_name" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                <label>Email:</label><br>
                <input type="email" name="email" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Shipping Address:</label><br>
                <textarea name="address" required style="width: 100%; padding: 8px; height: 80px;"></textarea>
            </div>
            <button type="submit" name="place_order" style="padding: 10px 20px; cursor: pointer; background-color: green; color: white; border: none;">Place Order</button>
        </form>
    </div>
</body>
</html>