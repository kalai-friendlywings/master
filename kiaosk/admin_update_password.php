<?php
session_start();

// Ensure the database connection file exists
if (!file_exists('db.php')) {
    die("Database file not found.");
}

require 'db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    if ($stmt->execute([$hashed_password, $user_id])) {
        echo "<script>alert('Password updated successfully!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "Failed to update password!";
    }
}
?>
