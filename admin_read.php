<?php
include 'db.php'; // Include database connection

header('Content-Type: application/json');

// Fetch all users
try {
    $stmt = $pdo->query("SELECT id, username, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'users' => $users]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching users.']);
    error_log($e->getMessage());
}
