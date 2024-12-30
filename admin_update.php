<?php
include 'db.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validate input
    if (empty($id) || empty($role)) {
        echo json_encode(['success' => false, 'message' => 'User ID and role are required.']);
        exit();
    }

    try {
        // Update user role
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);

        echo json_encode(['success' => true, 'message' => 'User role updated successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating user role.']);
        error_log($e->getMessage());
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
