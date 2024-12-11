<?php
// Include the database connection
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the cart items and total amount from the POST data
    $cartItems = $_POST['cart_items'];
    $totalAmount = $_POST['total_amount'];

    // Start a database transaction
    $pdo->beginTransaction();

    try {
        // Insert each cart item into the sales table
        foreach ($cartItems as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];

            // Insert sales data into the database
            $stmt = $pdo->prepare("INSERT INTO sales (product_id, quantity, price, total_amount, sale_date) 
                                   VALUES (:product_id, :quantity, :price, :total_amount, NOW())");
            $stmt->execute([
                ':product_id' => $productId,
                ':quantity' => $quantity,
                ':price' => $price,
                ':total_amount' => $quantity * $price
            ]);
        }

        // Commit the transaction
        $pdo->commit();

        // Return a success response
        echo 'Checkout Successful';
    } catch (PDOException $e) {
        // Rollback the transaction if there’s an error
        $pdo->rollBack();
        echo 'Error: ' . $e->getMessage();
    }
}
?>
