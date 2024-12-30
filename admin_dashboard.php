<?php
include 'db.php'; // Include database connection

header('Content-Type: application/json');

// Fetch statistics
try {
    $stmtUsers = $pdo->query("SELECT COUNT(*) as usersCount FROM users");
    $usersCount = $stmtUsers->fetch(PDO::FETCH_ASSOC)['usersCount'];

    $stmtPosts = $pdo->query("SELECT COUNT(*) as postsCount FROM posts"); // Update 'posts' if you use a different table
    $postsCount = $stmtPosts->fetch(PDO::FETCH_ASSOC)['postsCount'];

    echo json_encode(['usersCount' => $usersCount, 'postsCount' => $postsCount]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching statistics.']);
    error_log($e->getMessage());
}
