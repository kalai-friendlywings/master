<?php
// Database connection
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];
    
    // Fetch products by category
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are products
        if ($products) {
            foreach ($products as $product) {
                // Generate product card HTML
                echo '<div class="col-12 col-md-4">';
                echo '<div class="card">';
                echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" class="card-img-top" style="margin: 8px; margin-left:20px;">';
                echo '<div class="card-body">';
                if ($product['type'] == 'veg') {
                    echo '<img src="logo/132636849771dbfe43013a08764eb9f3.jpg" alt="Veg Logo" class="logo-img">';
                } else {
                    echo '<img src="logo/1531813245.png" alt="Non-Veg Logo" class="logo-img">';
                }
                echo '<h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>';
                echo '<div class="d-flex justify-content-between align-items-center">';
                echo '<span class="price">₹' . htmlspecialchars($product['price']) . '</span>';
                echo '<button class="add-to-cart" data-name="' . htmlspecialchars($product['name']) . '" data-price="' . htmlspecialchars($product['price']) . '">Add Item</button>';
                echo '</div></div></div></div>';
            }
        } else {
            echo '<p>No products found in this category.</p>';
        }
    } catch (PDOException $e) {
        echo "Error fetching products: " . $e->getMessage();
    }
}
?>
