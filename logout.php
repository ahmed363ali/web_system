<?php
session_start(); // Start the session

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear any login-related cookies (if applicable)
setcookie('user_id', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');
setcookie('user_role', '', time() - 3600, '/');

// Redirect to login page with a logout success message
header("Location: login_test.html?message=logout_success");
exit;
?>
