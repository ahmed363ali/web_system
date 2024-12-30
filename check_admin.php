<?php
session_start();
include 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_test.html");
    exit();
}

// Fetch the user's role from the database
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if the user is not an admin
if ($user['role'] !== 'admin') {
    echo "Access Denied: You do not have sufficient privileges.";
    exit();
}
?>
