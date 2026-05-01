<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">Maison Élégance</a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">L’Univers</a></li>
            <li><a href="cars.php">La Collection</a></li>
            <li><a href="cars.php?category=normal">Classiques</a></li>
            <li><a href="cars.php?category=sport">Performance</a></li>
            <li><a href="cars.php?category=collection">Héritage</a></li>
            <li><a href="cart.php">Sélection Privée</a></li>
        </ul>

        <div class="nav-auth">
            <?php if (isset($_SESSION['user_name'])): ?>
                <span class="welcome-text">
                    Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/products.php" class="admin-link">Espace Administration</a>
                <?php endif; ?>

                <a href="logout.php" class="logout-link">Quitter l’Espace</a>
            <?php else: ?>
                <a href="login.php" class="login-link">Accès Membre</a>
                <a href="register.php" class="register-link">Devenir Membre</a>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>
<?php


