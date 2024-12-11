<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .registration-container {
            width: 100%;
            max-width: 400px; /* Maximum width for larger screens */
            padding: 20px;
            background-color: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .registration-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 15px;
            height: 45px;
        }

        .register-btn {
            width: 100%;
            height: 45px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
        }

        .register-btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .registration-container {
                padding: 15px; /* Reduce padding for smaller screens */
            }

            .form-control {
                height: 40px; /* Slightly smaller input height on mobile */
            }

            .register-btn {
                height: 40px; /* Slightly smaller button height on mobile */
            }
        }
    </style>
</head>
<body>

<div class="registration-container">
    <h3>Admin Registration</h3>
    <form id="admin-registration-form" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" required>
        </div>
        <button type="submit" class="btn register-btn">Register Admin</button>
        <small class="form-text text-muted pl-2 mt-3">Only one admin login account. Enter the password carefully.</small>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('admin-registration-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Simulate form validation (if any) and registration logic
        const username = document.querySelector('input[name="username"]').value;
        const password = document.querySelector('input[name="password"]').value;
        const phoneNumber = document.querySelector('input[name="phone_number"]').value;

        if (username && password && phoneNumber) {
            // Registration logic goes here (e.g., AJAX call, database storage, etc.)

            // Redirect to demo.php after successful registration
            window.location.href = 'demo.php';
        }
    });
</script>

</body>
</html>
