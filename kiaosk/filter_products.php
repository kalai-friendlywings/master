<?php
// Include your database connection file
require 'connect.php'; // Make sure the path to your connect.php file is correct

// Fetch product data
$query = $pdo->query("SELECT * FROM products");
$products = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$query = $pdo->query("SELECT * FROM categories WHERE categories_active = 1");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .filter-btn {
            margin: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Filter Products by Category</h2>

        <!-- Filter Buttons -->
        <div id="filter-buttons" class="my-4">
            <button class="btn btn-primary filter-btn" data-category="all">All Products</button>
            <?php foreach ($categories as $category): ?>
                <button class="btn btn-secondary filter-btn" data-category="<?= htmlspecialchars($category['categories_id']); ?>">
                    <?= htmlspecialchars($category['categories_name']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Product List -->
        <?php foreach ($products as $product) : ?>
            <div class="col-12 col-md-4 mb-4 product-item" data-category="<?= htmlspecialchars($product['category_id']); ?>">
                <div class="card h-100">
                    <!-- Display product image or a placeholder if image is missing -->
                    <img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'path_to_placeholder_image.jpg'; ?>"
                        alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                        class="card-img-top"
                        style="margin: 8px; margin-left:20px; height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <!-- Check logo_type for Veg or Non-Veg -->
                        <?php if ($product['logo_type'] == 'veg'): ?>
                            <img src="logo/132636849771dbfe43013a08764eb9f3.jpg" alt="Veg Logo" class="logo-img" style="width: 30px;">
                        <?php else: ?>
                            <img src="logo/1531813245.png" alt="Non-Veg Logo" class="logo-img" style="width: 30px;">
                        <?php endif; ?>

                        <!-- Display product name -->
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>

                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Display product price -->
                            <span class="price">₹<?= htmlspecialchars($product['price']) ?></span>

                            <!-- Add to Cart button -->
                            <button class="btn btn-primary add-to-cart"
                                data-name="<?= htmlspecialchars($product['name']) ?>"
                                data-price="<?= htmlspecialchars($product['price']) ?>">
                                Add Item
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    </div>

    <script>
        $(document).ready(function() {
            // When a filter button is clicked
            $('.filter-btn').on('click', function() {
                const selectedCategory = $(this).data('category');

                // Show all products if "All Products" is selected
                if (selectedCategory === 'all') {
                    $('.product-item').show();
                } else {
                    // Hide all products, then show only the ones matching the selected category
                    $('.product-item').hide();
                    $(`.product-item[data-category='${selectedCategory}']`).show();
                }
            });
        });
    </script>
</body>

</html>