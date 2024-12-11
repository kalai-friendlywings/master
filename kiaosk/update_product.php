<?php
// Database connection
include 'connect.php';

// Check if a product ID was provided in the URL
if (!isset($_GET['productId'])) {
    echo "Product ID is missing.";
    exit;
}

// Fetch product details based on product ID
$productId = $_GET['productId'];
$product = null;
$categories = [];

try {
    // Fetch the specific product
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch all categories for the dropdown
    $categoryStmt = $pdo->query("SELECT categories_id, categories_name FROM categories");
    $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo "Product not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching product: " . $e->getMessage();
    exit;
}

// Handle form submission for updating the product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $categoryId = $_POST['categoryId'];
    $type = $_POST['type'];

    if (!empty($_FILES["productImage"]["name"])) {
        $target_dir = "product_image/";
        $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
        
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;
        } else {
            echo "Error uploading the image.";
            exit;
        }
    } else {
        $imagePath = $product['image'];
    }

    try {
        $sql = "UPDATE products SET name = :name, price = :price, category_id = :categoryId, type = :type, image = :image WHERE product_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $productName,
            ':price' => $productPrice,
            ':categoryId' => $categoryId,
            ':type' => $type,
            ':image' => $imagePath,
            ':id' => $productId
        ]);

        header("Location: product_list.php");
        exit;
    } catch (PDOException $e) {
        echo "Error updating product: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
        }
        
        .container {
            margin-top: 50px;
            max-width: 600px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        
        .product-img {
            width: 100px;
            height: auto;
            margin-top: 10px;
        }
        
        .btn-primary {
            background-color: #283694;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1d2951;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <h2 class="text-center mb-4">Update Product</h2>
        <form action="update_product.php?productId=<?php echo $productId; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="productName">Product Name</label>
                <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="productPrice">Product Price (₹)</label>
                <input type="number" step="0.01" class="form-control" id="productPrice" name="productPrice" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="categoryId">Category</label>
                <select class="form-control" id="categoryId" name="categoryId" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['categories_id']; ?>" <?php echo ($product['category_id'] == $category['categories_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['categories_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label>Type</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="veg" name="type" value="veg" <?php echo ($product['type'] == 'veg') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="veg">Veg</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="nonveg" name="type" value="nonveg" <?php echo ($product['type'] == 'nonveg') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="nonveg">Non-Veg</label>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label for="productImage">Product Image</label>
                <input type="file" class="form-control" id="productImage" name="productImage">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Current Image" class="product-img">
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Update Product</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
