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
    <?= $this->Html->meta('icon') ?>

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
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logout']) ?>" class="btn btn-danger">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>">Connexion</a>
                    <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'register']) ?>" class="btn btn-primary">Inscription</a>
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
</body>
</html>
