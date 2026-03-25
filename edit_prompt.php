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

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    
    if (empty($title)) $errors[] = "Le titre est obligatoire.";
    if (empty($content)) $errors[] = "Le contenu est obligatoire.";
    if (empty($category_id)) $errors[] = "Choisissez une catégorie.";
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE prompts SET title = ?, content = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$title, $content, $category_id, $id]);
        $success = "Prompt modifié !";
        $stmt = $pdo->prepare("SELECT * FROM prompts WHERE id = ?");
        $stmt->execute([$id]);
        $prompt = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier — Prompt Repository</title>
    <?php include 'includes/styles.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        
        <a href="view_prompt.php?id=<?= $id ?>" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i> Retour au prompt
        </a>
        
        <div class="page-header">
            <h1 class="page-title">Modifier le prompt</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-custom">
                    <div class="card-body-custom">
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert border-0 rounded-3 py-2 mb-4" style="background:#fef2f2;color:#991b1b;">
                                <?php foreach ($errors as $e): ?><small class="d-block">⚠️ <?= $e ?></small><?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert border-0 rounded-3 py-2 mb-4" style="background:#f0fdf4;color:#166534;">
                                <small>✅ <?= $success ?></small>
                                <a href="view_prompt.php?id=<?= $id ?>" class="small fw-semibold d-block" style="color:#166534;">Voir le prompt →</a>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-muted">TITRE</label>
                                <input type="text" name="title" class="form-input" value="<?= htmlspecialchars($prompt['title']) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-muted">CATÉGORIE</label>
                                <select name="category_id" class="form-input" required>
                                    <?php foreach ($categories as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= ($prompt['category_id'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-muted">CONTENU</label>
                                <textarea name="content" class="form-input" style="font-family:monospace;min-height:250px;" required><?= htmlspecialchars($prompt['content']) ?></textarea>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary-custom flex-fill">Enregistrer</button>
                                <a href="view_prompt.php?id=<?= $id ?>" class="btn btn-secondary-custom flex-fill text-center">Annuler</a>
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