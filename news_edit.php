<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (empty($id) || empty($title) || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
    if ($stmt->execute([$title, $content, $id])) {
        echo json_encode(['success' => true, 'message' => 'News updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update news']);
    }
}
?>
