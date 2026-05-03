<?php
session_start();
include 'connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        /*
           La database actuelle contient des mots de passe simples comme 123456.
           Donc on accepte temporairement :
           - password_verify() pour les futurs comptes sécurisés
           - comparaison simple pour les comptes test déjà insérés
        */
        if (password_verify($password, $user['password']) || $password == $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Aucun compte ne correspond à cette adresse email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès Membre - Maison Élégance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <section class="auth-section">
        <div class="auth-box">
            <span class="auth-label">Espace privé</span>

            <h2>Accès Membre</h2>

            <p>
                Connectez-vous à votre espace personnel afin de retrouver votre sélection
                et poursuivre votre expérience au sein de Maison Élégance.
            </p>

            <?php if (!empty($message)): ?>
                <div class="auth-message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Adresse email</label>
                    <input type="email" name="email" placeholder="exemple@email.com" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="password" placeholder="Votre mot de passe" required>
                </div>

                <button type="submit" class="auth-btn">Accéder à mon espace</button>
            </form>

            <p class="auth-link">
                Pas encore membre ?
                <a href="register.php">Créer un accès privilégié</a>
            </p>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>