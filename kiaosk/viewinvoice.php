<?php
include('connect.php');

if (isset($_GET['id'])) {
    $invoiceId = intval($_GET['id']);

    try {
        // Fetch invoice by ID
        $stmt = $pdo->prepare('SELECT * FROM invoices WHERE id = :id');
        $stmt->execute(['id' => $invoiceId]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invoice) {
            echo "Invoice not found!";
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "No invoice ID specified.";
    exit;
}

// Fetch invoice items along with product names
$itemsStmt = $pdo->prepare('
    SELECT ii.*, p.product_name 
    FROM invoice_items ii
    JOIN products p ON ii.product_id = p.product_id
    WHERE ii.invoice_id = :invoice_id
');
$itemsStmt->execute(['invoice_id' => $invoiceId]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo htmlspecialchars($invoice['invoice_number']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-container {
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-top: 20px;
            background-color: #fff;
        }
        .invoice-header h1 {
            font-size: 2rem;
        }
        .invoice-details, .invoice-footer {
            margin-top: 20px;
        }
        .invoice-items table {
            margin-top: 20px;
        }
        .total-amount {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
        }
        .print-btn {
            margin-top: 30px;
            text-align: center;
        }
        .text-end h5, .text-end p {
            text-align: right;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-light">

    <div class="container">
        <div class="invoice-container shadow-sm">
            <div class="invoice-header text-center">
                <h1>Invoice</h1>
                <p>Invoice #: <strong><?php echo htmlspecialchars($invoice['invoice_number']); ?></strong></p>
                <p>Date: <strong><?php echo htmlspecialchars($invoice['date']); ?></strong></p>
            </div>

            <div class="invoice-details">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Client Details:</h5>
                        <p>Name: <strong><?php echo htmlspecialchars($invoice['customer_name']); ?></strong></p>
                        <p>Contact: <strong><?php echo htmlspecialchars($invoice['customer_contact']); ?></strong></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h5>Company Details:</h5>
                        <p id="company-name-cart">Company Name: <strong>Your Company Name</strong></p>
                        <p id="user-address">Address: <strong>Your Company Address</strong></p>
                        <p id="phone-number-display">Contact Number: <strong>Your Company Contact Number</strong></p>
                    </div>
                </div>
            </div>

            <div class="invoice-items">
                <h5>Items:</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Description</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($items): ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td>₹<?php echo htmlspecialchars($item['rate']); ?></td>
                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    <td>₹<?php echo htmlspecialchars($item['total']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No items found for this invoice.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="total-amount">
                    Total: ₹<span><?php echo htmlspecialchars($invoice['total']); ?></span>
                </div>
            </div>

            <div class="invoice-footer text-center">
                <p>Thank you for your business!</p>
                <div class="print-btn">
                    <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
                    <a href="manageinvoice.php" class="btn btn-secondary">Back to Invoices</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
