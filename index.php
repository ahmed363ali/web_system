<?php
session_start();

if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 0; // Initialize visit count
}
$_SESSION['visit_count']++; // Increment visit count
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.html'; ?>

    <h1>Welcome to the Home Page</h1>
    <h2>Page Visit Count</h2>
    <p>You have visited this page <strong><?php echo $_SESSION['visit_count']; ?></strong> times during this session.</p>
</body>
</html>
