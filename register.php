<?php
require_once 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
  
    if (empty($username)) $errors[] = "Le nom d'utilisateur est obligatoire.";
    if (empty($email)) $errors[] = "L'email est obligatoire.";
    if (empty($password)) $errors[] = "Le mot de passe est obligatoire.";
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide.";
    if (!empty($password) && strlen($password) < 6) $errors[] = "Min. 6 caractères pour le mot de passe.";
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = "Cet email est déjà utilisé.";
    }
    
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed]);
        $success = "Compte créé avec succès !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — Prompt Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { min-height: 100vh; }
        
        .auth-left {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
        }
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(168,85,247,0.1) 0%, transparent 70%);
        }
        .auth-left .content { position: relative; z-index: 1; }
        .auth-left h1 { 
            font-size: 2.5rem; 
            font-weight: 800; 
            color: white; 
            line-height: 1.2;
        }
        .auth-left p { color: #94a3b8; font-size: 1.1rem; }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            color: #cbd5e1;
        }
        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #818cf8;
            flex-shrink: 0;
        }
        
        .auth-right {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #ffffff;
        }
        .auth-form { max-width: 400px; width: 100%; }
        .auth-form h2 { font-weight: 800; font-size: 1.8rem; color: #0f172a; }
        .auth-form p { color: #64748b; }
        
        .form-floating > .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            height: 56px;
            font-size: 0.95rem;
            background: #f8fafc;
            transition: all 0.2s;
        }
        .form-floating > .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
            background: #fff;
        }
        .form-floating > label { padding: 16px; color: #94a3b8; }
        
        .btn-auth {
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .btn-auth:hover {
            background: #1e293b;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,23,42,0.3);
        }
        
        .logo-badge {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        @media (max-width: 991px) {
            .auth-left { display: none; }
        }
    </style>
</head>
<body>
    <div class="row g-0">
        
        <!-- Left Panel -->
        <div class="col-lg-6 auth-left d-none d-lg-flex">
            <div class="content">
                <div class="logo-badge mb-4">
                    <i class="bi bi-braces-asterisk"></i>
                </div>
                <h1 class="mb-3">Sauvegardez vos<br>meilleurs prompts.</h1>
                <p class="mb-5">Une plateforme interne pour stocker, catégoriser<br>et réutiliser vos instructions IA performantes.</p>
                
                <div class="feature-item">
                    <div class="feature-icon"><i class="bi bi-archive"></i></div>
                    <span>Stockez vos prompts testés et approuvés</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="bi bi-tags"></i></div>
                    <span>Catégorisez par thématique</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="bi bi-people"></i></div>
                    <span>Partagez avec votre équipe</span>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="col-lg-6 auth-right">
            <div class="auth-form">
                
                <div class="d-lg-none mb-4">
                    <div class="logo-badge"><i class="bi bi-braces-asterisk"></i></div>
                </div>
                
                <h2>Créer un compte</h2>
                <p class="mb-4">Rejoignez Prompt Repository gratuitement.</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger border-0 rounded-3 py-2" style="background:#fef2f2;color:#991b1b;">
                        <?php foreach ($errors as $error): ?>
                            <small class="d-block"><i class="bi bi-x-circle me-1"></i><?= $error ?></small>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert border-0 rounded-3 py-2" style="background:#f0fdf4;color:#166534;">
                        <small><i class="bi bi-check-circle me-1"></i><?= $success ?></small>
                        <br><a href="login.php" class="small fw-semibold" style="color:#166534;">Se connecter →</a>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="username" placeholder="Nom" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                        <label for="username">Nom d'utilisateur</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        <label for="email">Adresse email</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
                        <label for="password">Mot de passe</label>
                    </div>

                    <button type="submit" class="btn btn-auth w-100 mb-3">
                        Créer mon compte <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                <p class="text-center text-muted small mt-4">
                    Déjà un compte ? <a href="login.php" class="fw-semibold text-decoration-none" style="color:#6366f1;">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>