<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: admin.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$cat = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$cat) { header('Location: admin.php'); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (empty($name)) $errors[] = "Nom obligatoire.";
    if (empty($errors)) { $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND id != ?"); $stmt->execute([$name, $id]); if ($stmt->fetch()) $errors[] = "Nom déjà utilisé."; }
    if (empty($errors)) { $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?"); $stmt->execute([$name, $id]); header('Location: admin.php?success=Catégorie modifiée'); exit; }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier catégorie</title>
    <?php include 'includes/styles.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <main class="main-content">
        <a href="admin.php" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-3"><i class="bi bi-arrow-left"></i> Retour</a>
        <div class="page-header"><h1 class="page-title">Modifier la catégorie</h1></div>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <?php if (!empty($errors)): ?>
                            <div class="alert border-0 rounded-3 py-2 mb-4" style="background:#fef2f2;color:#991b1b;">
                                <?php foreach ($errors as $e): ?><small class="d-block">⚠️ <?= $e ?></small><?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-muted">NOM</label>
                                <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($cat['name']) ?>" required>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary-custom flex-fill">Enregistrer</button>
                                <a href="admin.php" class="btn btn-secondary-custom flex-fill text-center">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>