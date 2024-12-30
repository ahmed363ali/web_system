<?php
include 'db.php';

// Fetch carousel images
$stmt = $pdo->prepare("SELECT * FROM carousel ORDER BY created_at DESC");
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'images' => $images
]);
