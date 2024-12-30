<?php
$hash = '$2y$10$vEKN8F2QWa2hsE99xHzRgeLmpn/eixGqR0s1sCN50ZbBojB60UdK.'; // Replace with your hash
$password = '12345';

if (password_verify($password, $hash)) {
    echo "Password is correct!";
} else {
    echo "Password verification failed!";
}
