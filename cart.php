<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Sélection - Maison Élégance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <section class="admin-section">
        <div class="admin-header">
            <span class="auth-label">Votre Panier</span>
            <h2>Ma Sélection Automobile</h2>
            <p>Retrouvez ici les modèles d'exception que vous avez sélectionnés.</p>
        </div>

        <div class="admin-table-box">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Modèle</th>
                        <th>Prix Unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;

                    if (empty($_SESSION['cart'])) {
                        echo "<tr><td colspan='5' style='text-align: center; padding: 50px;'>Votre sélection est actuellement vide.</td></tr>";
                    } else {
                        foreach ($_SESSION['cart'] as $id => $quantity) {
                            $id = (int)$id;
                            $query = "SELECT name, price, image FROM products WHERE id = $id";
                            $result = mysqli_query($conn, $query);
                            
                            if ($product = mysqli_fetch_assoc($result)) {
                                $subtotal = $product['price'] * $quantity;
                                $grand_total += $subtotal;
                                ?>
                                <tr>
                                    <td style="display: flex; align-items: center; gap: 15px;">
                                        <img src="images/<?php echo $product['image']; ?>" width="80" style="border-radius: 5px;">
                                        <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                    </td>
                                    <td><?php echo number_format($product['price'], 0, ',', ' '); ?> €</td>
                                    <td><?php echo $quantity; ?></td>
                                    <td style="font-weight: bold; color: var(--gold);"><?php echo number_format($subtotal, 0, ',', ' '); ?> €</td>
                                    <td>
                                        <a href="remove_from_cart.php?id=<?php echo $id; ?>" style="color: #c0392b; text-decoration: none; font-size: 0.9em;">Supprimer</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div style="margin-top: 30px; text-align: right; border-top: 1px solid #ddd; padding-top: 20px;">
                <h3 style="font-size: 24px; color: var(--dark);">Total : <span style="color: var(--gold);"><?php echo number_format($grand_total, 0, ',', ' '); ?> €</span></h3>
                <br>
                <a href="checkout.php" class="auth-btn" style="text-decoration: none; display: inline-block;">Procéder au paiement</a>
            </div>
        <?php else: ?>
            <div style="text-align: center; margin-top: 20px;">
                <a href="cars.php" class="auth-btn" style="text-decoration: none; display: inline-block;">Voir la collection</a>
            </div>
        <?php endif; ?>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>