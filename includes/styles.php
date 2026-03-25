<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Inter', sans-serif; }
    body { background: #f1f5f9; min-height: 100vh; }
    
    /* ========== SIDEBAR ========== */
    .sidebar {
        width: 260px;
        height: 100vh;
        background: #0f172a;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        padding: 0;
        transition: transform 0.3s;
    }
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .logo-icon {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1rem;
    }
    .logo-text {
        color: white;
        font-weight: 800;
        font-size: 1.1rem;
        letter-spacing: -0.5px;
    }
    
    .sidebar-nav { padding: 16px 12px; overflow-y: auto; }
    .nav-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #475569;
        padding: 8px 12px;
        margin-top: 8px;
    }
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        margin-bottom: 2px;
    }
    .nav-item:hover {
        background: rgba(255,255,255,0.06);
        color: #e2e8f0;
    }
    .nav-item.active {
        background: rgba(99,102,241,0.15);
        color: #818cf8;
    }
    .nav-item i { font-size: 1.1rem; width: 20px; text-align: center; }
    
    .sidebar-user {
        padding: 16px 20px;
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .user-avatar {
        width: 36px; height: 36px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 14px;
        flex-shrink: 0;
    }
    .user-name { color: #e2e8f0; font-weight: 600; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-role { color: #64748b; font-size: 0.75rem; }
    .logout-btn {
        color: #64748b;
        text-decoration: none;
        padding: 6px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .logout-btn:hover { color: #ef4444; background: rgba(239,68,68,0.1); }
    
    .sidebar-toggle {
        position: fixed;
        top: 16px; left: 16px;
        z-index: 1001;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 10px;
        width: 44px; height: 44px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* ========== MAIN CONTENT ========== */
    .main-content {
        margin-left: 260px;
        padding: 32px 40px;
        min-height: 100vh;
    }
    
    .page-header { margin-bottom: 32px; }
    .page-title { font-weight: 800; font-size: 1.8rem; color: #0f172a; margin-bottom: 4px; }
    .page-subtitle { color: #64748b; font-size: 0.95rem; }
    
    /* ========== CARDS ========== */
    .card-custom {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.2s;
    }
    .card-custom:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
    .card-header-custom {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header-custom h5 { font-weight: 700; font-size: 1rem; color: #0f172a; margin: 0; }
    .card-body-custom { padding: 24px; }
    
    /* ========== STAT CARDS ========== */
    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        border-color: #c7d2fe;
    }
    .stat-icon-box {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }
    .stat-value { font-size: 2rem; font-weight: 800; color: #0f172a; }
    .stat-label { color: #64748b; font-size: 0.85rem; font-weight: 500; }
    
    /* ========== PROMPT CARDS ========== */
    .prompt-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .prompt-card:hover {
        border-color: #6366f1;
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(99,102,241,0.08);
        color: inherit;
    }
    .prompt-card:hover .prompt-title { color: #6366f1; }
    .prompt-title {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
        transition: color 0.2s;
        margin-bottom: 8px;
    }
    .prompt-excerpt { color: #64748b; font-size: 0.85rem; line-height: 1.5; }
    
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #eef2ff;
        color: #4f46e5;
    }
    .avatar-xs {
        width: 24px; height: 24px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 10px;
    }
    
    /* ========== FORMS ========== */
    .form-input {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.9rem;
        background: #f8fafc;
        transition: all 0.2s;
        width: 100%;
    }
    .form-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        background: white;
    }
    textarea.form-input { resize: vertical; min-height: 200px; }
    select.form-input { cursor: pointer; }
    
    .btn-primary-custom {
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-primary-custom:hover {
        background: #1e293b;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,23,42,0.2);
    }
    .btn-secondary-custom {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-secondary-custom:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    /* ========== TABLE ========== */
    .table-custom th {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 12px 20px;
        background: #f8fafc;
        border: none;
    }
    .table-custom td {
        padding: 16px 20px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 0.9rem;
    }
    .table-custom tr:hover { background: #f8fafc; }
    
    /* ========== LIST ITEMS ========== */
    .list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .list-item:last-child { border-bottom: none; }
    .list-item:hover { background: #f8fafc; }
    
    /* ========== BADGES ========== */
    .role-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .role-admin { background: #fef2f2; color: #991b1b; }
    .role-dev { background: #eef2ff; color: #4338ca; }
    
    /* ========== RESPONSIVE ========== */
    @media (max-width: 991px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.show { transform: translateX(0); }
        .main-content { margin-left: 0; padding: 24px 16px; padding-top: 70px; }
    }
</style>