<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
   <section class="categories-section">
        <div class="section-title">
            <h2>L'art de l'automobile</h2>
            <p>
                Trois collections pensées pour une clientèle exigeante : élégance quotidienne,
                performance affirmée et héritage d’exception.
            </p>
        </div>

        <div class="category-line">
            <div class="category-image normal-image"></div>
            <div class="category-text">
                <h3>Classiques</h3>
                <p>
                    Des modèles raffinés, confortables et intemporels, conçus pour accompagner
                    chaque déplacement avec distinction.
                </p>
                <a href="cars.php?category=normal">Découvrir</a>
            </div>
        </div>

        <div class="category-line reverse">
            <div class="category-image sport-image"></div>
            <div class="category-text">
                <h3>Performance</h3>
                <p>
                    Une sélection de véhicules puissants et racés, destinés aux passionnés
                    de sensations maîtrisées.
                </p>
                <a href="cars.php?category=sport">Découvrir</a>
            </div>
        </div>

        <div class="category-line">
            <div class="category-image collection-image"></div>
            <div class="category-text">
                <h3>Héritage</h3>
                <p>
                    Des automobiles rares, chargées d’histoire, choisies pour leur caractère
                    et leur valeur patrimoniale.
                </p>
                <a href="cars.php?category=collection">Découvrir</a>
            </div>
        </div>
    </section>
</body>
</html>