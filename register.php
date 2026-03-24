<?php
require_once 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
  
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est obligatoire.";
    }
    
    if (empty($email)) {
        $errors[] = "L'email est obligatoire.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    
    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
  
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        }
    }
  
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$username, $email, $hashed_password]);
    $success = "✅ Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
    }

    if (password_verify($password_saisi, $password_en_base)) {
    echo "✅ Mot de passe correct !";
} else {
    echo "❌ Mot de passe incorrect !";
}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Prompt Repository</title>
    <style>
        .error { color: red; background: #ffe6e6; padding: 10px; margin: 10px 0; }
        .success { color: green; background: #e6ffe6; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>📝 Créer un compte</h1>
    
    <?php
    if (!empty($errors)) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo "❌ $error<br>";
        }
        echo '</div>';
    }
    ?>
    
    <form method="POST" action="">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        <br><br>
        
        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <br><br>
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <br><br>
        
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>