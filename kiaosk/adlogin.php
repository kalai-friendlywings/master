<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    // If admin is not logged in, redirect to the login page
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

        .login-container {
            width: 100%;
            max-width: 400px; /* Maximum width for larger screens */
            padding: 20px;
            background-color: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .login-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 15px;
            height: 45px;
        }

        .login-btn {
            width: 100%;
            height: 45px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }

        .form-text {
            text-align: center;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 15px; /* Reduce padding for smaller screens */
            }

            .form-control {
                height: 40px; /* Slightly smaller input height on mobile */
            }

            .login-btn {
                height: 40px; /* Slightly smaller button height on mobile */
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3>Admin Login</h3>
    <form method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn login-btn">Login</button>
        <small class="form-text text-muted mt-3">Only the admin can access this page.</small>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
