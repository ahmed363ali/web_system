<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];

    try {
        $stmt = $pdo->prepare("INSERT INTO content (title, body) VALUES (?, ?)");
        $stmt->execute([$title, $body]);
        echo "Content added successfully.";
        header('Location: content_management.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
