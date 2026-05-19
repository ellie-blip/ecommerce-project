<?php include 'navbar.php'; ?>

<?php
include "connect.php";


$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';


$sql = "SELECT * FROM products WHERE 1=1";

if (!empty($category)) {
    $sql .= " AND category = '" . mysqli_real_escape_string($conn, $category) . "'";
}

if (!empty($subcategory)) {
    $sql .= " AND idsub = " . intval($subcategory);
}

if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $sql .= " AND name LIKE '%$search_safe%'";
}

if ($sort == 'asc') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort == 'desc') {
    $sql .= " ORDER BY price DESC";
} else {
    $sql .= " ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);


if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

$subcategories = [];
if ($category == 'normal') {
    $sub_sql = "SELECT idsub, nomsub FROM subcategories WHERE idcategorie = 1 ORDER BY idsub";
    $sub_result = mysqli_query($conn, $sub_sql);
    if ($sub_result) {
        while ($row = mysqli_fetch_assoc($sub_result)) {
            $subcategories[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Voitures - Maison Auto</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
             background: linear-gradient(135deg, #ebf2f8 0%, #f8f0e3 100%);
            color: #eee;
            min-height: 100vh;
        }

        .cars-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #03033d;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        /* Search & Filter */
        .search-filter {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .search-filter input[type="text"] {
            padding: 12px 20px;
            border: 2px solid  #03033d;
            border-radius: 25px;
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 1rem;
            width: 300px;
            outline: none;
            transition: all 0.3s;
        }

        .search-filter input[type="text"]::placeholder {
            color: #aaa;
        }

        .search-filter input[type="text"]:focus {
            background: rgba(255,255,255,0.2);
            box-shadow: 0 0 15px rgba(233, 69, 96, 0.3);
        }

        .search-filter select {
            padding: 12px 20px;
            border: 2px solid  #03033d;
            border-radius: 25px;
            background: rgba(255,255,255,0.1);
            color: #b7cbdf;
            font-size: 1rem;
            cursor: pointer;
            outline: none;
        }

        .search-filter select option {
            background: #1a1a2e;
            color: #fff;
        }

        .search-filter button {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            background: #b7cbdf;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-filter button:hover {
            background: #b7cbdf;
            transform: scale(1.05);
        }

        /* Category Navigation */
        .nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .nav a {
            text-decoration: none;
            color:  #03033d;
            padding: 10px 25px;
            border-radius: 20px;
            border: 2px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav a:hover {
            color: #b7cbdf;
            border-color: #b7cbdf;
        }

        .nav a.active {
            background: #b7cbdf;
            color: #fff;
            border-color: #b7cbdf;
        }

        /* Subcategory Navigation */
        .sub-nav {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .sub-nav a {
            text-decoration: none;
            color: #03033d;
            padding: 8px 20px;
            border-radius: 15px;
            border: 2px solid #03033d;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sub-nav a:hover {
            background: #03033d;
            color: #fff;
        }

        .sub-nav a.active {
            background: #03033d;
            color: #fff;
            border-color: #03033d;
        }

        /* Cars Grid */
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .car-card { 
            background: #b7cbdf;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border-color: #03033d;
        }

        .car-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(45deg, #2a2a4a, #1a1a2e);
        }

        .car-info {

            padding: 20px;
        }

        .car-info h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #fff;
        }

        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .badge[data-category="normal"] {
            background: #4ecca3;
            color: #1a1a2e;
        }

        .badge[data-category="sport"] {
            background: #e94560;
            color: #fff;
        }

        .badge[data-category="collection"] {
            background: #ffd700;
            color: #1a1a2e;
        }

        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #03033d;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 25px;
            background: #03033d;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #03033d ;
            transform: scale(1.05);
        }

        .empty {
            text-align: center;
            font-size: 1.2rem;
            color: #aaa;
            grid-column: 1 / -1;
            padding: 50px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }
            .search-filter input[type="text"] {
                width: 100%;
            }
            .cars-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <main class="cars-page">
        <h1> Nos Voitures</h1>

        <!--  نموذج البحث  -->
        <form method="GET" action="cars.php" class="search-filter">
            <?php if(!empty($category)): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php endif; ?>

            <?php if(!empty($subcategory)): ?>
                <input type="hidden" name="subcategory" value="<?php echo htmlspecialchars($subcategory); ?>">
            <?php endif; ?>

            <input type="text" name="search" placeholder="Rechercher une voiture..." 
                   value="<?php echo htmlspecialchars($search); ?>">

            <select name="sort">
                <option value="">Trier par prix</option>
                <option value="asc" <?php echo ($sort == 'asc') ? 'selected' : ''; ?>>↑ Prix Croissant</option>
                <option value="desc" <?php echo ($sort == 'desc') ? 'selected' : ''; ?>>↓ Prix Décroissant</option>
            </select>

            <button type="submit"> Rechercher</button>
        </form>

        <!-- أزرار الفئات -->
        <div class="nav">
            <a href="cars.php" class="<?php echo empty($category) ? 'active' : ''; ?>"> ALL </a>
            <a href="cars.php?category=normal" class="<?php echo ($category == 'normal') ? 'active' : ''; ?>"> NORMAL</a>
            <a href="cars.php?category=sport" class="<?php echo ($category == 'sport') ? 'active' : ''; ?>">SPORT</a>
            <a href="cars.php?category=collection" class="<?php echo ($category == 'collection') ? 'active' : ''; ?>"> La Collection</a>
        </div>

        <!-- Subcategories -->
        <?php if ($category == 'normal' && !empty($subcategories)): ?>
        <div class="sub-nav">
            <a href="cars.php?category=normal" class="<?php echo empty($subcategory) ? 'active' : ''; ?>">All Normal</a>
            <?php foreach ($subcategories as $sub): ?>
                <a href="cars.php?category=normal&subcategory=<?php echo $sub['idsub']; ?>" 
                   class="<?php echo ($subcategory == $sub['idsub']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($sub['nomsub']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!--  عرض النتائج -->
        <div class="cars-grid">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($car = mysqli_fetch_assoc($result)):
                    $image_path = 'photo/' . ($car['image'] ?? '');
                    $image_exists = !empty($car['image']) && file_exists($image_path);

                    ?>
                    <div class="car-card">

                    <div class="img">
                            <?php if ($image_exists): ?>
                              
                                <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                     alt="<?php echo htmlspecialchars($car['name']); ?>">
                            <?php else: ?>
                        
                                <div class="no-image">

                                    <span><?php echo htmlspecialchars($car['name']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="car-info">
                            <h3><?php echo htmlspecialchars($car['name']); ?></h3>
                            <span class="badge" data-category="<?php echo htmlspecialchars($car['category']); ?>">
                                <?php echo htmlspecialchars(ucfirst($car['category'])); ?>
                            </span>
                            <p class="price"><?php echo number_format($car['price'], 0, ',', ' '); ?> €</p>
                            <a href="car_details.php?id=<?php echo $car['id']; ?>" class="btn">Voir détails →</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="empty"> Aucune voiture ne correspond à votre recherche.<br>Essayez d'autres critères !</p>
            <?php endif; ?>
        </div>
    </main>


<?php include 'footer.php'; ?>

</body>
</html>