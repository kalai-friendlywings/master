<?php
session_start();

require 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirm_password'])) {
        // Registration logic
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            echo "Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed_password])) {
                echo "Registration successful!";
            } else {
                echo "Registration failed!";
            }
        }
    } else {
        // Login logic
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: demo.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Styling omitted for brevity */
        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }

        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }

        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form button {
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background: #4CAF50;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .form button:hover,
        .form button:active,
        .form button:focus {
            background: #43A047;
        }

        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }

        .form .message a {
            color: #4CAF50;
            text-decoration: none;
        }

        .form .register-form {
            display: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
        }
    </style>
</head>

<body>
    <div class="login-page">
        <div class="form">
            <form class="register-form" method="POST" id="registration-form">
                <input type="text" name="username" placeholder="username" required />
                <input type="password" name="password" placeholder="password" id="password" required />
                <input type="password" name="confirm_password" placeholder="confirm password" id="confirm_password" required />
                <span id="password-error" style="color: red; display: none;">Passwords do not match!</span>
                <button type="submit">Register</button>
                <p class="message">Already registered? <a href="javascript:void(0)">Sign In</a></p>
            </form>

            <form class="login-form" method="POST">
                <input type="text" name="username" placeholder="username" required />
                <input type="password" name="password" placeholder="password" required />
                <button type="submit">Login</button>
                <p class="message">Not registered? <a href="javascript:void(0)">Create an account</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        // Toggle between login and register forms
        $('.message a').click(function(e) {
            e.preventDefault();  // Prevent default link behavior
            $('form').animate({
                height: "toggle",
                opacity: "toggle"
            }, "slow");
        });

        // JavaScript for password matching (if needed)
        const form = document.getElementById('registration-form');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                passwordError.style.display = 'block';
            } else {
                passwordError.style.display = 'none';
            }
        });
    </script>
</body>

</html>
