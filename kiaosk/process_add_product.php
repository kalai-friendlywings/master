<?php
// Database connection
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $category_id = $_POST['category_id'];
    $productType = $_POST['productType'];
    
    // Handle file upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
        $targetDir = "uploads/"; // Directory to store uploaded images
        $imageName = basename($_FILES['productImage']['name']);
        $imagePath = $targetDir . $imageName;
        
        // Check if the uploads directory exists, if not, create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $imagePath)) {
            // File uploaded successfully
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        echo "No image uploaded or error occurred.";
        exit;
    }

    // Insert product into the database
    try {
        $sql = "INSERT INTO products (name, price, category_id, type, image) 
                VALUES (:name, :price, :category_id, :type, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $productName);
        $stmt->bindParam(':price', $productPrice);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':type', $productType);
        $stmt->bindParam(':image', $imagePath);
        
        if ($stmt->execute()) {
            header("Location: product_list.php");
            exit;

        } else {
            echo "Error adding product.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
