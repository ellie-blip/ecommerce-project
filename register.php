<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devenir Membre - Maison Élégance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="auth-section">
    <div class="auth-box">
        <h2>Devenir Membre</h2>
        <p>Rejoignez un espace réservé aux amateurs d’automobiles d’exception.</p>

        <?php if (!empty($message)): ?>
            <div class="auth-message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Adresse email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="auth-btn">Créer mon accès</button>
        </form>

        <p class="auth-link">
            Déjà membre ?
            <a href="login.php">Accéder à mon espace</a>
        </p>
    </div>
</section>

</body>
</html>