<?php
// Déterminer la page active
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside class="sidebar d-flex flex-column" id="sidebar">
    
    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="bi bi-braces-asterisk"></i></div>
        <span class="logo-text">PromptRepo</span>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav flex-grow-1">
        <div class="nav-section-title">Menu</div>
        
        <a href="dashboard.php" class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="prompts.php" class="nav-item <?= $current_page === 'prompts.php' ? 'active' : '' ?>">
            <i class="bi bi-collection"></i>
            <span>Bibliothèque</span>
        </a>
        
        <a href="create_prompt.php" class="nav-item <?= $current_page === 'create_prompt.php' ? 'active' : '' ?>">
            <i class="bi bi-plus-square"></i>
            <span>Nouveau Prompt</span>
        </a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="nav-section-title mt-4">Administration</div>
            
            <a href="admin.php" class="nav-item <?= $current_page === 'admin.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Panel Admin</span>
            </a>
            
            <a href="add_category.php" class="nav-item <?= $current_page === 'add_category.php' ? 'active' : '' ?>">
                <i class="bi bi-tag"></i>
                <span>Catégories</span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- User -->
    <div class="sidebar-user">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['username'], 0, 1)) ?></div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
                <div class="user-role"><?= $_SESSION['role'] === 'admin' ? 'Administrateur' : 'Développeur' ?></div>
            </div>
            <a href="logout.php" class="logout-btn" title="Déconnexion">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Toggle -->
<button class="sidebar-toggle d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
    <i class="bi bi-list"></i>
</button>