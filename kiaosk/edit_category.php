<?php
// Database connection
include 'connect.php';

// Get the category ID from URL
$category_id = $_GET['id'];

// Fetch the category details
$stmt = $pdo->prepare("SELECT * FROM categories WHERE categories_id = :id");
$stmt->execute([':id' => $category_id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

// Update the category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $category_active = isset($_POST['category_active']) ? 1 : 0;
    $category_status = isset($_POST['category_status']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE categories SET categories_name = :name, categories_active = :active, categories_status = :status WHERE categories_id = :id");
    $stmt->execute([
        ':name' => $category_name,
        ':active' => $category_active,
        ':status' => $category_status,
        ':id' => $category_id
    ]);

    header("Location: categories.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Category</title>
</head>
<body style="font-family: Arial, sans-serif;
            background-color: #eaeaea;">
    <div class="container mt-5">
        <h2>Edit Category</h2>
        <div class="card p-3">
        <form action="edit_category.php?id=<?= $category['categories_id'] ?>" method="POST">
            <div class="form-group">
                <label for="category_name">Category Name:</label>
                <input type="text" class="form-control" name="category_name" value="<?= htmlspecialchars($category['categories_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="category_active">Active:</label>
                <input type="checkbox" name="category_active" <?= $category['categories_active'] == 1 ? 'checked' : '' ?>>
            </div>

            <div class="form-group">
                <label for="category_status">Status:</label>
                <input type="checkbox" name="category_status" <?= $category['categories_status'] == 1 ? 'checked' : '' ?>>
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
        </div>
      
    </div>
</body>
</html>
