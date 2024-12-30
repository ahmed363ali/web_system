<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_test.html"); // Redirect unauthorized users
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $url = isset($_POST['url']) ? $_POST['url'] : '';

    if (!empty($name) && !empty($url)) {
        $stmt = $pdo->prepare("INSERT INTO menu (name, url) VALUES (?, ?)");
        $stmt->execute([$name, $url]);

        echo json_encode(['success' => true, 'message' => 'Menu item added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Name and URL are required.']);
    }
}
?>
