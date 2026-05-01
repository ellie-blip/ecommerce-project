<?php include 'connect.php'; ?>
<?php

$categories = $conn->query("SELECT id, name FROM categories");
$subcategories = $conn->query("SELECT id, name FROM subcategories");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    $subcategory_id = !empty($_POST['subcategory_id']) ? $_POST['subcategory_id'] : NULL;

    $description = $_POST['description'];
   $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
  
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

       
        $sql = "INSERT INTO products 
        (name, brand, price, category_id, subcategory_id, image, description)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssdiiss",
            $name,
            $brand,
            $price,
            $category_id,
            $subcategory_id,
            $target_file,
            $description
        );
    if ($stmt->execute()) {
            $message = "<div style='color:green;'>✔Product added successfully</div>";
        } else {
            $message = "<div style='color:red;'>✖ Error: " . $conn->error . "</div>";
        }

        $stmt->close();

    } else {
        $message = "<div style='color:red;'>✖ Image upload failed</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
</head>
<body>

<h2>إAdd New Product</h2>

<?php echo $message; ?>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="name" placeholder="Product Name" required><br><br>

    <input type="text" name="brand" placeholder="Brand"><br><br>

    <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>

    <select name="category_id" required>
        <option value="">choose categories</option>
        <?php while($cat = $categories->fetch_assoc()): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <select name="subcategory_id">
        <option value=""> No subcategories</option>
        <?php while($sub = $subcategories->fetch_assoc()): ?>
            <option value="<?= $sub['id'] ?>"><?= $sub['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <input type="file" name="image" required><br><br>

    <textarea name="description" placeholder="Product Description"></textarea><br><br>

    <button type="submit">add product</button>

</form>

</body>
</html>


