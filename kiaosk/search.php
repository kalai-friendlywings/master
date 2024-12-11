<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .dropdown-menu {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3>Search for Products</h3>
    <div class="dropdown">
        <input type="text" id="search-bar" class="form-control" placeholder="Enter product name..." autocomplete="off">
        <div id="product-list" class="dropdown-menu"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#search-bar').on('input', function() {
            let query = $(this).val();
            
            if (query.length > 0) {
                $.ajax({
                    url: 'search_product.php',
                    type: 'POST',
                    data: { search: query },
                    success: function(response) {
                        $('#product-list').html(response).show();
                    }
                });
            } else {
                $('#product-list').hide();
            }
        });

        // Hide the dropdown when clicking outside of it
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('#product-list').hide();
            }
        });

        // Set search bar value on clicking a suggestion
        $(document).on('click', '.product-name', function() {
            let productName = $(this).data('name');
            $('#search-bar').val(productName);
            $('#product-list').hide();
        });
    });
</script>
</body>
</html>
