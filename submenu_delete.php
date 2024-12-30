<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_test.html"); // Redirect unauthorized users
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM submenus WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Submenu deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete submenu.']);
    }
}
?>
