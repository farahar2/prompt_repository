<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: admin.php'); exit; }

$stmt = $pdo->prepare("SELECT categories.*, COUNT(prompts.id) AS pc FROM categories LEFT JOIN prompts ON categories.id = prompts.category_id WHERE categories.id = ? GROUP BY categories.id");
$stmt->execute([$id]);
$cat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cat) { header('Location: admin.php?error=Non trouvée'); exit; }
if ($cat['pc'] > 0) { header('Location: admin.php?error=Contient des prompts'); exit; }

$stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin.php?success=Catégorie supprimée');
exit;
?>