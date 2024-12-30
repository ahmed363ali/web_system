<?php
include 'db.php'; // Include database connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    // Validate input
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'User ID is required.']);
        exit();
    }

    try {
        // Delete user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting user.']);
        error_log($e->getMessage());
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
