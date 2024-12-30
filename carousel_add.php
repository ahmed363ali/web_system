<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['image']['name'])) {
        $targetDir = 'uploads/';
        $targetFile = $targetDir . basename($_FILES['image']['name']);

        // Validate file type
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Insert the image path into the database
                $stmt = $pdo->prepare("INSERT INTO carousel (image_url) VALUES (:image_url)");
                $stmt->execute(['image_url' => $targetFile]);

                echo json_encode(['success' => true, 'message' => 'Image added to carousel successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload the image.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No image file selected.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
