<?php
include 'db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM news ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalStmt = $pdo->query("SELECT FOUND_ROWS() as total");
$total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

echo json_encode(['success' => true, 'news' => $news, 'page' => $page, 'limit' => $limit, 'total' => $total]);
?>
