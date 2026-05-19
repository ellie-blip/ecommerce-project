<?php
// Ensure only admins can access
include '../auth_admin.php';

// UNIVERSAL PATH FIX: Finds connect.php in the parent directory
require_once dirname(__DIR__) . '/connect.php';

$query = "SELECT * FROM orders ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes - Maison Élégance</title>
    <link rel="stylesheet" href="../style.css"> 
</head>
<body>

<?php include '../navbar.php'; ?>

<section class="admin-section">
    <div class="admin-header">
        <span class="auth-label">Espace Administration</span>
        <h2>Historique des commandes</h2>
        <p>Suivez les transactions et l'état des livraisons en cours.</p>
    </div>

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Commande</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo number_format($row['total_price'], 2, ',', ' '); ?> €</td>
                            <td>
                                <span class="status-badge" style="background: #f3f0eb; padding: 5px 10px; border-radius: 4px;">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Aucune commande enregistrée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../footer.php'; ?>
</body>
</html>