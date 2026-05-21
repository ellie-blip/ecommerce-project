<?php
include '../auth_admin.php';
require_once dirname(__DIR__) . '/connect.php';

// JOIN COMMANDES with users and EXPEDITION for a full order overview
$query = "
    SELECT 
        c.IdCommande,
        c.DateCommande,
        c.StatutCommande,
        u.name        AS customer_name,
        u.email       AS customer_email,
        e.AddresseLivraison,
        e.StatutExpedition,
        SUM(l.Quantite * l.PrixUnitaire) AS total_price
    FROM COMMANDES c
    JOIN users      u ON c.id_user      = u.id
    LEFT JOIN EXPEDITION  e ON c.IdCommande  = e.IdCommande
    LEFT JOIN LIGNECOMMANDE l ON c.IdCommande = l.IdCommande
    GROUP BY c.IdCommande, c.DateCommande, c.StatutCommande,
             u.name, u.email, e.AddresseLivraison, e.StatutExpedition
    ORDER BY c.DateCommande DESC
";

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
                    <th>Statut commande</th>
                    <th>Statut livraison</th>
                    <th>Adresse livraison</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['IdCommande']; ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_email']); ?></td>
                            <td><?php echo number_format($row['total_price'], 2, ',', ' '); ?> €</td>
                            <td>
                                <span class="status-badge" style="background: #f3f0eb; padding: 5px 10px; border-radius: 4px;">
                                    <?php echo htmlspecialchars($row['StatutCommande']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge" style="background: #f3f0eb; padding: 5px 10px; border-radius: 4px;">
                                    <?php echo $row['StatutExpedition'] ? htmlspecialchars($row['StatutExpedition']) : '—'; ?>
                                </span>
                            </td>
                            <td><?php echo $row['AddresseLivraison'] ? htmlspecialchars($row['AddresseLivraison']) : '—'; ?></td>
                            <td><?php echo htmlspecialchars($row['DateCommande']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">Aucune commande enregistrée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../footer.php'; ?>
</body>
</html>
