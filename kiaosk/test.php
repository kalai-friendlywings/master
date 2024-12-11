   <!-- Sidebar -->
   <div id="sidebar" class="border-end ">
        <div class="p-3">
            <h5 class="text-primary">Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item active ">
                    <a class="nav-link  hover-link" href="adlogin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  hover-link" href="product_list.php">Products</a>
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
        