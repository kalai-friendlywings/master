<?php
// Database connection
include 'connect.php';

// Fetch categories from the database
$categories = [];
try {
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
}

// Add new category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    $category_active = isset($_POST['category_active']) ? 1 : 0;
    $category_status = isset($_POST['category_status']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("INSERT INTO categories (categories_name, categories_active, categories_status) VALUES (:name, :active, :status)");
        $stmt->execute([
            ':name' => $category_name,
            ':active' => $category_active,
            ':status' => $category_status
        ]);
        header("Location: categories.php");
        exit;
    } catch (PDOException $e) {
        echo "Error adding category: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
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
    </style>
</head>

<body>
    <div class="container ">
        <h2 class="text-center">Manage Categories</h2>
        <div class="card p-4 mb-3" style="border-radius:12px;  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);"> <!-- Add more categories here -->
            <form action="categories.php" method="POST">
                <div class="form-group">
                    <label for="category_name">Category Name:</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                </div>

                <div class="form-group">
                    <label for="category_active">Active:</label>
                    <input type="checkbox" id="category_active" name="category_active">
                </div>

                <div class="form-group">
                    <label for="category_status">Status:</label>
                    <input type="checkbox" id="category_status" name="category_status">
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control">
                        <option value="1">Electronics</option>
                        <option value="2">Furniture</option>

                    </select>
                </div>

                <button type="submit" class="btn submit-btn">Add Category</button>
            </form>
        </div>

        <div class="card p-3" style="border-radius:12px;  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);"">
            <!-- Form to Add New Category -->
            <h4 class="mt-3">Category List</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Active</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= htmlspecialchars($category['categories_id']) ?></td>
                            <td><?= htmlspecialchars($category['categories_name']) ?></td>
                            <td><?= htmlspecialchars($category['categories_active']) == 1 ? 'Yes' : 'No' ?></td>
                            <td><?= htmlspecialchars($category['categories_status']) == 1 ? 'Enabled' : 'Disabled' ?></td>
                            <td>
                                <a href="edit_category.php?id=<?= $category['categories_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_category.php?id=<?= $category['categories_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- List of Categories -->

    </div>
</body>

</html>