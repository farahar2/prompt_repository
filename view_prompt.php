<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: prompts.php'); exit; }

$stmt = $pdo->prepare("SELECT prompts.*, users.username, categories.name AS category_name FROM prompts JOIN users ON prompts.user_id = users.id JOIN categories ON prompts.category_id = categories.id WHERE prompts.id = ?");
$stmt->execute([$id]);
$prompt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prompt) { header('Location: prompts.php'); exit; }

$is_author = ($prompt['user_id'] == $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($prompt['title']) ?></title>
    <?php include 'includes/styles.php'; ?>
    <style>
        .prompt-content-box {
            background: #0f172a;
            border-radius: 12px;
            padding: 24px;
            color: #e2e8f0;
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.85rem;
            line-height: 1.8;
            white-space: pre-wrap;
            position: relative;
        }
        .copy-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: #94a3b8;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .copy-btn:hover { background: rgba(255,255,255,0.15); color: white; }
        .copy-btn.copied { background: rgba(34,197,94,0.2); color: #4ade80; border-color: rgba(34,197,94,0.3); }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        
        <a href="prompts.php" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-4">
            <i class="bi bi-arrow-left"></i> Bibliothèque
        </a>

        <div class="row">
            <div class="col-lg-8">
                
                <!-- Header -->
                <div class="card-custom mb-4">
                    <div class="card-body-custom">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="category-badge"><?= htmlspecialchars($prompt['category_name']) ?></span>
                            <small class="text-muted"><?= date('d M Y à H:i', strtotime($prompt['created_at'])) ?></small>
                        </div>
                        
                        <h1 style="font-weight:800;font-size:1.6rem;color:#0f172a;margin-bottom:16px;">
                            <?= htmlspecialchars($prompt['title']) ?>
                        </h1>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar" style="width:40px;height:40px;border-radius:12px;">
                                <?= strtoupper(substr($prompt['username'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;"><?= htmlspecialchars($prompt['username']) ?></div>
                                <div class="text-muted" style="font-size:0.8rem;">Contributeur</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="card-custom mb-4">
                    <div class="card-header-custom">
                        <h5>Contenu du prompt</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="prompt-content-box">
                            <button class="copy-btn" id="copyBtn" onclick="copyPrompt()">
                                <i class="bi bi-clipboard me-1"></i><span id="copyText">Copier</span>
                            </button>
                            <div id="promptContent"><?= htmlspecialchars($prompt['content']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <?php if ($is_author): ?>
                    <div class="d-flex gap-3">
                        <a href="edit_prompt.php?id=<?= $prompt['id'] ?>" class="btn btn-secondary-custom flex-fill text-center">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </a>
                        <a href="delete_prompt.php?id=<?= $prompt['id'] ?>" onclick="return confirm('Supprimer ce prompt ?')" class="btn btn-secondary-custom flex-fill text-center" style="color:#dc2626;border-color:#fecaca;">
                            <i class="bi bi-trash me-1"></i> Supprimer
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <h6 class="fw-bold mb-3" style="font-size:0.85rem;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Détails</h6>
                        
                        <div class="mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                            <small class="text-muted d-block mb-1">Catégorie</small>
                            <span class="category-badge"><?= htmlspecialchars($prompt['category_name']) ?></span>
                        </div>
                        
                        <div class="mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                            <small class="text-muted d-block mb-1">Auteur</small>
                            <span class="fw-semibold" style="font-size:0.9rem;"><?= htmlspecialchars($prompt['username']) ?></span>
                        </div>
                        
                        <div class="mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                            <small class="text-muted d-block mb-1">Créé le</small>
                            <span style="font-size:0.9rem;"><?= date('d M Y', strtotime($prompt['created_at'])) ?></span>
                        </div>
                        
                        <div>
                            <small class="text-muted d-block mb-1">Taille</small>
                            <span style="font-size:0.9rem;"><?= strlen($prompt['content']) ?> caractères</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyPrompt() {
            const text = document.getElementById('promptContent').textContent;
            const btn = document.getElementById('copyBtn');
            const btnText = document.getElementById('copyText');
            
            navigator.clipboard.writeText(text).then(() => {
                btn.classList.add('copied');
                btnText.textContent = 'Copié !';
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btnText.textContent = 'Copier';
                }, 2000);
            });
        }
    </script>
</body>
</html>