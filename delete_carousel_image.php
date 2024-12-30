<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Check if the ID is provided
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required']);
        exit;
    }

    // Retrieve the image URL to delete the file
    $stmt = $pdo->prepare("SELECT image_url FROM carousel WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetch();

    if ($image) {
        $imagePath = $image['image_url'];
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath); // Remove the file
        }

        // Delete the image from the database
        $stmt = $pdo->prepare("DELETE FROM carousel WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the image from the database']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
