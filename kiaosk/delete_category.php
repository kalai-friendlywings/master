<?php
// Database connection
include 'connect.php';

// Get the category ID from URL
$category_id = $_GET['id'];

// Delete the category from the database
$stmt = $pdo->prepare("DELETE FROM categories WHERE categories_id = :id");
$stmt->execute([':id' => $category_id]);

header("Location: categories.php");
exit;
?>
