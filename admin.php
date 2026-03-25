<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }

$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_prompts = $pdo->query("SELECT COUNT(*) FROM prompts")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

$top = $pdo->query("SELECT users.*, COUNT(prompts.id) AS pc FROM users LEFT JOIN prompts ON users.id = prompts.user_id GROUP BY users.id ORDER BY pc DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$users = $pdo->query("SELECT users.*, COUNT(prompts.id) AS pc FROM users LEFT JOIN prompts ON users.id = prompts.user_id GROUP BY users.id ORDER BY users.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$cats = $pdo->query("SELECT categories.*, COUNT(prompts.id) AS pc FROM categories LEFT JOIN prompts ON categories.id = prompts.category_id GROUP BY categories.id ORDER BY categories.name")->fetchAll(PDO::FETCH_ASSOC);

$recent = $pdo->query("SELECT prompts.*, users.username, categories.name AS cn FROM prompts JOIN users ON prompts.user_id = users.id JOIN categories ON prompts.category_id = categories.id ORDER BY prompts.created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Prompt Repository</title>
    <?php include 'includes/styles.php'; ?>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        
        <div class="page-header">
            <h1 class="page-title">Panel administrateur</h1>
            <p class="page-subtitle">Vue d'ensemble de la plateforme.</p>
        </div>

        <?php if ($success): ?>
            <div class="alert border-0 rounded-3 py-2 mb-4 alert-dismissible fade show" style="background:#f0fdf4;color:#166534;">
                ✅ <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert border-0 rounded-3 py-2 mb-4 alert-dismissible fade show" style="background:#fef2f2;color:#991b1b;">
                ⚠️ <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon-box bg-primary bg-opacity-10 text-primary mb-3"><i class="bi bi-people"></i></div>
                    <div class="stat-value"><?= $total_users ?></div>
                    <div class="stat-label">Utilisateurs</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon-box mb-3" style="background:#ecfdf5;color:#059669;"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="stat-value"><?= $total_prompts ?></div>
                    <div class="stat-label">Prompts</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon-box mb-3" style="background:#fef3c7;color:#d97706;"><i class="bi bi-tags"></i></div>
                    <div class="stat-value"><?= $total_categories ?></div>
                    <div class="stat-label">Catégories</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Top Contributors -->
            <div class="col-lg-6">
                <div class="card-custom">
                    <div class="card-header-custom"><h5>🏆 Top contributeurs</h5></div>
                    <?php foreach ($top as $i => $u): ?>
                        <div class="list-item">
                            <div class="d-flex align-items-center gap-3">
                                <span style="font-size:1.2rem;width:24px;text-align:center;">
                                    <?= $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '<span class="text-muted small fw-bold">'.($i+1).'</span>')) ?>
                                </span>
                                <div>
                                    <span class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($u['username']) ?></span>
                                    <br><small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                </div>
                            </div>
                            <span class="category-badge"><?= $u['pc'] ?> prompts</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Categories -->
            <div class="col-lg-6">
                <div class="card-custom">
                    <div class="card-header-custom">
                        <h5>🏷️ Catégories</h5>
                        <a href="add_category.php" class="btn btn-primary-custom btn-sm"><i class="bi bi-plus me-1"></i>Ajouter</a>
                    </div>
                    <?php foreach ($cats as $c): ?>
                        <div class="list-item">
                            <div>
                                <span class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($c['name']) ?></span>
                                <br><small class="text-muted"><?= $c['pc'] ?> prompts</small>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="edit_category.php?id=<?= $c['id'] ?>" class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:8px;"><i class="bi bi-pencil"></i></a>
                                <?php if ($c['pc'] == 0): ?>
                                    <a href="delete_category.php?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ?')" class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border-radius:8px;"><i class="bi bi-trash"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card-custom mb-4">
            <div class="card-header-custom"><h5>👥 Utilisateurs</h5></div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr><th>Utilisateur</th><th>Rôle</th><th>Prompts</th><th>Inscrit le</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-xs" style="width:32px;height:32px;border-radius:8px;font-size:12px;"><?= strtoupper(substr($u['username'], 0, 1)) ?></div>
                                        <div>
                                            <span class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($u['username']) ?></span>
                                            <br><small class="text-muted"><?= htmlspecialchars($u['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="role-badge <?= $u['role'] === 'admin' ? 'role-admin' : 'role-dev' ?>"><?= $u['role'] === 'admin' ? 'Admin' : 'Developer' ?></span></td>
                                <td class="fw-semibold"><?= $u['pc'] ?></td>
                                <td class="text-muted"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent -->
        <div class="card-custom">
            <div class="card-header-custom"><h5>📋 Activité récente</h5></div>
            <?php foreach ($recent as $p): ?>
                <a href="view_prompt.php?id=<?= $p['id'] ?>" class="list-item text-decoration-none">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="category-badge"><?= htmlspecialchars($p['cn']) ?></span>
                            <small class="text-muted">par <?= htmlspecialchars($p['username']) ?></small>
                        </div>
                        <span class="fw-semibold" style="font-size:0.9rem;color:#0f172a;"><?= htmlspecialchars($p['title']) ?></span>
                    </div>
                    <small class="text-muted"><?= date('d/m H:i', strtotime($p['created_at'])) ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>