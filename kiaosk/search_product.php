<?php
include 'connect.php';

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $stmt = $pdo->prepare("SELECT name FROM products WHERE name LIKE :searchTerm LIMIT 10");
    $stmt->execute(['searchTerm' => $searchTerm . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($products) {
        foreach ($products as $product) {
            echo "<a class='dropdown-item product-name' href='#' data-name='".htmlspecialchars($product['name'])."'>".htmlspecialchars($product['name'])."</a>";
        }
    } else {
        echo "<a class='dropdown-item disabled' href='#'>No products found</a>";
    }
}
?>
