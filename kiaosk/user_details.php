<?php
session_start();

// Ensure the database connection file exists
if (!file_exists('db.php')) {
    die("Database file not found.");
}

require '../db.php'; // If db.php is one level above the current file
 // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user) {
    echo "<h1>User Details</h1>";
    echo "Username: " . htmlspecialchars($user['username']) . "<br>";
    echo "Balance: $" . htmlspecialchars($user['balance']) . "<br>";
    // Add more user details as needed
} else {
    echo "User not found.";
}
?>
<a href="transaction.php">View Transaction History</a>
<a href="logout.php">Logout</a>
