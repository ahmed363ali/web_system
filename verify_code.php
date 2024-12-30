<?php
session_start();

// CAPTCHA Generation
if (isset($_GET['captcha'])) {
    header('Content-Type: image/png');

    $captcha_code = rand(1000, 9999);
    $_SESSION['captcha'] = $captcha_code;

    // Create CAPTCHA Image
    $image = imagecreate(100, 40);
    $background_color = imagecolorallocate($image, 255, 255, 255); // White background
    $text_color = imagecolorallocate($image, 0, 0, 0); // Black text
    imagestring($image, 5, 25, 10, $captcha_code, $text_color);

    imagepng($image);
    imagedestroy($image);
    exit;
}

// Login and Verification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_captcha = $_POST['captcha'];

    // Validate CAPTCHA
    if ($user_captcha != $_SESSION['captcha']) {
        echo "Invalid CAPTCHA. Please try again.";
        exit;
    }

    // Simulate User Login Validation
    if ($username === "admin" && $password === "1234") {
        // Generate Verification Code
        $verification_code = rand(100000, 999999);
        $_SESSION['verification_code'] = $verification_code;

        // Simulate Sending Email
        // Use PHP's `mail()` function in a real scenario
        echo "Verification code sent to your email! Your code is: " . $verification_code;
    } else {
        echo "Invalid username or password.";
    }
}
?>
