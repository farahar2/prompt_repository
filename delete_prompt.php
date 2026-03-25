<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: prompts.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM prompts WHERE id = ?");
$stmt->execute([$id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prompt || $prompt['user_id'] != $_SESSION['user_id']) { header('Location: prompts.php'); exit; }

$stmt = $pdo->prepare("DELETE FROM prompts WHERE id = ?");
$stmt->execute([$id]);

header('Location: prompts.php?deleted=1');
exit;
?>