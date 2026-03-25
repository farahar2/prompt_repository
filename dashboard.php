<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM prompts WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$my_stats = $stmt->fetch(PDO::FETCH_ASSOC);

$total_prompts = $pdo->query("SELECT COUNT(*) FROM prompts")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$stmt = $pdo->prepare("
    SELECT prompts.*, categories.name AS category_name
    FROM prompts JOIN categories ON prompts.category_id = categories.id
    WHERE prompts.user_id = ?
    ORDER BY prompts.created_at DESC LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Prompt Repository</title>
    <?php include 'includes/styles.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header">
            <h1 class="page-title">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
            <p class="page-subtitle">Voici un aperçu de votre activité.</p>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box bg-primary bg-opacity-10 text-primary mb-3">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="stat-value"><?= $my_stats['total'] ?></div>
                    <div class="stat-label">Mes prompts</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box" style="background:#ecfdf5;color:#059669;">
                        <i class="bi bi-archive"></i>
                    </div>
                    <div class="stat-value"><?= $total_prompts ?></div>
                    <div class="stat-label">Total prompts</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box" style="background:#fef3c7;color:#d97706;">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div class="stat-value"><?= $total_categories ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box" style="background:#fae8ff;color:#a855f7;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-value"><?= $total_users ?></div>
                    <div class="stat-label">Utilisateurs</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <a href="create_prompt.php" class="prompt-card d-flex align-items-center gap-4">
                    <div class="stat-icon-box" style="background:#eef2ff;color:#6366f1;width:52px;height:52px;flex-shrink:0;">
                        <i class="bi bi-plus-lg fs-4"></i>
                    </div>
                    <div>
                        <div class="prompt-title mb-0">Nouveau prompt</div>
                        <div class="prompt-excerpt mb-0">Ajouter un prompt testé et approuvé</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="prompts.php" class="prompt-card d-flex align-items-center gap-4">
                    <div class="stat-icon-box" style="background:#ecfdf5;color:#059669;width:52px;height:52px;flex-shrink:0;">
                        <i class="bi bi-search fs-4"></i>
                    </div>
                    <div>
                        <div class="prompt-title mb-0">Explorer</div>
                        <div class="prompt-excerpt mb-0">Parcourir la bibliothèque</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent -->
        <div class="card-custom">
            <div class="card-header-custom">
                <h5>Prompts récents</h5>
                <a href="prompts.php" class="text-decoration-none small fw-semibold" style="color:#6366f1;">Voir tout →</a>
            </div>
            
            <?php if (empty($recent)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <p class="text-muted mt-2">Aucun prompt créé</p>
                    <a href="create_prompt.php" class="btn btn-primary-custom btn-sm">Créer mon premier prompt</a>
                </div>
            <?php else: ?>
                <?php foreach ($recent as $p): ?>
                    <a href="view_prompt.php?id=<?= $p['id'] ?>" class="list-item text-decoration-none">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="category-badge"><?= htmlspecialchars($p['category_name']) ?></span>
                                <small class="text-muted"><?= date('d M Y', strtotime($p['created_at'])) ?></small>
                            </div>
                            <div class="prompt-title mb-0" style="font-size:0.9rem;"><?= htmlspecialchars($p['title']) ?></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>