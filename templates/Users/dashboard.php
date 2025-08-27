<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $inscriptions
 * @var int $totalInscriptions
 */

// Add the main CSS file to ensure template colors are available
echo $this->Html->css('app');
?>
<div class="dashboard">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg">
            <div class="hero-pattern"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="welcome-text">
                    <span class="greeting">Salut</span>
                    <h1 class="username"><?= h($user->username) ?></h1>
                    <p class="tagline">Prêt pour de nouveaux défis sportifs ?</p>
                </div>
                <div class="stats-widget">
                    <div class="stat-circle">
                        <svg class="progress-ring" width="120" height="120">
                            <circle class="progress-ring-bg" cx="60" cy="60" r="50"></circle>
                            <circle class="progress-ring-fill" cx="60" cy="60" r="50" 
                                style="--progress: <?= $totalInscriptions > 0 ? min($totalInscriptions * 20, 100) : 0 ?>%"></circle>
                        </svg>
                        <div class="stat-content">
                            <span class="stat-number"><?= $totalInscriptions ?></span>
                            <span class="stat-label">équipes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="main-content">
        <div class="container">
            <?php if ($totalInscriptions > 0): ?>
                <!-- Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Sports Overview -->
                    <section class="sports-section">
                        <header class="section-header">
                            <h2><i class="fas fa-trophy"></i> Mes Sports</h2>
                            <span class="section-count">
                                <?= count(array_filter($inscriptions)) ?>/<?= count($inscriptions) ?> sports actifs
                            </span>
                        </header>
                        
                        <div class="sports-list">
                            <?php
                            $sportsConfig = [
                                'football' => ['name' => 'Football', 'icon' => '⚽', 'gradient' => 'football-gradient'],
                                'basketball' => ['name' => 'Basketball', 'icon' => '🏀', 'gradient' => 'basketball-gradient'], 
                                'handball' => ['name' => 'Handball', 'icon' => '🤾', 'gradient' => 'handball-gradient'],
                                'volleyball' => ['name' => 'Volleyball', 'icon' => '🏐', 'gradient' => 'volleyball-gradient'],
                                'beachvolley' => ['name' => 'Beach Volley', 'icon' => '🏖️', 'gradient' => 'beachvolley-gradient']
                            ];
                            
                            $addActions = [
                                'football' => 'add',
                                'basketball' => 'add-basketball',
                                'handball' => 'add-handball',
                                'volleyball' => 'add-volleyball',
                                'beachvolley' => 'add-beachvolley'
                            ];
                            
                            foreach ($sportsConfig as $key => $sport):
                                $count = $inscriptions[$key] ?? 0;
                            ?>
                                <div class="sport-item <?= $sport['gradient'] ?> <?= $count === 0 ? 'no-teams' : '' ?>">
                                    <div class="sport-icon">
                                        <span class="emoji"><?= $sport['icon'] ?></span>
                                    </div>
                                    <div class="sport-details">
                                        <h4><?= $sport['name'] ?></h4>
                                        <?php if ($count > 0): ?>
                                            <p><?= $count ?> équipe<?= $count > 1 ? 's' : '' ?></p>
                                        <?php else: ?>
                                            <p class="no-teams-text">Aucune équipe</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="sport-actions">
                                        <?php if ($count > 0): ?>
                                            <?= $this->Html->link(
                                                '<i class="fas fa-eye"></i>',
                                                ['controller' => 'Teams', 'action' => 'index'],
                                                ['class' => 'btn-icon', 'escape' => false, 'title' => 'Voir mes équipes ' . $sport['name']]
                                            ) ?>
                                        <?php else: ?>
                                            <span class="btn-icon disabled" title="Aucune équipe à voir pour <?= $sport['name'] ?>">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?= $this->Html->link(
                                            '<i class="fas fa-plus"></i>',
                                            ['controller' => 'Teams', 'action' => $addActions[$key]],
                                            ['class' => 'btn-icon primary', 'escape' => false, 'title' => 'Ajouter une équipe de ' . $sport['name']]
                                        ) ?>
                                    </div>
                                </div>
                            <?php 
                            endforeach; 
                            ?>
                        </div>
                    </section>

                    <!-- Quick Actions -->
                    <section class="actions-section">
                        <header class="section-header">
                            <h2><i class="fas fa-bolt"></i> Actions Rapides</h2>
                        </header>
                        
                        <div class="action-cards">
                            <?= $this->Html->link(
                                '<div class="action-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Gérer</h4>
                                    <p>Mes équipes</p>
                                </div>',
                                ['controller' => 'Teams', 'action' => 'index'],
                                ['class' => 'action-card manage', 'escape' => false]
                            ) ?>
                            
                            <?= $this->Html->link(
                                '<div class="action-icon">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Ajouter</h4>
                                    <p>Nouvelle équipe</p>
                                </div>',
                                ['controller' => 'Sports', 'action' => 'index'],
                                ['class' => 'action-card add', 'escape' => false]
                            ) ?>
                        </div>
                    </section>

                    <!-- Recent Activity -->
                    <section class="activity-section">
                        <header class="section-header">
                            <h2><i class="fas fa-clock"></i> Activité Récente</h2>
                        </header>
                        
                        <div class="activity-feed">
                            <div class="activity-item">
                                <div class="activity-dot success"></div>
                                <div class="activity-content">
                                    <p><strong>Équipe inscrite</strong></p>
                                    <small>Il y a 2 jours</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-dot info"></div>
                                <div class="activity-content">
                                    <p><strong>Profil mis à jour</strong></p>
                                    <small>Il y a 1 semaine</small>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

            <?php else: ?>
                <!-- Empty State -->
                <section class="empty-state">
                    <div class="empty-illustration">
                        <div class="empty-circle">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="empty-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    
                    <div class="empty-content">
                        <h2>Votre aventure sportive commence ici !</h2>
                        <p>Rejoignez les tournois, formez vos équipes et vivez des moments inoubliables.</p>
                        
                        <?= $this->Html->link(
                            '<span>Commencer maintenant</span>
                            <i class="fas fa-arrow-right"></i>',
                            ['controller' => 'Sports', 'action' => 'index'],
                            ['class' => 'cta-button', 'escape' => false]
                        ) ?>
                    </div>

                    <!-- Sports Grid -->
                    <div class="sports-preview">
                        <h3>Choisissez votre sport</h3>
                        <div class="preview-cards">
                            <?php 
                            $sportsConfig = [
                                'football' => ['name' => 'Football', 'icon' => '⚽', 'gradient' => 'football-gradient'],
                                'basketball' => ['name' => 'Basketball', 'icon' => '🏀', 'gradient' => 'basketball-gradient'], 
                                'handball' => ['name' => 'Handball', 'icon' => '🤾', 'gradient' => 'handball-gradient'],
                                'volleyball' => ['name' => 'Volleyball', 'icon' => '🏐', 'gradient' => 'volleyball-gradient'],
                                'beachvolley' => ['name' => 'Beach Volley', 'icon' => '🏖️', 'gradient' => 'beachvolley-gradient']
                            ];
                            
                            $addActions = [
                                'football' => 'add',
                                'basketball' => 'add-basketball',
                                'handball' => 'add-handball',
                                'volleyball' => 'add-volleyball',
                                'beachvolley' => 'add-beachvolley'
                            ];
                            
                            foreach ($sportsConfig as $key => $sport): 
                            ?>
                                <?= $this->Html->link(
                                    '<div class="preview-icon">
                                        <span>' . $sport['icon'] . '</span>
                                    </div>
                                    <h4>' . $sport['name'] . '</h4>
                                    <div class="preview-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>',
                                    ['controller' => 'Teams', 'action' => $addActions[$key]],
                                    ['class' => 'preview-card ' . $sport['gradient'], 'escape' => false]
                                ) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background: var(--background-light);
        color: var(--text-dark);
        line-height: 1.6;
    }

    .dashboard {
        min-height: 100vh;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        padding: 4rem 0;
        overflow: hidden;
    }

    .hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.1;
    }

    .hero-pattern {
        background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                          radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);
        background-size: 100px 100px;
        height: 100%;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
    }

    .hero-content {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .greeting {
        display: block;
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0.5rem;
    }

    .username {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.8));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .tagline {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
    }

    /* Stats Widget */
    .stats-widget {
        flex-shrink: 0;
    }

    .stat-circle {
        position: relative;
        width: 120px;
        height: 120px;
    }

    .progress-ring {
        transform: rotate(-90deg);
    }

    .progress-ring-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.1);
        stroke-width: 8;
    }

    .progress-ring-fill {
        fill: none;
        stroke: var(--secondary-color);
        stroke-width: 8;
        stroke-linecap: round;
        stroke-dasharray: 314;
        stroke-dashoffset: calc(314 - (314 * var(--progress)) / 100);
        transition: stroke-dashoffset 2s ease;
    }

    .stat-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .stat-number {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .stat-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
    }

    /* Main Content */
    .main-content {
        padding: 3rem 0;
        margin-top: -2rem;
        position: relative;
        z-index: 10;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        grid-template-areas: 
            "sports actions"
            "sports activity";
    }

    /* Section Styles */
    .sports-section { grid-area: sports; }
    .actions-section { grid-area: actions; }
    .activity-section { grid-area: activity; }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-count {
        background: var(--border-color);
        color: var(--text-light);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Sports List */
    .sports-section {
        background: var(--background-white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
    }

    .sports-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .sport-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--tw-gradient-stops));
        border-radius: 12px;
        color: white;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .sport-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .sport-item.no-teams {
        opacity: 0.7;
        background: linear-gradient(135deg, var(--text-light), var(--text-dark));
    }
    
    .sport-item.no-teams:hover {
        opacity: 0.85;
        transform: translateY(-2px);
    }


    .sport-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(10px);
    }

    .sport-details {
        flex: 1;
    }

    .sport-details h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .sport-details p {
        opacity: 0.9;
        font-size: 0.9rem;
    }
    
    .no-teams-text {
        font-style: italic;
        opacity: 0.8 !important;
    }

    .sport-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        backdrop-filter: blur(10px);
        text-decoration: none;
    }

    .btn-icon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        color: white;
    }

    .btn-icon.primary {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-dark);
    }

    .btn-icon.primary:hover {
        background: white;
        color: var(--text-dark);
    }
    
    .btn-icon.disabled {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.4);
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Actions Section */
    .actions-section {
        background: var(--background-white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        height: fit-content;
    }

    .action-cards {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 2px solid transparent;
    }

    .action-card.manage {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
    }

    .action-card.add {
        background: linear-gradient(135deg, var(--secondary-color), var(--warning-color));
        color: var(--text-dark);
    }

    .action-card:hover {
        transform: translateY(-2px);
        border-color: currentColor;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: rgba(255, 255, 255, 0.5);
    }

    .action-text h4 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .action-text p {
        opacity: 0.8;
        font-size: 0.9rem;
    }

    /* Activity Section */
    .activity-section {
        background: var(--background-white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
    }

    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--background-light);
        border-radius: 10px;
    }

    .activity-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .activity-dot.success { background: var(--success-color); }
    .activity-dot.info { background: var(--info-color); }

    .activity-content p {
        margin-bottom: 0.25rem;
        color: var(--text-dark);
    }

    .activity-content small {
        color: var(--text-light);
        font-size: 0.8rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 0;
        background: var(--background-white);
        border-radius: 20px;
        margin: 2rem 0;
        box-shadow: var(--shadow-md);
    }

    .empty-illustration {
        position: relative;
        margin-bottom: 2rem;
        display: inline-block;
    }

    .empty-circle {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--warning-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        margin: 0 auto 1rem;
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .empty-dots {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .empty-dots span {
        width: 8px;
        height: 8px;
        background: var(--border-color);
        border-radius: 50%;
        animation: bounce 1.4s ease-in-out infinite;
    }

    .empty-dots span:nth-child(1) { animation-delay: -0.32s; }
    .empty-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
        40% { transform: scale(1); opacity: 1; }
    }

    .empty-content h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .empty-content p {
        font-size: 1.1rem;
        color: var(--text-light);
        margin-bottom: 2rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        margin-bottom: 3rem;
    }

    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        color: white;
    }

    /* Sports Preview */
    .sports-preview h3 {
        color: var(--text-dark);
        margin-bottom: 2rem;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .preview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .preview-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem 1rem;
        border-radius: 16px;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .preview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        color: white;
    }

    .preview-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        background: rgba(255, 255, 255, 0.2);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    .preview-card h4 {
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .preview-arrow {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .preview-card:hover .preview-arrow {
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            grid-template-areas: 
                "sports"
                "actions"
                "activity";
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 1rem;
        }

        .hero-content {
            flex-direction: column;
            text-align: center;
        }

        .username {
            font-size: 2.5rem;
        }

        .main-content {
            padding: 2rem 0;
        }

        .sports-section,
        .actions-section,
        .activity-section {
            padding: 1.5rem;
        }

        .action-cards {
            gap: 0.75rem;
        }

        .preview-cards {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.75rem;
        }

        .preview-card {
            padding: 1.5rem 1rem;
        }
    }
</style>