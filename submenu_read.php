<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_test.html"); // Redirect unauthorized users
    exit();
}
$menu_id = $_GET['menu_id'];

$stmt = $pdo->prepare("SELECT * FROM submenus WHERE parent_id = ?");
$stmt->execute([$menu_id]);
$submenus = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'submenu' => $submenus
]);
?>
