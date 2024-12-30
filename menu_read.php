<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login_test.html"); // Redirect unauthorized users
    exit();
}
try {
    // Fetch all menus
    $stmtMenus = $pdo->query("SELECT * FROM menus");
    $menus = $stmtMenus->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all submenus
    $stmtSubmenus = $pdo->query("SELECT * FROM submenus");
    $submenus = $stmtSubmenus->fetchAll(PDO::FETCH_ASSOC);

    // Group submenus by parent_id
    $groupedSubmenus = [];
    foreach ($submenus as $submenu) {
        $groupedSubmenus[$submenu['parent_id']][] = $submenu;
    }

    // Attach submenus to their respective menus
    foreach ($menus as &$menu) {
        $menu['submenus'] = $groupedSubmenus[$menu['id']] ?? [];
    }

    echo json_encode([
        'success' => true,
        'menu' => $menus,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching menus: ' . $e->getMessage(),
    ]);
}
?>
