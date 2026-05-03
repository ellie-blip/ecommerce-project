<?php
session_start();
include 'connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($result) > 0) {
        $message = "Cette adresse email est déjà utilisée.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, phone, address, role)
                VALUES ('$name', '$email', '$hashedPassword', '$phone', '$address', 'customer')";

        if (mysqli_query($conn, $sql)) {
            $message = "Votre inscription a été réalisée avec succès. Vous pouvez maintenant vous connecter.";
        } else {
            $message = "Une erreur est survenue lors de l'inscription.";
        }
    }
}
?>

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
            <span class="auth-label">Accès confidentiel</span>

            <h2>Devenir Membre</h2>

            <p>
                Rejoignez un espace réservé aux amateurs d’automobiles d’exception,
                pensé pour une clientèle exigeante.
            </p>

            <?php if (!empty($message)): ?>
                <div class="auth-message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" name="name" placeholder="Votre nom complet" required>
                </div>

                <div class="form-group">
                    <label>Adresse email</label>
                    <input type="email" name="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="Créer un mot de passe" required>
                </div>

                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="phone" placeholder="Votre numéro de téléphone">
                </div>

                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" name="address" placeholder="Votre adresse">
                </div>

                <button type="submit" class="auth-btn">Créer mon accès</button>
            </form>

            <p class="auth-link">
                Déjà membre ?
                <a href="login.php">Accéder à mon espace</a>
            </p>
        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>
</html>