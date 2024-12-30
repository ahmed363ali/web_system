<?php
session_start();
include 'db.php';

// Ensure only admin users can delete images
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Fetch the image URL from the database
    $stmt = $pdo->prepare("SELECT image_url FROM carousel WHERE id = ?");
    $stmt->execute([$id]);
    $carousel = $stmt->fetch();

    if ($carousel) {
        // Delete the image file from the server
        $imagePath = $carousel['image_url'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the file
        }

        // Delete the record from the database
        $stmt = $pdo->prepare("DELETE FROM carousel WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true, 'message' => 'Carousel image deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete image from database']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
