<?php
// Include the database connection
require_once 'connect.php'; // Make sure this file contains your PDO connection details
// Fetch product data
$query = $pdo->query("SELECT * FROM products"); // Check if table name and column names match your database
$products = $query->fetchAll(PDO::FETCH_ASSOC);
// Initialize $categories as an empty array
$query = $pdo->query("SELECT * FROM categories WHERE categories_active = 1");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Kiosk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
       
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cart = [];
            // Load company details from session storage and display them
            const companyName = sessionStorage.getItem('companyName');
            const phoneNumber = sessionStorage.getItem('phoneNumber');
            const address = sessionStorage.getItem('address');
            if (companyName) {
                document.getElementById('company-name-display').textContent = companyName;
                document.getElementById('company-name-cart').textContent = companyName; // Shopping Cart
            }
            if (phoneNumber) {
                document.getElementById('phone-number-display').textContent = phoneNumber;
            }
            if (address) {
                document.getElementById('user-address').textContent = address;
            }

            function addToCart(name, price, image) {
                const existingItem = cart.find(item => item.name === name);
                if (existingItem) {
                    existingItem.quantity++;
                    existingItem.totalPrice = existingItem.quantity * existingItem.price;
                } else {
                    cart.push({
                        name: name,
                        price: price,
                        image: image,
                        quantity: 1,
                        totalPrice: price
                    });
                }
                updateCartDisplay();
            }

            function increaseQuantity(name) {
                const existingItem = cart.find(item => item.name === name);
                if (existingItem) {
                    existingItem.quantity++;
                    existingItem.totalPrice = existingItem.quantity * existingItem.price;
                    updateCartDisplay();
                }
            }

            function decreaseQuantity(name) {
                const existingItem = cart.find(item => item.name === name);
                if (existingItem) {
                    existingItem.quantity--;
                    existingItem.totalPrice = existingItem.quantity * existingItem.price;

                    if (existingItem.quantity <= 0) {
                        cart = cart.filter(item => item.name !== name);
                    }
                    updateCartDisplay();
                }
            }

            function updateCartDisplay() {
                const cartItemsContainer = document.getElementById('cart-items');
                const totalPriceElement = document.getElementById('total-price');
                const modalTotalPriceElement = document.getElementById('modal-total-price');

                cartItemsContainer.innerHTML = '';
                let total = 0;

                cart.forEach(item => {
                    total += item.totalPrice;
                    const cartItemHTML = `
                <div class="cart-item">
                    <div class="item-details">
                        <span class="item-name">${item.name}</span>
                        <span class="item-quantity"> (X ${item.quantity})</span>
                    </div>
                    <div class="item-price">${item.totalPrice.toFixed(2)}</div>
                    <div>
                        <button class="increase-quantity" data-name="${item.name}">+</button>
                        <button class="decrease-quantity" data-name="${item.name}">-</button>
                    </div>
                </div>
            `;
                    cartItemsContainer.insertAdjacentHTML('beforeend', cartItemHTML);
                });
                totalPriceElement.textContent = `₹${total.toFixed(2)}`;
                modalTotalPriceElement.textContent = `₹${total.toFixed(2)}`;
                document.querySelectorAll('.increase-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const name = this.getAttribute('data-name');
                        increaseQuantity(name);
                    });
                });
                document.querySelectorAll('.decrease-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const name = this.getAttribute('data-name');
                        decreaseQuantity(name);
                    });
                });
            }
            // Event listener for adding items to cart
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const price = parseFloat(this.getAttribute('data-price'));
                    const image = this.parentElement.previousElementSibling.src; // Get the image source from the card
                    addToCart(name, price, image);
                });
            });
            // Update the modal total price when the modal is shown
            const modal = document.getElementById('exampleModal');
            modal.addEventListener('show.bs.modal', function() {
                const modalTotalPriceElement = document.getElementById('modal-total-price');
                const totalPriceElement = document.getElementById('total-price');
                modalTotalPriceElement.textContent = totalPriceElement.textContent;
            });
            // Filter products by category
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    // Get all product items
                    const productItems = document.querySelectorAll('.product-item');
                    productItems.forEach(item => {
                        if (category === 'all' || item.getAttribute('data-category') === category) {
                            item.style.display = ''; // Show the item
                        } else {
                            item.style.display = 'none'; // Hide the item
                        }
                    });
                });
            });
        });
        // Update the modal total price when the modal is shown
        const modal = document.getElementById('exampleModal');
        modal.addEventListener('show.bs.modal', function() {
            const modalTotalPriceElement = document.getElementById('modal-total-price');
            const totalPriceElement = document.getElementById('total-price');
            modalTotalPriceElement.textContent = totalPriceElement.textContent;
        });
        // Print only the cart section
        function printCart() {
            const printContent = document.querySelector('.cart').outerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload(); // Reload to restore the original view
        }
        // Store company details and redirect
        document.addEventListener('DOMContentLoaded', function() {
            // Retrieve company details from session storage
            const companyName = sessionStorage.getItem('companyName');
            const phoneNumber = sessionStorage.getItem('phoneNumber');
            const address = sessionStorage.getItem('address');
            // Check if companyName is retrieved and log for debugging
            console.log("Company Name from Session Storage: ", companyName);
            if (companyName) {
                document.getElementById('company-name-display').textContent = companyName;
                document.getElementById('company-name-cart').textContent = companyName; // Shopping Cart
            } else {
                console.warn('Company name not found in session storage.');
            }
            if (phoneNumber) {
                document.getElementById('phone-number-display').textContent = phoneNumber;
            }
            if (address) {
                document.getElementById('user-address').textContent = address;
            }
        });

        function displayLogo(event) {
            const file = event.target.files[0];
            const logoDisplay = document.getElementById('logo-display');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoDisplay.src = e.target.result; // Set the logo display source to the selected file
                }
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        }
        button.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const image = this.parentElement.previousElementSibling.src; // Get the image source from the card
            addToCart(name, price, image); // Pass image along with name and price
        });
    </script>
</head>

<body>
    <!-- Sidebar -->
    <div id="sidebar" class="border-end ">
        <div class="p-3">
            <h5 class="text-primary">Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item active ">
                    <a class="nav-link  hover-link" href="adlogin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  hover-link" href="product-list.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  hover-link" href="categories.php">Categories</a>
                </li>
                <div class="nav-item">
                    <a class="nav-link  hover-link" href="manageinvoice.php">Invoices</a>
                </div>
                <div class="nav-item">
                    <a class="nav-link  hover-link" href="#">Sales Reports</a>
                </div>
                <div class="nav-item">
                    <a class="nav-link  hover-link" href="Users.php">Shop Users</a>
                </div>
                <li class="nav-item">
                    <a class="nav-link  hover-link" href="#">Settings</a>
                </li>
            </ul>
        </div>
    </div>


    <!-- Main Content -->
    <div id="content" class="p-0">
        <nav class="navbar navbar-expand-lg navbar-light glass-navbar">
            <button
                id="toggleSidebar"
                class="btn btn-primary ms-2"
                style="border-radius: 50%;"
                type="button">
                ☰
            </button>
            <a class="navbar-brand d-flex align-items-center" style="padding-left: 10px;" href="#">
                <input type="file" id="logo" accept="image/*" onchange="displayLogo(event)" style="display: none;">
                <label for="logo">
                    <img id="logo-display" src="/path/to/your/logo.png" alt="Company Logo" class="d-inline-block align-text-top">
                </label>
                <h1 id="company-name-display" class="mb-0">Orcas</h1>
            </a>
            <div class="dropdown ms-5" style="width: fit-content; ">
                <input type="text" id="search-bar" class="form-control glass-input" placeholder="Enter product name..." autocomplete="off">
                <div id="product-lists" class="dropdown-menu"></div>
            </div>
        </nav>

        <!-- menu button -->
        <div class="cont sticky " style="background-color:aliceblue;" id="filter-buttons">
            <button class="btn btn filter-btn mx-2" style="border-radius:20px; font-size:small;  box-shadow: 0 5px 12px rgba(14, 13, 13, 0.203); background-color:#aeb0b0; color:#fff;" data-category="all">All </button>
            <?php foreach ($categories as $category): ?>
                <button class="btn btn filter-btn" style="border-radius:16px; font-size: small; box-shadow: 0 2px 2px rgba(14, 13, 13, 0.203); background-color:#aeb0b0; color:#fff;" data-category="<?= htmlspecialchars($category['categories_id']); ?>">
                    <?= htmlspecialchars($category['categories_name']); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <!-- Page Content -->
        <div class="container-fluid">
            <div class="row">
                <!-- Product Cards Section -->
                <div class="col-12 col-md-8 mt-3 m-3">
                    <div class="row" id="product-list">
                        <?php foreach ($products as $product) : ?>
                            <div class="col-12 col-md-4 product-item" data-category="<?= htmlspecialchars($product['category_id']); ?>">
                                <div class="card h-30">
                                    <!-- Display product image or a placeholder if image is missing -->
                                    <img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : 'path_to_placeholder_image.jpg'; ?>"
                                        alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                        class="card-img-top"
                                        style="margin: 8px; margin-left:20px; object-fit: cover;" loading="lazy">
                                    <div class="card-body">
                                        <!-- Check logo_type for Veg or Non-Veg -->
                                        <?php if ($product['logo_type'] == 'veg'): ?>
                                            <img src="logo/132636849771dbfe43013a08764eb9f3.jpg" alt="Veg Logo" class="logo-img" style="width: 20px;" loading="lazy">
                                        <?php else: ?>
                                            <img src="logo/1531813245.png" alt="Non-Veg Logo" class="logo-img" style="width: 30px;">
                                        <?php endif; ?>
                                        <!-- Display product name -->
                                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <!-- Display product price -->
                                            <span class="price">₹<?= htmlspecialchars($product['price']) ?></span>
                                            <!-- Add to Cart button -->
                                            <button class="btn btn-primary add-to-cart"
                                                data-name="<?= htmlspecialchars($product['name']) ?>"
                                                data-price="<?= htmlspecialchars($product['price']) ?>">
                                                Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Shopping Cart Section -->
                <div class="col-12 col-md-4 ml-3 sticky-cart">
                    <div class="cart" style="background-color: #fff;">
                        <h1 id="company-name-cart">Orcas</h1>
                        <h6 id="phone-number-display">snack and smoothies</h6>
                        <p id="user-address">near : Anantham</p>
                        <hr>
                        <div id="cart-items">
                            <!-- Cart items will be dynamically inserted here -->
                        </div>
                        <div class="cart-total">
                            <span>Total:</span>
                            <span id="total-price">₹0</span>
                        </div>
                        <hr>
                        <p style="font-weight: bold;">THANK YOU!</p>
                    </div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-primary my-1" style="padding: 10px 114px;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Payment process</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="cart-total">
                        <span>Total:</span>
                        <span id="modal-total-price">₹0</span>
                    </div>
                </div>

                <br>
                <a href="javascript:void(0);" id="print-button-card" style="background-color: #aeb0b0; text-align:center; color: aliceblue; margin: 0px 15px; padding: 5px; margin-bottom: 10px;  " onclick="processCardPayment()">CARD</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleSidebarButton = document.getElementById('toggleSidebar');

        toggleSidebarButton.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('full-width');
        });
        $(document).ready(function() {
            $('#search-bar').on('input', function() {
                let query = $(this).val().trim();

                if (query.length > 0) {
                    $.ajax({
                        url: 'search_product.php',
                        type: 'POST',
                        data: {
                            search: query
                        },
                        success: function(response) {
                            $('#product-lists').html(response).show();
                        },
                        error: function() {
                            $('#product-lists').hide();
                        }
                    });
                } else {
                    $('#product-lists').hide();
                }
            });

            // Hide dropdown on click outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('#product-lists').hide();
                }
            });

            // Populate search input with selected item
            $(document).on('click', '.product-name', function() {
                let productName = $(this).text();
                $('#search-bar').val(productName);
                $('#product-lists').hide();
            });
        });

        document.getElementById('print-button').addEventListener('click', function() {
            window.print();
        });
        // Store company details
        function displayLogo(event) {
            const file = event.target.files[0];
            const logoDisplay = document.getElementById('logo-display');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoDisplay.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function processPayment() {
            let cartItems = [];
            let totalPrice = document.getElementById('total-price').innerText.replace('₹', '').trim(); // Remove currency symbol

            // Collect cart items data
            document.querySelectorAll('#cart-items .cart-item').forEach(function(item) {
                let productName = item.querySelector('.product-name').innerText;
                let quantity = item.querySelector('.product-quantity').innerText;
                cartItems.push({
                    productName: productName,
                    quantity: quantity
                });
            });

            // Ensure cart has items
            if (cartItems.length === 0) {
                alert('No items in cart');
                return;
            }
            // Send the data to the server
            fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cartItems: cartItems,
                        totalPrice: totalPrice
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Payment processed successfully');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
        // Toggle Sidebar Script
        document.getElementById('toggle-btn').addEventListener('click', function() {
            let sidebar = document.getElementById('sidebar');
            let content = document.getElementById('content');
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                content.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            }
        });
    </script>
</body>

</html>