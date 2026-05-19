<?php
session_start();

// 1. Absolute Path Fix: This finds connect.php no matter where this file is moved
require_once dirname(__DIR__) . '/connect.php';

// 2. Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Now $conn is guaranteed to be defined
$categories = $conn->query("SELECT id, name FROM categories");
$subcategories = $conn->query("SELECT id, name FROM subcategories");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use mysqli_real_escape_string with the verified $conn
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $target_dir = "../images/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

    $image_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO products (name, brand, price, category_id, description, image) 
                VALUES ('$name', '$brand', '$price', '$category_id', '$description', '$image_name')";

        if ($conn->query($sql)) {
            $message = "<div style='color:green;'>✔ Product added!</div>";
        } else {
            $message = "<div style='color:red;'>✖ SQL Error: " . $conn->error . "</div>";
        }
    }
}
?>