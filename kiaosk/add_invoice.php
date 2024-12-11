<?php
include('connect.php');

// Fetch products from the database
$productsStmt = $pdo->query("SELECT * FROM products");
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

// Generate automatic invoice number
$lastInvoiceStmt = $pdo->query("SELECT invoice_number FROM invoices ORDER BY id DESC LIMIT 1");
$lastInvoice = $lastInvoiceStmt->fetch(PDO::FETCH_ASSOC);
$nextInvoiceNumber = isset($lastInvoice['invoice_number']) ? '#' . (intval(substr($lastInvoice['invoice_number'], 1)) + 1) : '#1';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $invoice_number = $_POST['invoice_number'] ?? '';
    $date = $_POST['date'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_contact = $_POST['customer_contact'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    $total = $_POST['total'] ?? 0.00;
    $productsSelected = $_POST['products'] ?? [];
    $quantities = $_POST['quantities'] ?? [];

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        // Insert invoice into the database
        $stmt = $pdo->prepare('INSERT INTO invoices (invoice_number, date, customer_name, customer_contact, status, total) 
                               VALUES (:invoice_number, :date, :customer_name, :customer_contact, :status, :total)');
        $stmt->execute([
            'invoice_number' => $invoice_number,
            'date' => $date,
            'customer_name' => $customer_name,
            'customer_contact' => $customer_contact,
            'status' => $status,
            'total' => $total
        ]);

        // Get the last inserted invoice ID
        $invoiceId = $pdo->lastInsertId();

        // Insert each product into the invoice_items table
        $itemsStmt = $pdo->prepare('INSERT INTO invoice_items (invoice_id, product_id, quantity, total) 
                                    VALUES (:invoice_id, :product_id, :quantity, :total)');
        
        foreach ($productsSelected as $key => $productId) {
            $quantity = $quantities[$key];
            
            // Fetch product price
            $product = $pdo->prepare('SELECT price FROM products WHERE product_id = :product_id');
            $product->execute(['product_id' => $productId]);
            $productData = $product->fetch(PDO::FETCH_ASSOC);
            $price = $productData['price'];

            // Calculate total price for this product
            $totalPrice = $price * $quantity;

            // Insert item into invoice_items table
            $itemsStmt->execute([
                'invoice_id' => $invoiceId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'total' => $totalPrice
            ]);
        }

        // Commit the transaction
        $pdo->commit();

        // Redirect to manage invoice page after adding
        header('Location: manageinvoice.php');
        exit;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        echo "Error adding invoice: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="style.css">
   <script>
        // Add a new product row dynamically
        function addProductRow() {
            let productRow = `
            <div class="row mb-3 product-row">
                <div class="col-md-6">
                    <select name="products[]" class="form-select" onchange="updateTotal()" required>
                        <option value="">Select Product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['product_id']; ?>" data-price="<?php echo $product['price']; ?>">
                                <?php echo htmlspecialchars($product['product_name']); ?> (₹<?php echo number_format($product['price'], 2); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="quantities[]" class="form-control" placeholder="Quantity" min="1" oninput="updateTotal()" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </div>
            </div>`;
            document.getElementById('product-list').insertAdjacentHTML('beforeend', productRow);
        }

        // Remove a product row
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-row')) {
                event.target.closest('.product-row').remove();
                updateTotal();
            }
        });

        // Update the total when products or quantities change
        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(function(row) {
                let productSelect = row.querySelector('select');
                let quantityInput = row.querySelector('input[name="quantities[]"]');
                let price = productSelect.selectedOptions[0]?.getAttribute('data-price') || 0;
                let quantity = quantityInput.value || 0;
                total += parseFloat(price) * parseInt(quantity);
            });
            document.getElementById('total').value = total.toFixed(2);
        }
    </script>
</head>

<body class="bg-light">
    <div class="container">
        <div class="invoice-container shadow-sm p-4 bg-white mt-5">
            <h2 class="text-center">Add New Invoice</h2>

            <form method="POST" action="add_invoice.php">
                <div class="mb-3">
                    <label for="invoice_number" class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?php echo $nextInvoiceNumber; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Invoice Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="customer_contact" class="form-label">Customer Contact</label>
                    <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
                </div>

                <!-- Product Selection -->
                <div class="mb-3">
                    <label for="products" class="form-label">Products</label>
                    <div id="product-list">
                        <div class="row mb-3 product-row">
                            <div class="col-md-6">
                                <select name="products[]" class="form-select" onchange="updateTotal()" required>
                                    <option value="">Select Product</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?php echo $product['product_id']; ?>" data-price="<?php echo $product['price']; ?>">
                                            <?php echo htmlspecialchars($product['product_name']); ?> (₹<?php echo number_format($product['price'], 2); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="quantities[]" class="form-control" placeholder="Quantity" min="1" oninput="updateTotal()" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="addProductRow()">Add Another Product</button>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending" selected>Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="total" class="form-label">Total (₹)</label>
                    <input type="number" class="form-control" id="total" name="total" step="0.01" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Add Invoice</button>
                <a href="manageinvoice.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>
