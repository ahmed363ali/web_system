<?php
session_start();
include 'db.php'; // Include database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied. Only admins can add users.']);
    exit();
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'user';

    // Validate input
    if (empty($username) || empty($password) || empty($role)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    if ($role !== 'admin' && $role !== 'user') {
        echo json_encode(['success' => false, 'message' => 'Invalid role. Only "admin" or "user" are allowed.']);
        exit();
    }

    try {
        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            exit();
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashedPassword, $role]);

        echo json_encode(['success' => true, 'message' => 'User added successfully.']);
    } catch (PDOException $e) {
        // Log the error and return a failure message
        error_log('Database Error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred while adding the user.']);
    }
} else {
    // Return a 405 error if the request method is not POST
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
}
?>
