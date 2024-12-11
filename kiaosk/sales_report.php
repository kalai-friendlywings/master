<?php
// Database connection
include 'connect.php';

$sales = [];
$totalSales = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    try {
        // Query for sales data
        $stmt = $pdo->prepare("
            SELECT 
                s.id, 
                p.product_name, 
                s.quantity, 
                s.price, 
                (s.quantity * s.price) AS total_amount, 
                s.sale_date
            FROM sales s
            INNER JOIN products p ON s.product_id = p.product_id
            WHERE s.sale_date BETWEEN :startDate AND :endDate
            ORDER BY s.sale_date DESC;
        ");
        $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query for total sales amount
        $totalStmt = $pdo->prepare("
            SELECT SUM(s.quantity * s.price) AS total_sales
            FROM sales s
            WHERE s.sale_date BETWEEN :startDate AND :endDate;
        ");
        $totalStmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $totalSales = $totalStmt->fetchColumn() ?: 0; // Default to 0 if no sales data
    } catch (PDOException $e) {
        echo "Error fetching sales data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Sales Report</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Sales Report</h1>

        <!-- Filter Form -->
        <form method="post" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" name="startDate" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" name="endDate" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </div>
            </div>
        </form>

        <!-- Total Sales -->
        <h4>Total Sales: ₹<?php echo number_format($totalSales, 2); ?></h4>

        <!-- Sales Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Amount</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sales)): ?>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                            <td>₹<?php echo htmlspecialchars($sale['price']); ?></td>
                            <td>₹<?php echo htmlspecialchars($sale['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No sales data available for the selected date range.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
