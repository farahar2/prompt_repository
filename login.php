<?php
session_start();
require_once 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email)) $errors[] = "L'email est obligatoire.";
    if (empty($password)) $errors[] = "Le mot de passe est obligatoire.";
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ' . ($user['role'] === 'admin' ? 'admin.php' : 'dashboard.php'));
            exit;
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Prompt Repository</title>
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
            top: -50%; right: -50%;
            width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
        }
        .auth-left .content { position: relative; z-index: 1; }
        .auth-left h1 { font-size: 2.5rem; font-weight: 800; color: white; line-height: 1.2; }
        .auth-left p { color: #94a3b8; font-size: 1.1rem; }
        
        .testimonial-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 24px;
            margin-top: 40px;
        }
        .testimonial-card p { color: #e2e8f0; font-style: italic; font-size: 0.95rem; }
        .testimonial-card .author { color: #94a3b8; font-size: 0.85rem; }
        .testimonial-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 14px;
        }
        
        .auth-right {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 40px; background: #ffffff;
        }
        .auth-form { max-width: 400px; width: 100%; }
        .auth-form h2 { font-weight: 800; font-size: 1.8rem; color: #0f172a; }
        
        .form-floating > .form-control {
            border: 2px solid #e2e8f0; border-radius: 12px;
            padding: 16px; height: 56px; font-size: 0.95rem;
            background: #f8fafc; transition: all 0.2s;
        }
        .form-floating > .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
            background: #fff;
        }
        .form-floating > label { padding: 16px; color: #94a3b8; }
        
        .btn-auth {
            background: #0f172a; color: white; border: none;
            border-radius: 12px; padding: 14px; font-weight: 700;
            font-size: 0.95rem; transition: all 0.2s;
        }
        .btn-auth:hover {
            background: #1e293b; color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15,23,42,0.3);
        }
        .logo-badge {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.2rem;
        }

        @media (max-width: 991px) { .auth-left { display: none; } }
    </style>
</head>
<body>
    <div class="row g-0">
        
        <div class="col-lg-6 auth-left d-none d-lg-flex">
            <div class="content">
                <div class="logo-badge mb-4"><i class="bi bi-braces-asterisk"></i></div>
                <h1 class="mb-3">Ne perdez plus<br>jamais un prompt.</h1>
                <p>Retrouvez instantanément vos meilleures<br>instructions IA testées et approuvées.</p>
                
                <div class="testimonial-card">
                    <p>"Depuis qu'on utilise Prompt Repository, on a réduit notre temps de recherche de prompts de 80%. Un game changer."</p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div class="testimonial-avatar">A</div>
                        <div>
                            <div class="author fw-semibold" style="color:#e2e8f0;">Ahmed K.</div>
                            <div class="author">Lead Developer, DevGenius</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 auth-right">
            <div class="auth-form">
                <div class="d-lg-none mb-4">
                    <div class="logo-badge"><i class="bi bi-braces-asterisk"></i></div>
                </div>
                
                <h2>Bon retour !</h2>
                <p class="text-muted mb-4">Connectez-vous à votre espace.</p>

                <?php if (!empty($errors)): ?>
                    <div class="alert border-0 rounded-3 py-2" style="background:#fef2f2;color:#991b1b;">
                        <?php foreach ($errors as $error): ?>
                            <small class="d-block"><i class="bi bi-x-circle me-1"></i><?= $error ?></small>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        <label for="email">Adresse email</label>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
                        <label for="password">Mot de passe</label>
                    </div>

                    <button type="submit" class="btn btn-auth w-100 mb-3">
                        Se connecter <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                <p class="text-center text-muted small mt-4">
                    Pas de compte ? <a href="register.php" class="fw-semibold text-decoration-none" style="color:#6366f1;">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>