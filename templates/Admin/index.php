<?php
/**
 * Admin Statistics Page - Detailed Statistics View
 * @var \App\View\AppView $this
 * @var array $stats
 * @var int $totalUsers
 */

$this->assign('title', 'Dashboard Administrateur - Statistiques Détaillées');
?>

<div class="admin-page admin-stats-page">
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="header-content">
            <div class="header-info">
                <h1><i class="fas fa-chart-bar"></i> Dashboard Administrateur</h1>
                <p>Analyses complètes des inscriptions, utilisateurs et activités sportives - Rabat Jeunesse 2025</p>
                <div class="stats-summary">
                    <span class="stat-item">
                        <strong><?= isset($stats['summary']) ? $stats['summary']['total_teams'] : array_sum(array_column($stats, 'total')) ?></strong> équipes total
                    </span>
                    <span class="stat-item">
                        <strong><?= isset($stats['summary']) ? $stats['summary']['total_pending'] : array_sum(array_column($stats, 'pending')) ?></strong> en attente
                    </span>
                    <span class="stat-item">
                        <strong><?= isset($stats['summary']) ? $stats['summary']['total_verified'] : array_sum(array_column($stats, 'verified')) ?></strong> vérifiées
                    </span>
                    <span class="stat-item">
                        <strong><?= $totalUsers ?? 0 ?></strong> utilisateurs
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-users"></i> Gérer Équipes', 
                    ['action' => 'teams'], 
                    ['class' => 'btn btn-primary header-btn', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-user-cog"></i> Gérer Utilisateurs', 
                    ['action' => 'users'], 
                    ['class' => 'btn btn-secondary header-btn', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                    ['action' => 'exportStats'], 
                    ['class' => 'btn btn-info header-btn', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <!-- Statistics Content -->
    <div class="dashboard-content">
        <!-- Overview Cards -->
        <div class="quick-stats">
            <div class="stat-card overview-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3><?= isset($stats['summary']) ? $stats['summary']['total_verified'] : array_sum(array_column($stats, 'verified')) ?></h3>
                    <p>Équipes Vérifiées</p>
                    <div class="stat-percentage">
                        <?php 
                        $totalTeams = isset($stats['summary']) ? $stats['summary']['total_teams'] : array_sum(array_column($stats, 'total'));
                        $totalVerified = isset($stats['summary']) ? $stats['summary']['total_verified'] : array_sum(array_column($stats, 'verified'));
                        $verifiedPercentage = $totalTeams > 0 ? round(($totalVerified / $totalTeams) * 100, 1) : 0;
                        ?>
                        <span class="percentage"><?= $verifiedPercentage ?>%</span> du total
                    </div>
                </div>
            </div>

            <div class="stat-card overview-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?= isset($stats['summary']) ? $stats['summary']['total_pending'] : array_sum(array_column($stats, 'pending')) ?></h3>
                    <p>En Attente de Validation</p>
                    <div class="stat-percentage">
                        <?php 
                        $totalPending = isset($stats['summary']) ? $stats['summary']['total_pending'] : array_sum(array_column($stats, 'pending'));
                        $pendingPercentage = $totalTeams > 0 ? round(($totalPending / $totalTeams) * 100, 1) : 0;
                        ?>
                        <span class="percentage"><?= $pendingPercentage ?>%</span> du total
                    </div>
                </div>
            </div>

            <div class="stat-card overview-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3><?= isset($stats['summary']) ? $stats['summary']['total_recent'] : array_sum(array_column($stats, 'recent')) ?></h3>
                    <p>Inscriptions Cette Semaine</p>
                    <div class="stat-percentage">
                        <span class="percentage">+<?= isset($stats['summary']) ? $stats['summary']['total_recent'] : array_sum(array_column($stats, 'recent')) ?></span> nouvelles inscriptions
                    </div>
                </div>
            </div>

            <div class="stat-card overview-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?= $totalUsers ?? 0 ?></h3>
                    <p>Utilisateurs Inscrits</p>
                    <div class="stat-percentage">
                        <span class="percentage">100%</span> actifs
                    </div>
                </div>
            </div>
        </div>

        <!-- Sports Statistics Grid -->
        <div class="sports-statistics-grid">
            <?php 
            $sportIcons = [
                'football' => 'fas fa-futbol',
                'basketball' => 'fas fa-basketball-ball', 
                'handball' => 'fas fa-hand-paper',
                'volleyball' => 'fas fa-volleyball-ball',
                'beachvolley' => 'fas fa-umbrella-beach'
            ];
            $sportColors = [
                'football' => '#22c55e',
                'basketball' => '#f97316',
                'handball' => '#06b6d4', 
                'volleyball' => '#8b5cf6',
                'beachvolley' => '#eab308'
            ];
            $sportNames = [
                'football' => 'Football',
                'basketball' => 'Basketball',
                'handball' => 'Handball',
                'volleyball' => 'Volleyball',
                'beachvolley' => 'Beach Volleyball'
            ];
            ?>

            <?php foreach (['football', 'basketball', 'handball', 'volleyball', 'beachvolley'] as $sport): ?>
                <?php if (isset($stats[$sport])): ?>
                    <div class="content-section sport-stats-card">
                        <div class="widget-header" style="background: linear-gradient(135deg, <?= $sportColors[$sport] ?>, <?= $sportColors[$sport] ?>dd);">
                            <h3 style="color: white;">
                                <i class="<?= $sportIcons[$sport] ?>"></i> 
                                <?= $sportNames[$sport] ?>
                            </h3>
                            <div class="sport-total" style="color: white; font-size: 2rem; font-weight: bold;">
                                <?= $stats[$sport]['total'] ?>
                            </div>
                        </div>
                        <div class="sport-stats-body">
                            <div class="stat-row">
                                <div class="stat-item">
                                    <div class="stat-label">
                                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                        Vérifiées
                                    </div>
                                    <div class="stat-value"><?= $stats[$sport]['verified'] ?></div>
                                    <div class="stat-bar">
                                        <?php 
                                        $verifiedPercent = $stats[$sport]['total'] > 0 ? 
                                            ($stats[$sport]['verified'] / $stats[$sport]['total'] * 100) : 0;
                                        ?>
                                        <div class="stat-progress" style="width: <?= $verifiedPercent ?>%; background: #10b981;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-row">
                                <div class="stat-item">
                                    <div class="stat-label">
                                        <i class="fas fa-clock" style="color: #f59e0b;"></i>
                                        En Attente
                                    </div>
                                    <div class="stat-value"><?= $stats[$sport]['pending'] ?></div>
                                    <div class="stat-bar">
                                        <?php 
                                        $pendingPercent = $stats[$sport]['total'] > 0 ? 
                                            ($stats[$sport]['pending'] / $stats[$sport]['total'] * 100) : 0;
                                        ?>
                                        <div class="stat-progress" style="width: <?= $pendingPercent ?>%; background: #f59e0b;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-row">
                                <div class="stat-item">
                                    <div class="stat-label">
                                        <i class="fas fa-times-circle" style="color: #ef4444;"></i>
                                        Rejetées
                                    </div>
                                    <div class="stat-value"><?= $stats[$sport]['rejected'] ?></div>
                                    <div class="stat-bar">
                                        <?php 
                                        $rejectedPercent = $stats[$sport]['total'] > 0 ? 
                                            ($stats[$sport]['rejected'] / $stats[$sport]['total'] * 100) : 0;
                                        ?>
                                        <div class="stat-progress" style="width: <?= $rejectedPercent ?>%; background: #ef4444;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-row">
                                <div class="stat-item">
                                    <div class="stat-label">
                                        <i class="fas fa-calendar-week" style="color: #6366f1;"></i>
                                        Cette Semaine
                                    </div>
                                    <div class="stat-value"><?= $stats[$sport]['recent'] ?></div>
                                    <div class="recent-badge">
                                        <?php if ($stats[$sport]['recent'] > 0): ?>
                                            <span class="badge-positive">+<?= $stats[$sport]['recent'] ?> nouvelles</span>
                                        <?php else: ?>
                                            <span class="badge-neutral">Aucune nouvelle</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="sport-actions">
                                <?= $this->Html->link(
                                    '<i class="fas fa-eye"></i> Voir les équipes',
                                    ['action' => 'teams', '?' => ['sport' => $sport]],
                                    ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]
                                ) ?>
                                <?= $this->Html->link(
                                    '<i class="fas fa-download"></i> Exporter',
                                    ['action' => 'exportSport', $sport],
                                    ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false]
                                ) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Summary Information -->
        <div class="content-section summary-section">
            <div class="widget-header">
                <h3><i class="fas fa-info-circle"></i> Résumé & Actions Rapides</h3>
                <div class="last-updated">
                    Dernière mise à jour: <?= isset($stats['summary']['last_updated']) ? 
                        date('d/m/Y à H:i', strtotime($stats['summary']['last_updated'])) : 'maintenant' ?>
                </div>
            </div>
            <div class="summary-content">
                <div class="summary-grid">
                    <div class="summary-item">
                        <h4>Taux de Validation Global</h4>
                        <div class="summary-percentage">
                            <?= $verifiedPercentage ?>%
                        </div>
                        <p>des équipes sont vérifiées</p>
                    </div>
                    <div class="summary-item">
                        <h4>Sport le Plus Populaire</h4>
                        <div class="summary-sport">
                            <?php
                            $mostPopular = 'football';
                            $maxTeams = 0;
                            foreach (['football', 'basketball', 'handball', 'volleyball', 'beachvolley'] as $sport) {
                                if (isset($stats[$sport]) && $stats[$sport]['total'] > $maxTeams) {
                                    $maxTeams = $stats[$sport]['total'];
                                    $mostPopular = $sport;
                                }
                            }
                            ?>
                            <i class="<?= $sportIcons[$mostPopular] ?>" style="color: <?= $sportColors[$mostPopular] ?>;"></i>
                            <?= $sportNames[$mostPopular] ?>
                        </div>
                        <p><?= $maxTeams ?> équipes inscrites</p>
                    </div>
                    <div class="summary-item">
                        <h4>Activité Récente</h4>
                        <div class="summary-activity">
                            <?= isset($stats['summary']) ? $stats['summary']['total_recent'] : array_sum(array_column($stats, 'recent')) ?>
                        </div>
                        <p>inscriptions cette semaine</p>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="quick-actions-section">
                    <h4><i class="fas fa-bolt"></i> Actions Rapides</h4>
                    <div class="actions-grid">
                        <?= $this->Html->link('<i class="fas fa-plus"></i><span>Nouvelle Équipe</span>', 
                            ['controller' => 'Teams', 'action' => 'add'], 
                            ['class' => 'action-btn primary', 'escape' => false]) ?>
                        <?= $this->Html->link('<i class="fas fa-user-plus"></i><span>Nouvel Utilisateur</span>', 
                            ['action' => 'addUser'], 
                            ['class' => 'action-btn secondary', 'escape' => false]) ?>
                        <?= $this->Html->link('<i class="fas fa-file-export"></i><span>Exporter Données</span>', 
                            ['action' => 'export'], 
                            ['class' => 'action-btn success', 'escape' => false]) ?>
                        <?= $this->Html->link('<i class="fas fa-cogs"></i><span>Paramètres</span>', 
                            ['action' => 'settings'], 
                            ['class' => 'action-btn info', 'escape' => false]) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Registrations Table -->
        <?php if (!empty($recentTeams)): ?>
            <div class="content-section recent-registrations">
                <div class="widget-header">
                    <h3><i class="fas fa-list"></i> Dernières Inscriptions</h3>
                    <div class="widget-actions">
                        <?= $this->Html->link('Voir tout <i class="fas fa-arrow-right"></i>', ['action' => 'teams'], ['class' => 'btn btn-sm btn-primary', 'escape' => false]) ?>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-futbol"></i> Sport</th>
                                <th><i class="fas fa-users"></i> Nom de l'équipe</th>
                                <th><i class="fas fa-user"></i> Responsable</th>
                                <th><i class="fas fa-flag"></i> Statut</th>
                                <th><i class="fas fa-calendar"></i> Date d'inscription</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recentTeams, 0, 10) as $team): ?>
                                <tr>
                                    <td>
                                        <span class="sport-badge sport-<?= strtolower($team->sport_type ?? 'football') ?>">
                                            <?= ucfirst($team->sport_type ?? 'Football') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="team-details">
                                            <div class="team-name"><?= h($team->nom_equipe ?? 'Équipe inconnue') ?></div>
                                            <small class="team-id">ID: <?= $team->id ?? '0' ?></small>
                                        </div>
                                    </td>
                                    <td><?= h($team->user->username ?? 'N/A') ?></td>
                                    <td>
                                        <span class="status-badge status-<?= $team->status ?? 'pending' ?>">
                                            <?php
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'verified' => 'Vérifiée',
                                                'rejected' => 'Rejetée'
                                            ];
                                            echo $statusLabels[$team->status ?? 'pending'];
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <div class="date"><?= $team->created->format('d/m/Y') ?></div>
                                            <small class="time"><?= $team->created->format('H:i') ?></small>
                                        </div>
                                    </td>
                                    <td class="actions">
                                        <?= $this->Html->link(
                                            '<i class="fas fa-eye"></i>',
                                            ['action' => 'teamView', $team->sport_type ?? 'football', $team->id ?? 0],
                                            ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir détails']
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Admin Statistics Page Styles */
.admin-stats-page .quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.overview-card {
    background: white !important;
    border-radius: 12px !important;
    padding: 1.5rem !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    display: flex !important;
    align-items: center !important;
    gap: 1rem !important;
    transition: all 0.3s ease !important;
}

.overview-card:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
}

.overview-card .stat-icon {
    width: 60px !important;
    height: 60px !important;
    border-radius: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 1.5rem !important;
    color: white !important;
    flex-shrink: 0 !important;
}

.overview-card .stat-info h3 {
    font-size: 2rem !important;
    font-weight: 800 !important;
    margin: 0 0 0.25rem 0 !important;
    color: #1f2937 !important;
}

.overview-card .stat-info p {
    color: #6b7280 !important;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    margin: 0 0 0.5rem 0 !important;
}

.stat-percentage {
    font-size: 0.85rem !important;
    color: #374151 !important;
}

.stat-percentage .percentage {
    font-weight: 600 !important;
    color: #059669 !important;
}

.sports-statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.sport-stats-card {
    border-radius: 12px !important;
    overflow: hidden !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
    transition: all 0.3s ease !important;
}

.sport-stats-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
}

.sport-stats-body {
    padding: 1.5rem !important;
    background: white !important;
}

.stat-row {
    margin-bottom: 1rem !important;
}

.stat-item {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 0.5rem !important;
}

.stat-label {
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    color: #374151 !important;
}

.stat-value {
    font-weight: 600 !important;
    color: #1f2937 !important;
}

.stat-bar {
    width: 100% !important;
    height: 4px !important;
    background: #f3f4f6 !important;
    border-radius: 2px !important;
    margin-top: 0.25rem !important;
    overflow: hidden !important;
}

.stat-progress {
    height: 100% !important;
    border-radius: 2px !important;
    transition: width 0.3s ease !important;
}

.recent-badge {
    margin-top: 0.25rem !important;
}

.badge-positive {
    background: #dcfce7 !important;
    color: #16a34a !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 6px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
}

.badge-neutral {
    background: #f3f4f6 !important;
    color: #6b7280 !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 6px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
}

.sport-actions {
    display: flex !important;
    gap: 0.5rem !important;
    margin-top: 1rem !important;
    padding-top: 1rem !important;
    border-top: 1px solid #f3f4f6 !important;
}

.summary-section .widget-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

.last-updated {
    font-size: 0.85rem !important;
    color: #6b7280 !important;
    font-style: italic !important;
}

.summary-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
    gap: 2rem !important;
    padding: 1.5rem !important;
    margin-bottom: 2rem !important;
}

.summary-item {
    text-align: center !important;
}

.summary-item h4 {
    font-size: 1rem !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 1rem !important;
}

.summary-percentage,
.summary-activity {
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    color: #059669 !important;
    margin-bottom: 0.5rem !important;
}

.summary-sport {
    font-size: 1.2rem !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 0.5rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.5rem !important;
}

.summary-sport i {
    font-size: 1.5rem !important;
}

.summary-item p {
    color: #6b7280 !important;
    font-size: 0.9rem !important;
}

/* Quick Actions Section */
.quick-actions-section {
    padding: 1.5rem !important;
    border-top: 1px solid #f3f4f6 !important;
    background: #f8fafc !important;
}

.quick-actions-section h4 {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 1rem !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
}

.actions-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
    gap: 1rem !important;
}

.action-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.75rem !important;
    padding: 1rem 1.5rem !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    border: none !important;
    cursor: pointer !important;
}

.action-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    text-decoration: none !important;
}

.action-btn.primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    color: white !important;
}

.action-btn.secondary {
    background: linear-gradient(135deg, #6b7280, #4b5563) !important;
    color: white !important;
}

.action-btn.success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    color: white !important;
}

.action-btn.info {
    background: linear-gradient(135deg, #06b6d4, #0891b2) !important;
    color: white !important;
}

.action-btn i {
    font-size: 1.1rem !important;
}

.sport-total {
    font-size: 1.8rem !important;
    font-weight: bold !important;
    color: white !important;
}

@media (max-width: 768px) {
    .sports-statistics-grid {
        grid-template-columns: 1fr !important;
    }
    
    .summary-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .sport-actions {
        flex-direction: column !important;
    }

    .actions-grid {
        grid-template-columns: 1fr !important;
        gap: 0.75rem !important;
    }

    .overview-card {
        padding: 1rem !important;
        gap: 0.75rem !important;
    }

    .overview-card .stat-info h3 {
        font-size: 1.5rem !important;
    }
}
</style>