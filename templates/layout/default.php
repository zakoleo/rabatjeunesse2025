<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Rabat Jeunesse - Inscriptions Tournois';
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

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'app']) ?>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    
    <style>
        /* Navigation Styles */
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            height: 70px;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
        }
        
        .logo-link {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .nav-logo {
            height: 50px;
            width: auto;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            color: var(--primary-color);
            background-color: var(--background-light);
        }
        
        .nav-links .btn {
            padding: 10px 20px !important;
            min-height: auto;
        }
        
        .admin-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            font-weight: 600 !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-link:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%) !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        /* Adjust main content for fixed nav */
        body {
            padding-top: 70px;
        }
        
        .main {
            min-height: calc(100vh - 70px);
            background-color: var(--background-light);
        }
        
        /* Override any conflicting styles */
        .top-nav,
        .top-nav-title,
        .top-nav-links {
            all: unset;
        }
        
        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 25px;
            color: #495057;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .user-dropdown-toggle:hover {
            background: #e9ecef;
            color: #343a40;
            text-decoration: none;
        }
        
        .user-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            line-height: 1;
        }
        
        .user-role {
            font-size: 11px;
            color: #6c757d;
            line-height: 1;
        }
        
        .dropdown-arrow {
            font-size: 10px;
            transition: transform 0.2s ease;
        }
        
        .user-dropdown.active .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            margin-top: 5px;
        }
        
        .user-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -6px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: white;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-right: none;
            transform: rotate(45deg);
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            color: #495057;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:first-child {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        
        .dropdown-item:last-child {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        
        .dropdown-item:hover {
            background: #f8f9fa;
            color: #343a40;
            text-decoration: none;
        }
        
        .dropdown-item.danger {
            color: #dc3545;
        }
        
        .dropdown-item.danger:hover {
            background: #f8d7da;
            color: #721c24;
        }
        
        .dropdown-divider {
            height: 1px;
            background: #dee2e6;
            margin: 5px 0;
        }
        
        /* White button style for Inscription */
        .btn-white {
            background: white !important;
            color: #333 !important;
            border: 2px solid #dee2e6 !important;
        }
        
        .btn-white:hover {
            background: #f8f9fa !important;
            color: #333 !important;
            text-decoration: none;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }
            
            .nav-links {
                gap: 0.5rem;
            }
            
            .nav-links a {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .nav-logo {
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="<?= $this->Url->build('/') ?>" class="logo-link">
                    <?= $this->Html->image('logo.webp', ['alt' => 'Rabat Jeunesse', 'class' => 'nav-logo']) ?>
                </a>
            </div>
            <div class="nav-links">
                <a href="<?= $this->Url->build(['controller' => 'Sports', 'action' => 'index']) ?>">Sports</a>
                <?php if ($this->request->getAttribute('identity')): ?>
                    <a href="<?= $this->Url->build(['controller' => 'Teams', 'action' => 'index']) ?>">Mes équipes</a>
                    <?php 
                    $identity = $this->request->getAttribute('identity');
                    $isAdmin = false;
                    
                    if ($identity) {
                        // Simple admin check
                        try {
                            if ($identity->is_admin == 1 || $identity->is_admin === true) {
                                $isAdmin = true;
                            }
                        } catch (\Exception $e) {
                            // Field might not exist
                        }
                        
                        // Fallback checks
                        if (!$isAdmin) {
                            $adminEmails = ['admin@lifemoz.com', 'zouhair@gmail.com', 'admin@rabatjeunesse.ma'];
                            $adminUsernames = ['admin', 'administrator', 'admin1', 'Zouhair'];
                            
                            if (in_array($identity->email, $adminEmails) || 
                                in_array($identity->username, $adminUsernames) || 
                                $identity->id == 1) {
                                $isAdmin = true;
                            }
                        }
                    }
                    
                    if ($isAdmin): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Admin', 'action' => 'index']) ?>" class="admin-link">
                            <i class="fas fa-shield-alt"></i> Administration
                        </a>
                    <?php endif; ?>
                    
                    <!-- User Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-toggle" onclick="toggleUserDropdown()">
                            <div class="user-avatar">
                                <?= strtoupper(substr($identity->username ?? $identity->email ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="user-info">
                                <div class="user-name"><?= h($identity->username ?? explode('@', $identity->email)[0] ?? 'Utilisateur') ?></div>
                                <div class="user-role"><?= $isAdmin ? 'Administrateur' : 'Utilisateur' ?></div>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </div>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-cog"></i> Mon profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>" class="dropdown-item danger">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>">Connexion</a>
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']) ?>" class="btn btn-white">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>

    <script>
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const isClickInside = dropdown && dropdown.contains(event.target);
            
            if (!isClickInside && dropdown && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
            }
        });

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown && dropdown.classList.contains('active')) {
                    dropdown.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
