<?php
// Database connection
include 'connect.php';

// Fetch products with their categories from the database
$products = [];
try {
    $stmt = $pdo->query("
        SELECT p.*, c.categories_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.categories_id
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching products: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
        }

        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .logo-img {
            width: 30px;
            height: 30px;
        }

        .container {
            margin-top: 10px;
        }

        .table {
            border-radius: 16px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-success {
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product List</h1>
        <a href="add_product.php" class="btn btn-outline-success btn-sm w-100">Add New Product</a>

        <!-- Responsive Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>₹<?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['categories_name'] ?? 'No Category'); ?></td>

                            <!-- Product Type and Logo -->
                            <td>
                                <?php if ($product['type'] == 'veg'): ?>
                                    <img src="logo/132636849771dbfe43013a08764eb9f3.jpg" alt="Veg Logo" class="logo-img img-fluid"> Veg
                                <?php else: ?>
                                    <img src="logo/1531813245.png" alt="Non-Veg Logo" class="logo-img img-fluid"> Non-Veg
                                <?php endif; ?>
                            </td>

                            <!-- Product Image -->
                            <td>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img img-fluid">
                            </td>

                            <!-- Action Button -->
                            <td>
                                <a href="update_product.php?productId=<?php echo $product['product_id']; ?>" class="btn btn-warning btn-sm w-100 text-black">Update</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
