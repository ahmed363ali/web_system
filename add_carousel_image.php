<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['carousel_image'])) {
    $image = $_FILES['carousel_image'];
    $uploadDir = 'uploads/carousel/';
    $filePath = $uploadDir . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $filePath)) {
        $stmt = $pdo->prepare("INSERT INTO carousel (url) VALUES (?)");
        $stmt->execute([$filePath]);
        echo json_encode(['success' => true, 'message' => 'Image added to carousel.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
