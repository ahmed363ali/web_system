<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_url = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $image_url = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
    }

    $stmt = $pdo->prepare("INSERT INTO news (title, content, image_url) VALUES (?, ?, ?)");
    if ($stmt->execute([$title, $content, $image_url])) {
        echo json_encode(['success' => true, 'message' => 'News added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add news.']);
    }
}

?>
