<?php
include 'connect.php'; // Database connection

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Invoice</title>
</head>
<body>
    <div class="container">
        <h1>Invoice</h1>
        <p>Product: <?php echo $product['name']; ?></p>
        <p>Price: $<?php echo $product['price']; ?></p>
        <p>Thank you for your purchase!</p>
        <button onclick="window.print()">Print Invoice</button>
    </div>
    <script src="js/jquery.min.js"></script>
</body>
</html>
