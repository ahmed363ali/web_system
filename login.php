<?php
session_start();
include 'db.php'; // Include database connection

header('Content-Type: application/json'); // Return JSON responses for AJAX requests

$response = ['success' => false, 'message' => '']; // Initialize response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_captcha = trim($_POST['captcha']);

    // Validate CAPTCHA
    if (!isset($_SESSION['captcha']) || $user_captcha != $_SESSION['captcha']) {
        $response['message'] = "Invalid CAPTCHA. Please try again.";
        echo json_encode($response);
        exit;
    }

    // Validate user inputs
    if (empty($username) || empty($password)) {
        $response['message'] = "Both username and password are required.";
        echo json_encode($response);
        exit;
    }

    try {
        // Check if user exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, log the user in
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Store user role

            $response['success'] = true;
            $response['message'] = "Login successful.";

            // Ensure the redirect URL is correct
            if ($user['role'] === 'admin') {
                $response['redirect'] = '/web_system/admin_panel.html'; // Correct admin redirect
            } else {
                $response['redirect'] = '/web_system/user_dashboard.html'; // Correct user redirect
            }

            echo json_encode($response);
            exit();
        } else {
            $response['message'] = "Invalid username or password.";
            echo json_encode($response);
            exit();
        }
    } catch (PDOException $e) {
        $response['message'] = "Database Error: " . $e->getMessage();
        echo json_encode($response);
        exit();
    }
} else if (isset($_GET['captcha']) || isset($_GET['new_captcha'])) {
    // Generate a new CAPTCHA
    header('Content-Type: image/png');
    $captcha_code = rand(1000, 9999);
    $_SESSION['captcha'] = $captcha_code;

    // Create CAPTCHA image
    $image = imagecreate(100, 40);
    $background_color = imagecolorallocate($image, 255, 255, 255); // White background
    $text_color = imagecolorallocate($image, 0, 0, 0); // Black text
    imagestring($image, 5, 25, 10, $captcha_code, $text_color);

    imagepng($image);
    imagedestroy($image);
    exit;
} else {
    $response['message'] = "Unauthorized access.";
    echo json_encode($response);
    exit;
}
?>
