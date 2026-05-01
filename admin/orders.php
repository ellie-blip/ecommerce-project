<?php
session_start();

require_once __DIR__ . '/../connect.php';

$query = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Orders</title>
    <link rel="stylesheet" href="../style.css"> 
</head>
<body>
    <div style="padding: 20px;">
        <h2>Store Orders (Admin Dashboard)</h2>
        
        <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background-color: #f4f4f4;">
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date Ordered</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>#{$row['id']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['address']}</td>
                            <td>\${$row['total_price']}</td>
                            <td style='font-weight: bold;'>{$row['status']}</td>
                            <td>{$row['created_at']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align: center;'>No orders have been placed yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>