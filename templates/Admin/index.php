<?php
/**
 * @var \App\View\AppView $this
 * @var array $stats
 * @var array $recentTeams
 */

$this->assign('title', 'Administration - Dashboard');

// Add admin CSS
echo $this->Html->css('admin');
?>

<div class="admin-dashboard">
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-tachometer-alt"></i> Administration</h1>
                <p>Gestion des équipes et vérification des inscriptions</p>
            </div>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-users"></i> Gérer les équipes', ['action' => 'teams'], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-user-friends"></i> Utilisateurs', ['action' => 'users'], ['class' => 'btn btn-secondary', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-download"></i> Export', ['action' => 'exportTeams'], ['class' => 'btn btn-success', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card football">
            <div class="stat-icon">
                <i class="fas fa-futbol"></i>
            </div>
            <div class="stat-content">
                <h3>Football</h3>
                <div class="stat-number"><?= $stats['football']['total'] ?></div>
                <div class="stat-label">équipes inscrites</div>
                <div class="stat-status">
                    <span class="status-pending"><?= $stats['football']['pending'] ?> en attente</span>
                    <span class="status-verified"><?= $stats['football']['verified'] ?> vérifiées</span>
                    <span class="status-rejected"><?= $stats['football']['rejected'] ?> rejetées</span>
                </div>
                <div class="stat-recent">+<?= $stats['football']['recent'] ?> cette semaine</div>
            </div>
        </div>
        
        <div class="stat-card basketball">
            <div class="stat-icon">
                <i class="fas fa-basketball-ball"></i>
            </div>
            <div class="stat-content">
                <h3>Basketball</h3>
                <div class="stat-number"><?= $stats['basketball']['total'] ?></div>
                <div class="stat-label">équipes inscrites</div>
                <div class="stat-status">
                    <span class="status-pending"><?= $stats['basketball']['pending'] ?> en attente</span>
                    <span class="status-verified"><?= $stats['basketball']['verified'] ?> vérifiées</span>
                    <span class="status-rejected"><?= $stats['basketball']['rejected'] ?> rejetées</span>
                </div>
                <div class="stat-recent">+<?= $stats['basketball']['recent'] ?> cette semaine</div>
            </div>
        </div>
        
        <div class="stat-card handball">
            <div class="stat-icon">
                <i class="fas fa-hand-paper"></i>
            </div>
            <div class="stat-content">
                <h3>Handball</h3>
                <div class="stat-number"><?= $stats['handball']['total'] ?></div>
                <div class="stat-label">équipes inscrites</div>
                <div class="stat-status">
                    <span class="status-pending"><?= $stats['handball']['pending'] ?> en attente</span>
                    <span class="status-verified"><?= $stats['handball']['verified'] ?> vérifiées</span>
                    <span class="status-rejected"><?= $stats['handball']['rejected'] ?> rejetées</span>
                </div>
                <div class="stat-recent">+<?= $stats['handball']['recent'] ?> cette semaine</div>
            </div>
        </div>
        
        <div class="stat-card volleyball">
            <div class="stat-icon">
                <i class="fas fa-volleyball-ball"></i>
            </div>
            <div class="stat-content">
                <h3>Volleyball</h3>
                <div class="stat-number"><?= $stats['volleyball']['total'] ?></div>
                <div class="stat-label">équipes inscrites</div>
                <div class="stat-status">
                    <span class="status-pending"><?= $stats['volleyball']['pending'] ?> en attente</span>
                    <span class="status-verified"><?= $stats['volleyball']['verified'] ?> vérifiées</span>
                    <span class="status-rejected"><?= $stats['volleyball']['rejected'] ?> rejetées</span>
                </div>
                <div class="stat-recent">+<?= $stats['volleyball']['recent'] ?> cette semaine</div>
            </div>
        </div>
        
        <div class="stat-card beachvolley">
            <div class="stat-icon">
                <i class="fas fa-volleyball-ball"></i>
            </div>
            <div class="stat-content">
                <h3>Beach Volleyball</h3>
                <div class="stat-number"><?= $stats['beachvolley']['total'] ?></div>
                <div class="stat-label">équipes inscrites</div>
                <div class="stat-status">
                    <span class="status-pending"><?= $stats['beachvolley']['pending'] ?> en attente</span>
                    <span class="status-verified"><?= $stats['beachvolley']['verified'] ?> vérifiées</span>
                    <span class="status-rejected"><?= $stats['beachvolley']['rejected'] ?> rejetées</span>
                </div>
                <div class="stat-recent">+<?= $stats['beachvolley']['recent'] ?> cette semaine</div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="recent-section">
        <h2><i class="fas fa-clock"></i> Inscriptions récentes</h2>
        
        <?php if (!empty($recentTeams)): ?>
            <div class="recent-teams-table">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Nom de l'équipe</th>
                            <th>Utilisateur</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTeams as $team): ?>
                            <tr>
                                <td>
                                    <span class="sport-badge sport-<?= $team->sport_type ?>">
                                        <?= ucfirst($team->sport_type) ?>
                                    </span>
                                </td>
                                <td class="team-name"><?= h($team->nom_equipe) ?></td>
                                <td><?= h($team->user->username ?? 'N/A') ?></td>
                                <td><?= h($team->categorie) ?></td>
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
                                <td><?= $team->created->format('d/m/Y H:i') ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'teamView', $team->sport_type, $team->id],
                                        ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir détails']
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="view-all-link">
                <?= $this->Html->link('Voir toutes les équipes', ['action' => 'teams'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox fa-3x"></i>
                <p>Aucune inscription récente</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .admin-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .admin-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .admin-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2.5rem;
    }

    .admin-header p {
        margin: 0 0 2rem 0;
        opacity: 0.9;
    }

    .admin-nav {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card.football .stat-icon { background: #4CAF50; }
    .stat-card.basketball .stat-icon { background: #FF6B35; }
    .stat-card.handball .stat-icon { background: #E74C3C; }
    .stat-card.volleyball .stat-icon { background: #3498DB; }
    .stat-card.beachvolley .stat-icon { background: #F39C12; }

    .stat-content h3 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 1.1rem;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2c3e50;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 0.5rem;
    }

    .stat-recent {
        font-size: 0.8rem;
        color: #27ae60;
        font-weight: 500;
    }

    .stat-status {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        margin: 0.5rem 0;
        font-size: 0.75rem;
    }

    .stat-status span {
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        font-weight: 500;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-verified { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }

    .recent-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .recent-section h2 {
        margin: 0 0 1.5rem 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .admin-table th,
    .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .admin-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .admin-table tbody tr:hover {
        background: #f8f9fa;
    }

    .sport-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
    }

    .sport-badge.sport-football { background: #4CAF50; }
    .sport-badge.sport-basketball { background: #FF6B35; }
    .sport-badge.sport-handball { background: #E74C3C; }
    .sport-badge.sport-volleyball { background: #3498DB; }
    .sport-badge.sport-beachvolley { background: #F39C12; }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-badge.status-pending { 
        background: #fff3cd; 
        color: #856404; 
        border: 1px solid #ffeaa7;
    }
    .status-badge.status-verified { 
        background: #d4edda; 
        color: #155724; 
        border: 1px solid #c3e6cb;
    }
    .status-badge.status-rejected { 
        background: #f8d7da; 
        color: #721c24; 
        border: 1px solid #f5c6cb;
    }

    .team-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .actions {
        white-space: nowrap;
    }

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.875rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .btn-primary { background: #007bff; color: white; }
    .btn-primary:hover { background: #0056b3; color: white; }

    .btn-secondary { background: #6c757d; color: white; }
    .btn-secondary:hover { background: #545b62; color: white; }

    .btn-success { background: #28a745; color: white; }
    .btn-success:hover { background: #1e7e34; color: white; }

    .btn-info { background: #17a2b8; color: white; }
    .btn-info:hover { background: #138496; color: white; }

    .btn-outline-primary {
        background: transparent;
        color: #007bff;
        border: 1px solid #007bff;
    }
    .btn-outline-primary:hover {
        background: #007bff;
        color: white;
    }

    .view-all-link {
        text-align: center;
        margin-top: 2rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-dashboard {
            padding: 1rem;
        }

        .admin-nav {
            flex-direction: column;
            align-items: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            text-align: center;
            flex-direction: column;
        }

        .admin-table {
            font-size: 0.875rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.5rem;
        }
    }
</style>