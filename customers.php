<?php
include '../auth_admin.php';
include '../connect.php';

$sql = "SELECT id, name, email, phone, address, role FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients - Maison Élégance</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<section class="admin-section">
    <div class="admin-header">
        <span class="auth-label">Espace Administration</span>
        <h2>Gestion des clients</h2>
        <p>
            Consultez la liste des membres inscrits sur la plateforme Maison Élégance.
        </p>
    </div>

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Rôle</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun client inscrit pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../footer.php'; ?>

</body>
</html>