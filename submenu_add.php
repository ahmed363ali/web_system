<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_test.html"); // Redirect unauthorized users
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parent_id = $_POST['parent_id'];
    $name = $_POST['submenu_name'];
    $url = $_POST['submenu_url'];

    $stmt = $pdo->prepare("INSERT INTO submenus (parent_id, name, url) VALUES (?, ?, ?)");
    $result = $stmt->execute([$parent_id, $name, $url]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Submenu added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add submenu.']);
    }
}
?>
