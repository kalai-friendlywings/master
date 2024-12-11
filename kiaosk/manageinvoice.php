<?php
include('connect.php');

try {
    // Fetch all invoices
    $stmt = $pdo->query('SELECT * FROM invoices');
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error fetching invoices: " . $e->getMessage();
}


// Initialize search query
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Fetch invoices with optional search
try {
    $stmt = $pdo->prepare('
        SELECT * FROM invoices
        WHERE invoice_number LIKE :search
        ORDER BY date DESC
    ');
    $stmt->execute(['search' => '%' . $search . '%']);
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error fetching invoices: " . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Invoices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional custom styles */
        .invoice-container {
            margin-top: 20px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .btn-actions {
            display: flex;
            gap: 10px;
        }
        .form-control{
            width: 10%;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container">
        <div class="invoice-container shadow-sm p-4 bg-white">
            <h1 class="text-center">Manage Invoices</h1>

            <div class="text-end mb-3">
                <a href="add_invoice.php" class="btn" style="background-color: #283694; color:#fff" >Add New Invoice</a>
            </div>
            <form method="GET" class="mb-4">
                <div class="input-group text-end">
                    <input type="text" class="form-control" name="search" placeholder="Search by Invoice Number" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn" style="background-color: #283694; color:#fff" type="submit">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Total (₹)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                                    <td><?php echo htmlspecialchars($invoice['date']); ?></td>
                                    <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                                    <td>₹<?php echo htmlspecialchars($invoice['total']); ?></td>
                                    <td>
                                        <?php if ($invoice['status'] == 'paid'): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-actions">
                                            <a href="viewinvoice.php?id=<?php echo $invoice['id']; ?>" class="btn btn-info btn-sm">View</a>
                                            <a href="editinvoice.php?id=<?php echo $invoice['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="deleteinvoice.php?id=<?php echo $invoice['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No invoices found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>