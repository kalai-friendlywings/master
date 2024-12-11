<?php
// Database connection
include 'connect.php';

// Fetch categories from the database
$categories = [];
try {
    $stmt = $pdo->query("SELECT * FROM categories WHERE categories_active = 1");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .submit-btn {
            background-color: #283694;
            color: white;
        }

        .logo-preview {
            margin-top: 20px;
            max-width: 150px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Add New Product</h2>
        <form action="process_add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <input type="text" class="form-control" id="productName" name="productName" required>
            </div>

            <div class="form-group">
                <label for="productPrice">Product Price (₹):</label>
                <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice" required>
            </div>

            <!-- Categories Dropdown -->
            <div class="form-group">
                <label for="category">Product Category:</label>
                <select class="form-control" id="category" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['categories_id']); ?>">
                            <?= htmlspecialchars($category['categories_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Product Type:</label><br>
                <input type="radio" id="veg" name="productType" value="veg" onchange="showLogo('veg')" required>
                <label for="veg">Veg</label>

                <input type="radio" id="nonveg" name="productType" value="nonveg" onchange="showLogo('nonveg')" required>
                <label for="nonveg">Non-Veg</label>
            </div>

            <!-- Display the corresponding logo -->
            <img id="logoPreview" class="logo-preview" src="" alt="Product Logo" style="display:none;">

            <div class="form-group">
                <label for="productImage">Product Image:</label>
                <input type="file" class="form-control-file" id="productImage" name="productImage" required>
            </div>

            <button type="submit" class="btn submit-btn">Add Product</button>
        </form>
    </div>

    <script>
        // Function to display the logo based on product type
        function showLogo(type) {
            const logoPreview = document.getElementById('logoPreview');
            if (type === 'veg') {
                logoPreview.src = 'logo/132636849771dbfe43013a08764eb9f3.jpg'; // Path to the Veg logo
            } else if (type === 'nonveg') {
                logoPreview.src = 'logo/1531813245.png'; // Path to the Non-Veg logo
            }
            logoPreview.style.display = 'block'; // Show the logo
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>