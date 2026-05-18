<?php
include '../auth_admin.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Maison Élégance</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<section class="admin-dashboard">
    <div class="admin-header">
        <span class="auth-label">Espace Administration</span>
        <h2>Tableau de bord</h2>
        <p>
            Gérez les clients, les véhicules, les commandes et les livraisons
            de Maison Élégance depuis un espace unique.
        </p>
    </div>

    <div class="dashboard-cards">

        <a href="customers.php" class="dashboard-card">
            <h3>Clients</h3>
            <p>Consulter les membres inscrits et leurs informations.</p>
            <span>Ouvrir</span>
        </a>

        <a href="products.php" class="dashboard-card">
            <h3>Produits</h3>
            <p>Ajouter, modifier ou supprimer les véhicules proposés.</p>
            <span>Ouvrir</span>
        </a>

        <a href="orders.php" class="dashboard-card">
            <h3>Commandes</h3>
            <p>Suivre les commandes et mettre à jour leur statut.</p>
            <span>Ouvrir</span>
        </a>

    </div>
</section>

<?php include '../footer.php'; ?>

</body>
</html>