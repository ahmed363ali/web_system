<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only admins can manage content.";
    exit();
}

try {
    // Fetch content with additional details
    $stmt = $pdo->query("SELECT * FROM content ORDER BY created_at DESC");
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Content Management</h1>

    <!-- Add New Content Form -->
    <form action="add_content.php" method="POST">
        <h2>Add New Content</h2>
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="body">Body:</label><br>
        <textarea id="body" name="body" rows="5" required></textarea><br>
        <button type="submit">Add Content</button>
    </form>

    <br>

    <!-- Content Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Body</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($contents)): ?>
                <?php foreach ($contents as $content): ?>
                <tr>
                    <td><?= htmlspecialchars($content['id']) ?></td>
                    <td><?= htmlspecialchars($content['title']) ?></td>
                    <td><?= htmlspecialchars($content['body']) ?></td>
                    <td><?= htmlspecialchars($content['created_at']) ?></td>
                    <td class="actions">
                        <a href="edit_content.php?id=<?= $content['id'] ?>">Edit</a>
                        <a href="delete_content.php?id=<?= $content['id'] ?>" onclick="return confirm('Are you sure you want to delete this content?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No content available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
