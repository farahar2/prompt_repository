<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filter = $_GET['category'] ?? '';
$deleted = $_GET['deleted'] ?? '';

if (!empty($filter)) {
    $stmt = $pdo->prepare("SELECT prompts.*, users.username, categories.name AS category_name FROM prompts JOIN users ON prompts.user_id = users.id JOIN categories ON prompts.category_id = categories.id WHERE prompts.category_id = ? ORDER BY prompts.created_at DESC");
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query("SELECT prompts.*, users.username, categories.name AS category_name FROM prompts JOIN users ON prompts.user_id = users.id JOIN categories ON prompts.category_id = categories.id ORDER BY prompts.created_at DESC");
}

$prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque — Prompt Repository</title>
    <?php include 'includes/styles.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">

        <?php if ($deleted): ?>
            <div class="alert border-0 rounded-3 py-2 mb-4 alert-dismissible fade show" style="background:#f0fdf4;color:#166534;">
                <i class="bi bi-check-circle me-1"></i> Prompt supprimé.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div class="page-header mb-0">
                <h1 class="page-title">Bibliothèque</h1>
                <p class="page-subtitle mb-0"><?= count($prompts) ?> prompt<?= count($prompts) > 1 ? 's' : '' ?> disponible<?= count($prompts) > 1 ? 's' : '' ?></p>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <form method="GET">
                    <select name="category" onchange="this.form.submit()" class="form-input" style="padding:8px 16px;width:auto;font-size:0.85rem;">
                        <option value="">Toutes catégories</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($filter == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a href="create_prompt.php" class="btn btn-primary-custom btn-sm" style="white-space:nowrap;">
                    <i class="bi bi-plus me-1"></i>Nouveau
                </a>
            </div>
        </div>

        <?php if (empty($prompts)): ?>
            <div class="card-custom">
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-3 text-muted"></i>
                    <h5 class="text-muted mt-3 fw-semibold">Aucun prompt trouvé</h5>
                    <a href="create_prompt.php" class="btn btn-primary-custom btn-sm mt-2">Créer un prompt</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($prompts as $p): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="view_prompt.php?id=<?= $p['id'] ?>" class="prompt-card h-100">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="category-badge"><?= htmlspecialchars($p['category_name']) ?></span>
                                <small class="text-muted" style="font-size:0.75rem;"><?= date('d M', strtotime($p['created_at'])) ?></small>
                            </div>
                            
                            <h6 class="prompt-title"><?= htmlspecialchars($p['title']) ?></h6>
                            <p class="prompt-excerpt mb-3"><?= htmlspecialchars(substr($p['content'], 0, 100)) ?>...</p>
                            
                            <div class="d-flex align-items-center gap-2 mt-auto pt-3" style="border-top:1px solid #f1f5f9;">
                                <div class="avatar-xs"><?= strtoupper(substr($p['username'], 0, 1)) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($p['username']) ?></small>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>