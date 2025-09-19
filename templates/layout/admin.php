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
    <?= $this->Html->meta('icon', $this->Url->image('logo.webp')) ?>
    <link rel="apple-touch-icon" href="<?= $this->Url->image('logo.webp') ?>">
    <link rel="icon" type="image/webp" href="<?= $this->Url->image('logo.webp') ?>">
    
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
            width: 220px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
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

        .nav-section.primary-section {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.05));
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .nav-section.primary-section .nav-section-title {
            color: #3b82f6 !important;
            font-weight: 700;
            font-size: 1rem;
        }

        .collapsible-header {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .collapsible-header:hover {
            color: #3b82f6;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .toggle-icon.rotated {
            transform: rotate(90deg);
        }

        .nav-section-title {
            padding: 0 1rem 0.25rem;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.05em;
            margin-top: 1rem;
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
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 12px;
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
            margin-left: 220px;
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

        /* Sport Configuration Menu Styles */
        .sport-config-section {
            margin-bottom: 1rem;
        }

        .sport-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            color: #cbd5e1;
        }

        .sport-menu-header:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .sport-menu-header .sport-icon {
            width: 20px;
            text-align: center;
            font-size: 12px;
            margin-right: 0.75rem;
        }

        .sport-menu-header .sport-name {
            flex: 1;
            font-weight: 600;
            font-size: 12px;
        }

        .sport-menu-header .toggle-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
            color: #64748b;
        }

        .sport-menu-header.expanded .toggle-arrow {
            transform: rotate(180deg);
        }

        .sport-submenu {
            list-style: none;
            padding-left: 0;
            margin: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            max-height: 0;
            opacity: 0;
        }

        .sport-submenu.expanded {
            max-height: 200px;
            opacity: 1;
            margin-bottom: 0.5rem;
        }

        .sport-submenu .nav-link {
            padding: 0.4rem 1rem 0.4rem 2.5rem;
            font-size: 12px;
            color: #94a3b8;
            border-left: 3px solid transparent;
        }

        .sport-submenu .nav-link:hover {
            background: rgba(59, 130, 246, 0.08);
            color: #3b82f6;
            border-left-color: rgba(59, 130, 246, 0.3);
        }

        .sport-submenu .nav-link.active {
            background: rgba(59, 130, 246, 0.12);
            color: #3b82f6;
            border-left-color: #3b82f6;
        }

        .sport-submenu .nav-link i {
            width: 16px;
            font-size: 0.9rem;
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
                    <?= $this->Html->image('logo.webp', ['alt' => 'Rabat Jeunesse Logo', 'style' => 'height: 50px; width: auto;']) ?>
                </div>
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
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-home"></i> Dashboard', 
                                ['controller' => 'Admin', 'action' => 'index'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'index') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- ÉQUIPES PAR SPORT -->
                <div class="nav-section-title">🏆 Équipes par Sport</div>
                
                <!-- All Sports Teams -->
                <div class="nav-section">
                    <ul class="nav-items">
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
                            <?= $this->Html->link('<i class="fas fa-hand-paper"></i> Handball', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'handball']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'handball']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-volleyball-ball"></i> Volleyball', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'volleyball']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'volleyball']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-umbrella-beach"></i> Beach Volley', 
                                ['controller' => 'Admin', 'action' => 'teams', '?' => ['sport' => 'beachvolley']], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams', ['sport' => 'beachvolley']) ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Toutes équipes', 
                                ['controller' => 'Admin', 'action' => 'teams'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'teams') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- SPORTS CONFIGURATION -->
                <div class="nav-section-title">⚙️ Configuration Sports</div>
                
                <!-- Football Configuration -->
                <div class="nav-section sport-config-section">
                    <div class="sport-menu-header" onclick="toggleSportMenu('football')">
                        <i class="fas fa-futbol sport-icon"></i>
                        <span class="sport-name">Football</span>
                        <i class="fas fa-chevron-down toggle-arrow" id="football-arrow"></i>
                    </div>
                    <ul class="sport-submenu" id="football-menu">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Catégories', 
                                ['controller' => 'FootballManagement', 'action' => 'categories'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('FootballManagement', 'categories') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-map"></i> Types', 
                                ['controller' => 'FootballManagement', 'action' => 'types'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('FootballManagement', 'types') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-link"></i> Relations', 
                                ['controller' => 'FootballManagement', 'action' => 'relationships'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('FootballManagement', 'relationships') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Basketball Configuration -->
                <div class="nav-section sport-config-section">
                    <div class="sport-menu-header" onclick="toggleSportMenu('basketball')">
                        <i class="fas fa-basketball-ball sport-icon"></i>
                        <span class="sport-name">Basketball</span>
                        <i class="fas fa-chevron-down toggle-arrow" id="basketball-arrow"></i>
                    </div>
                    <ul class="sport-submenu" id="basketball-menu">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Catégories', 
                                ['controller' => 'BasketballManagement', 'action' => 'categories'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('BasketballManagement', 'categories') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-map"></i> Types', 
                                ['controller' => 'BasketballManagement', 'action' => 'types'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('BasketballManagement', 'types') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-link"></i> Relations', 
                                ['controller' => 'BasketballManagement', 'action' => 'relationships'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('BasketballManagement', 'relationships') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Handball Configuration -->
                <div class="nav-section sport-config-section">
                    <div class="sport-menu-header" onclick="toggleSportMenu('handball')">
                        <i class="fas fa-hand-paper sport-icon"></i>
                        <span class="sport-name">Handball</span>
                        <i class="fas fa-chevron-down toggle-arrow" id="handball-arrow"></i>
                    </div>
                    <ul class="sport-submenu" id="handball-menu">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Catégories', 
                                ['controller' => 'HandballManagement', 'action' => 'categories'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('HandballManagement', 'categories') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-map"></i> Types', 
                                ['controller' => 'HandballManagement', 'action' => 'types'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('HandballManagement', 'types') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-link"></i> Relations', 
                                ['controller' => 'HandballManagement', 'action' => 'relationships'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('HandballManagement', 'relationships') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Volleyball Configuration -->
                <div class="nav-section sport-config-section">
                    <div class="sport-menu-header" onclick="toggleSportMenu('volleyball')">
                        <i class="fas fa-volleyball-ball sport-icon"></i>
                        <span class="sport-name">Volleyball</span>
                        <i class="fas fa-chevron-down toggle-arrow" id="volleyball-arrow"></i>
                    </div>
                    <ul class="sport-submenu" id="volleyball-menu">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Catégories', 
                                ['controller' => 'VolleyballManagement', 'action' => 'categories'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('VolleyballManagement', 'categories') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-map"></i> Types', 
                                ['controller' => 'VolleyballManagement', 'action' => 'types'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('VolleyballManagement', 'types') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-link"></i> Relations', 
                                ['controller' => 'VolleyballManagement', 'action' => 'relationships'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('VolleyballManagement', 'relationships') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Beach Volleyball Configuration -->
                <div class="nav-section sport-config-section">
                    <div class="sport-menu-header" onclick="toggleSportMenu('beachvolley')">
                        <i class="fas fa-umbrella-beach sport-icon"></i>
                        <span class="sport-name">Beach Volley</span>
                        <i class="fas fa-chevron-down toggle-arrow" id="beachvolley-arrow"></i>
                    </div>
                    <ul class="sport-submenu" id="beachvolley-menu">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Catégories', 
                                ['controller' => 'BeachvolleyManagement', 'action' => 'categories'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('BeachvolleyManagement', 'categories') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-map"></i> Types', 
                                ['controller' => 'BeachvolleyManagement', 'action' => 'types'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('BeachvolleyManagement', 'types') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-link"></i> Relations', 
                                ['controller' => 'BeachvolleyManagement', 'action' => 'relationships'], 
                                ['class' => 'nav-link submenu-link' . ($isActive('BeachvolleyManagement', 'relationships') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>


                <!-- UTILISATEURS -->
                <div class="nav-section-title">👥 Utilisateurs</div>
                <div class="nav-section">
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-users"></i> Tous', 
                                ['controller' => 'Admin', 'action' => 'users'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'users') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-user-plus"></i> Nouveau', 
                                ['controller' => 'Admin', 'action' => 'addUser'], 
                                ['class' => 'nav-link' . ($isActive('Admin', 'addUser') ? ' active' : ''), 'escape' => false]) ?>
                        </li>
                    </ul>
                </div>

                <!-- OUTILS -->
                <div class="nav-section-title">🔧 Outils</div>
                <div class="nav-section">
                    <ul class="nav-items">
                        <li class="nav-item">
                            <?= $this->Html->link('<i class="fas fa-sign-out-alt"></i> Retour site', 
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

        function toggleSportMenu(sport) {
            const header = document.querySelector(`div[onclick="toggleSportMenu('${sport}')"]`);
            const menu = document.getElementById(`${sport}-menu`);
            const arrow = document.getElementById(`${sport}-arrow`);
            
            if (header && menu && arrow) {
                // Toggle expanded classes
                header.classList.toggle('expanded');
                menu.classList.toggle('expanded');
                
                // Update arrow rotation (handled by CSS)
                if (menu.classList.contains('expanded')) {
                    menu.style.display = 'block';
                } else {
                    // Add delay to allow transition before hiding
                    setTimeout(() => {
                        if (!menu.classList.contains('expanded')) {
                            menu.style.display = 'none';
                        }
                    }, 300);
                }
            }
        }

        // Initialize sport menus on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial state - only football expanded by default
            const footballMenu = document.getElementById('football-menu');
            const footballHeader = document.querySelector('div[onclick="toggleSportMenu(\'football\')"]');
            
            if (footballMenu && footballHeader) {
                footballMenu.style.display = 'block';
                footballMenu.classList.add('expanded');
                footballHeader.classList.add('expanded');
            }
            
            // Ensure other menus start collapsed
            const sports = ['basketball', 'handball', 'volleyball', 'beachvolley'];
            sports.forEach(sport => {
                const menu = document.getElementById(`${sport}-menu`);
                if (menu) {
                    menu.style.display = 'none';
                    menu.classList.remove('expanded');
                }
            });
        });

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
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize all dropdowns
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
</body>
</html>