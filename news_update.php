<?php
session_start();
include 'db.php'; // Database connection

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST data
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $existingImage = $_POST['existing_image'] ?? null;

    // Validate required fields
    if (!$id || !$title || !$content) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    try {
        // Handle optional image upload
        $newImage = $existingImage; // Default to existing image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = 'uploads/' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $newImage = $imagePath;
            }
        }

        // Update query
        $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$title, $content, $newImage, $id]);

        // Check if the update was successful
        if ($stmt->rowCount()) {
            echo json_encode(['success' => true, 'message' => 'News updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes were made to the news.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
