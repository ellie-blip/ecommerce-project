<?php
session_start();
require_once 'connect.php'; 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div style="padding: 20px;">
        <h2>Your Shopping Cart</h2>
        
        <table border="1" cellpadding="10" style="border-collapse: collapse; width: 80%;">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;

                if (empty($_SESSION['cart'])) {
                    echo "<tr><td colspan='5' style='text-align: center;'>Your cart is empty.</td></tr>";
                } else {
                    foreach ($_SESSION['cart'] as $id => $quantity) {
                        
                        // ⚠️ TEAMMATE CHECK: Verify table name ('products') and columns ('name', 'price')
                        $query = "SELECT name, price FROM products WHERE id = $id";
                        $result = mysqli_query($conn, $query);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            $product = mysqli_fetch_assoc($result);
                            $name = $product['name'];
                            $price = $product['price'];
                            
                            $subtotal = $price * $quantity;
                            $grand_total += $subtotal; 

                            echo "<tr>
                                <td>{$name}</td>
                                <td>\${$price}</td>
                                <td>{$quantity}</td>
                                <td>\${$subtotal}</td>
                                <td><a href='remove_from_cart.php?id={$id}' style='color: red;'>Remove</a></td>
                            </tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>

        <h3>Grand Total: $<?php echo number_format($grand_total, 2); ?></h3>

        <?php if (!empty($_SESSION['cart'])): ?>
            <form action="checkout.php" method="POST">
                <button type="submit" name="checkout" style="padding: 10px 20px; cursor: pointer;">Proceed to Checkout</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>