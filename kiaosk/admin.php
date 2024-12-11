<?php
session_start();

// Ensure the database connection file exists
if (!file_exists('db.php')) {
    die("Database file not found.");
}

require 'db.php'; // Include the database connection file

// Fetch all users from the database
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-inline {
            margin-bottom: 20px;
        }

        .form-control {
            margin-right: 10px;
        }

  
    </style>
</head>

<body>
    
    <div class="table-container">
        <h1>User Management</h1>
        <button class="btn btn-primary back-btn" onclick="goBack()" style="margin-bottom: 8px;  color:aliceblue;">Back</button>
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td>******</td> <!-- Do not display actual passwords -->
                        <td>
                            <!-- Form to update password -->
                            <form method="POST" action="admin_update_password.php" class="form-inline">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <input type="password" name="new_password" class="form-control" placeholder="New password" required>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
      function goBack() {
        window.location.href = 'demo.php';
    }
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>