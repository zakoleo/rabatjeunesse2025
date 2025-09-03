<?php
/**
 * Admin Layout with Sidebar
 */

$cakeDescription = 'Administration - Rabat Jeunesse';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="<?= $this->request->getAttribute('csrfToken') ?>">

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'app', 'admin-dashboard', 'admin-unified']) ?>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            overflow-x: hidden;
            font-size: 16px;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid #334155;
            text-align: center;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .sidebar-logo i {
            font-size: 2rem;
            color: #3b82f6;
        }

        .sidebar-logo h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }

        .sidebar-subtitle {
            font-size: 1.1rem;
            color: #94a3b8;
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.05em;
        }

        .nav-items {
            list-style: none;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-left-color: #3b82f6;
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border-left-color: #3b82f6;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-badge {
            margin-left: auto;
            background: #dc2626;
            color: white;
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .nav-badge.warning {
            background: #f59e0b;
        }

        .nav-badge.success {
            background: #10b981;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            background: #f8fafc;
        }

        .admin-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-left h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .header-left .breadcrumb {
            color: #64748b;
            font-size: 1.1rem;
            margin-top: 0.25rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-menu:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
        }

        .user-role {
            font-size: 1rem;
            color: #64748b;
        }

        .admin-content {
            padding: 2rem;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }
        }

        .mobile-toggle {
            display: none;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Utilities */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1.1rem;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-shield-alt"></i>
                    <h1>Admin</h1>
                </div>
                <div class="sidebar-subtitle">Rabat Jeunesse 2025</div>
            </div>

<?php 
            // Helper function to determine if a nav link is active
            $isActive = function($controller, $action, $queryParams = []) {
                $currentController = $this->request->getParam('controller');
                $currentAction = $this->request->getParam('action');
                $currentQuery = $this->request->getQueryParams();
                
                // Check controller and action match
                if ($currentController !== $controller || $currentAction !== $action) {
                    return false;
                }
                
                // If no query params to check, just controller/action match is enough
                if (empty($queryParams)) {
                    return empty($currentQuery) || (count($currentQuery) === 1 && isset($currentQuery['_Token']));
                }
                
                // Check if all required query params match
                foreach ($queryParams as $key => $value) {
                    if (!isset($currentQuery[$key]) || $currentQuery[$key] !== $value) {
                        return false;
                    }
                }
                
                return true;
            };
            ?>
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-section-title">Tableau de bord</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-home"></i> Vue d\'ensemble', 
                                ['controller' => 'Admin', 'action' => 'index'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'index') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Teams Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Gestion des équipes</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Toutes les équipes', 
                                ['controller' => 'Admin', 'action' => 'teams'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-futbol"></i> Football', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'football']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'football']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-basketball-ball"></i> Basketball', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'basketball']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'basketball']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-handball"></i> Handball', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'handball']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'handball']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-volleyball-ball"></i> Volleyball', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'volleyball']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'volleyball']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-umbrella-beach"></i> Beach Volleyball', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'beachvolley']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'beachvolley']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Status Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Validation</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-clock"></i> En attente <span class="nav-badge warning">3</span>', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['status' => 'pending']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['status' => 'pending']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-check-circle"></i> Vérifiées', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['status' => 'verified']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['status' => 'verified']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-times-circle"></i> Rejetées', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['status' => 'rejected']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['status' => 'rejected']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- User Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Utilisateurs</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-user-friends"></i> Tous les utilisateurs', 
                                ['controller' => 'Admin', 'action' => 'users'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'users') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-user-plus"></i> Nouvel utilisateur', 
                                ['controller' => 'Admin', 'action' => 'addUser'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'addUser') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Tools -->
                <div class="nav-section">
                    <div class="nav-section-title">Outils</div>
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-download"></i> Export données', 
                                ['controller' => 'Admin', 'action' => 'export'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'export') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-cog"></i> Paramètres', 
                                ['controller' => 'Admin', 'action' => 'settings'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'settings') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-sign-out-alt"></i> Retour au site', 
                                ['controller' => 'Sports', 'action' => 'index'], 
                                ['class' => 'nav-link', 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-left">
                    <h1><?= $this->fetch('title') ?: 'Administration' ?></h1>
                    <div class="breadcrumb">
                        <?= $this->Html->link('Admin', ['controller' => 'Admin', 'action' => 'index']) ?> 
                        <?php if ($this->request->getParam('action') !== 'index'): ?>
                            / <?= ucfirst($this->request->getParam('action')) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="header-actions">
                    <div class="user-menu">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <div class="user-name">Administrateur</div>
                            <div class="user-role">Super Admin</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>

    <?= $this->fetch('script') ?>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        // Auto-close sidebar on mobile when window is resized
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('adminSidebar');
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>