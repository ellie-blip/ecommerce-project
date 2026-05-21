<?php
session_start();
require_once 'connect.php';

// Redirect to cart if it's empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Only logged-in users can place an order
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {

    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $date_today = date('Y-m-d');

    // 1. INSERT into COMMANDES
    $order_sql = "INSERT INTO COMMANDES (DateCommande, StatutCommande, id_user)
                  VALUES ('$date_today', 'En cours', $user_id)";

    if (mysqli_query($conn, $order_sql)) {

        $new_order_id = mysqli_insert_id($conn);

        // 2. INSERT each cart item into LIGNECOMMANDE
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_id = (int)$product_id;
            $quantity   = (int)$quantity;

            $price_result = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
            $price_row    = mysqli_fetch_assoc($price_result);
            $unit_price   = $price_row['price'];

            $line_sql = "INSERT INTO LIGNECOMMANDE (IdCommande, id, Quantite, PrixUnitaire)
                         VALUES ($new_order_id, $product_id, $quantity, $unit_price)";
            mysqli_query($conn, $line_sql);
        }

        // 3. INSERT into EXPEDITION
        $expedition_sql = "INSERT INTO EXPEDITION (IdCommande, DateExpedition, StatutExpedition, AddresseLivraison)
                           VALUES ($new_order_id, '$date_today', 'En cours', '$address')";
        mysqli_query($conn, $expedition_sql);

        // 4. Clear the cart
        $_SESSION['cart'] = array();

        echo "<script>
                alert('Commande passée avec succès ! Numéro de commande : #$new_order_id');
                window.location.href='index.php';
              </script>";
        exit;

    } else {
        $error = "Erreur lors de la commande : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Finaliser la commande - Maison Élégance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="auth-section">
        <div class="auth-box">
            <span class="auth-label">Finalisation</span>
            <h2>Votre commande</h2>
            <p>Renseignez votre adresse de livraison pour finaliser votre sélection.</p>

            <?php if (!empty($error)): ?>
                <div class="auth-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="checkout.php">
                <div class="form-group">
                    <label>Adresse de livraison</label>
                    <textarea name="address" required 
                              style="width: 100%; padding: 10px; height: 100px; border-radius: 8px; border: 1px solid #ccc;"
                              placeholder="Votre adresse complète de livraison"></textarea>
                </div>

                <button type="submit" name="place_order" class="auth-btn">
                    Confirmer la commande
                </button>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
