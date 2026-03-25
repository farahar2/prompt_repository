<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$errors = [];
$success = '';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];
    
    if (empty($title)) $errors[] = "Le titre est obligatoire.";
    if (empty($content)) $errors[] = "Le contenu est obligatoire.";
    if (empty($category_id)) $errors[] = "Choisissez une catégorie.";
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO prompts (title, content, user_id, category_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $_SESSION['user_id'], $category_id]);
        $success = "Prompt créé avec succès !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau prompt - Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { box-shadow: 0 2px 15px rgba(0,0,0,0.08); }
        .card { border: none; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none; color: white; border-radius: 10px;
            padding: 12px 20px; font-weight: 600; transition: all 0.3s;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .avatar {
            width: 35px; height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 14px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-book-half text-primary me-2"></i>Prompt Repository</a>
            <div class="d-flex align-items-center gap-2">
                <a href="dashboard.php" class="btn btn-light btn-sm">Dashboard</a>
                <a href="prompts.php" class="btn btn-light btn-sm">Bibliothèque</a>
                <div class="avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                
                <a href="dashboard.php" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-3">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                
                <h2 class="fw-bold mb-1">Nouveau prompt</h2>
                <p class="text-muted mb-4">Partagez un prompt testé et approuvé avec l'équipe.</p>

                <div class="card p-4">
                    <div class="card-body">
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger py-2">
                                <?php foreach ($errors as $error): ?>
                                    <small class="d-block"><i class="bi bi-exclamation-circle me-1"></i><?= $error ?></small>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success py-2">
                                <small><i class="bi bi-check-circle me-1"></i><?= $success ?></small>
                                <br><a href="prompts.php" class="small">Voir les prompts →</a>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Titre</label>
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" placeholder="Ex: Générer un CRUD en PHP" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Catégorie</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Choisir une catégorie</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Contenu du prompt</label>
                                <textarea name="content" rows="10" class="form-control font-monospace" placeholder="Écrivez votre prompt ici..." required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-gradient w-100">
                                <i class="bi bi-save me-1"></i> Enregistrer le prompt
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>