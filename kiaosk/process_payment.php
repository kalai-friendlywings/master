<?php
// Include your database connection
include 'connect.php';

// Get the JSON input from the fetch request
$data = json_decode(file_get_contents('php://input'), true);

// Check if cart data exists
if (empty($data['cartItems']) || empty($data['totalPrice'])) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

$cartItems = $data['cartItems'];
$totalPrice = $data['totalPrice'];

// Check if cartItems is an array and contains valid data
if (!is_array($cartItems) || empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Invalid cart data']);
    exit;
}

try {
    // Prepare the query to insert into the sales table
    foreach ($cartItems as $item) {
        // Debugging: Print item details
        error_log(print_r($item, true));

        $productName = $item['productName'];
        $quantity = $item['quantity'];

        // Fetch product_id and price for the product based on product_name
        $stmt = $pdo->prepare("SELECT id, price FROM products WHERE product_name = ?");
        $stmt->execute([$productName]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Check if product was found
        if ($product) {
            $product_id = $product['id'];
            $price = $product['price'];

            // Debugging: Print fetched product details
            error_log("Product found: ID = $product_id, Name = $productName, Price = $price");

            // Insert sales data into the sales table
            $stmt = $pdo->prepare("INSERT INTO sales (product_id, product_name, quantity, price, total_amount, sale_date) VALUES (?, ?, ?, ?, ?, NOW())");
            $totalAmount = $price * $quantity;
            $stmt->execute([$product_id, $productName, $quantity, $price, $totalAmount]);
        } else {
            // Debugging: Product not found
            error_log("Product '$productName' not found in the database.");
            echo json_encode(['success' => false, 'message' => "Product '$productName' not found"]);
            exit;
        }
    }

    // Return success message
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Debugging: Log the error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
