<?php
/**
 * @var \App\View\AppView $this
 * @var array $teams
 * @var string $sport
 * @var string $status
 */

$this->assign('title', 'Administration - Gestion des équipes');

// Add admin CSS
echo $this->Html->css('admin');
?>

<div class="admin-teams">
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-users"></i> Gestion des équipes</h1>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-tachometer-alt"></i> Dashboard', ['action' => 'index'], ['class' => 'btn btn-secondary', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-download"></i> Export', ['action' => 'exportTeams', $sport], ['class' => 'btn btn-success', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-card">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
            
            <div class="filter-group">
                <label>Sport</label>
                <?= $this->Form->select('sport', [
                    'all' => 'Tous les sports',
                    'football' => 'Football',
                    'basketball' => 'Basketball',
                    'handball' => 'Handball',
                    'volleyball' => 'Volleyball',
                    'beachvolley' => 'Beach Volleyball'
                ], ['value' => $sport, 'class' => 'form-control']) ?>
            </div>
            
            <div class="filter-group">
                <label>Statut</label>
                <?= $this->Form->select('status', [
                    'all' => 'Tous les statuts',
                    'pending' => 'En attente',
                    'verified' => 'Vérifiées',
                    'rejected' => 'Rejetées'
                ], ['value' => $status, 'class' => 'form-control']) ?>
            </div>
            
            <div class="filter-actions">
                <?= $this->Form->button('<i class="fas fa-filter"></i> Filtrer', ['class' => 'btn btn-primary', 'escape' => false]) ?>
                <?= $this->Html->link('Reset', ['action' => 'teams'], ['class' => 'btn btn-outline-secondary']) ?>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>

    <!-- Teams Table -->
    <div class="teams-section">
        <div class="section-header">
            <h2>Équipes inscrites (<?= is_array($teams) ? count($teams) : 0 ?>)</h2>
        </div>
        
        <?php if (!empty($teams)): ?>
            <div class="teams-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Nom de l'équipe</th>
                            <th>Responsable</th>
                            <th>Catégorie</th>
                            <th>Genre</th>
                            <th>District</th>
                            <th>Joueurs</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $team): ?>
                            <tr class="team-row">
                                <td>
                                    <span class="sport-badge sport-<?= $team->sport_type ?>">
                                        <?= ucfirst($team->sport_type) ?>
                                    </span>
                                </td>
                                <td class="team-name">
                                    <strong><?= h($team->nom_equipe) ?></strong>
                                    <?php if (!empty($team->reference_inscription)): ?>
                                        <br><small class="text-muted">Ref: <?= h($team->reference_inscription) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($team->responsable_nom_complet)): ?>
                                        <div><?= h($team->responsable_nom_complet) ?></div>
                                        <?php if (!empty($team->responsable_tel)): ?>
                                            <small class="text-muted"><?= h($team->responsable_tel) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Non renseigné</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($team->categorie) ?></td>
                                <td><?= h($team->genre) ?></td>
                                <td><?= h($team->district) ?></td>
                                <td>
                                    <?php
                                    $playerCount = 0;
                                    if (isset($team->basketball_teams_joueurs)) {
                                        $playerCount = count($team->basketball_teams_joueurs);
                                    } elseif (isset($team->handball_teams_joueurs)) {
                                        $playerCount = count($team->handball_teams_joueurs);
                                    } elseif (isset($team->volleyball_teams_joueurs)) {
                                        $playerCount = count($team->volleyball_teams_joueurs);
                                    } elseif (isset($team->beachvolley_teams_joueurs)) {
                                        $playerCount = count($team->beachvolley_teams_joueurs);
                                    } elseif (isset($team->joueurs)) {
                                        $playerCount = count($team->joueurs);
                                    }
                                    ?>
                                    <span class="player-count"><?= $playerCount ?> joueurs</span>
                                </td>
                                <td>
                                    <div><?= $team->created->format('d/m/Y') ?></div>
                                    <small class="text-muted"><?= $team->created->format('H:i') ?></small>
                                </td>
                                <td>
                                    <?php
                                    $status = $team->status ?? 'pending';
                                    $statusConfig = [
                                        'pending' => ['icon' => 'fas fa-clock', 'label' => 'En attente', 'class' => 'status-pending'],
                                        'verified' => ['icon' => 'fas fa-check-circle', 'label' => 'Vérifiée', 'class' => 'status-verified'],
                                        'rejected' => ['icon' => 'fas fa-times-circle', 'label' => 'Rejetée', 'class' => 'status-rejected']
                                    ];
                                    $config = $statusConfig[$status];
                                    ?>
                                    <span class="status-badge <?= $config['class'] ?>">
                                        <i class="<?= $config['icon'] ?>"></i> <?= $config['label'] ?>
                                    </span>
                                    <?php if (!empty($team->verified_at)): ?>
                                        <br><small class="text-muted">
                                            <?= $team->verified_at->format('d/m/Y H:i') ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'teamView', $team->sport_type, $team->id],
                                        ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir détails']
                                    ) ?>
                                    
                                    <?php $teamStatus = $team->status ?? 'pending'; ?>
                                    <?php if ($teamStatus === 'pending'): ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-check"></i>',
                                            ['action' => 'verifyTeam', $team->sport_type, $team->id],
                                            [
                                                'class' => 'btn btn-sm btn-success',
                                                'escape' => false,
                                                'title' => 'Vérifier',
                                                'confirm' => 'Confirmer la vérification de cette équipe ?'
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-times"></i>',
                                            ['action' => 'rejectTeam', $team->sport_type, $team->id],
                                            [
                                                'class' => 'btn btn-sm btn-danger',
                                                'escape' => false,
                                                'title' => 'Rejeter',
                                                'confirm' => 'Confirmer le rejet de cette équipe ?'
                                            ]
                                        ) ?>
                                    <?php else: ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-undo"></i>',
                                            ['action' => 'resetTeamStatus', $team->sport_type, $team->id],
                                            [
                                                'class' => 'btn btn-sm btn-warning',
                                                'escape' => false,
                                                'title' => 'Réinitialiser',
                                                'confirm' => 'Réinitialiser le statut de cette équipe ?'
                                            ]
                                        ) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-search fa-3x"></i>
                <h3>Aucune équipe trouvée</h3>
                <p>Aucune équipe ne correspond aux critères sélectionnés.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .admin-teams {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-content h1 {
        margin: 0;
        font-size: 2rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .filters-section {
        margin-bottom: 2rem;
    }

    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .filters-form {
        display: flex;
        align-items: end;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-group {
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.875rem;
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    .teams-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .section-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #dee2e6;
        background: #f8f9fa;
    }

    .section-header h2 {
        margin: 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .teams-table-container {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .admin-table th,
    .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .admin-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        white-space: nowrap;
    }

    .team-row:hover {
        background: #f8f9fa;
    }

    .sport-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sport-badge.sport-football { background: #4CAF50; }
    .sport-badge.sport-basketball { background: #FF6B35; }
    .sport-badge.sport-handball { background: #E74C3C; }
    .sport-badge.sport-volleyball { background: #3498DB; }
    .sport-badge.sport-beachvolley { background: #F39C12; }

    .team-name strong {
        color: #2c3e50;
    }

    .text-muted {
        color: #6c757d !important;
        font-size: 0.8rem;
    }

    .player-count {
        background: #e9ecef;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        color: #495057;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-verified {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .actions {
        white-space: nowrap;
    }

    .actions .btn {
        margin-right: 0.25rem;
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
        line-height: 1;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
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

    .btn-danger { background: #dc3545; color: white; }
    .btn-danger:hover { background: #c82333; color: white; }

    .btn-warning { background: #ffc107; color: #212529; }
    .btn-warning:hover { background: #e0a800; color: #212529; }

    .btn-outline-secondary {
        background: transparent;
        color: #6c757d;
        border: 1px solid #6c757d;
    }
    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    .empty-state i {
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        margin-bottom: 1rem;
        color: #495057;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-teams {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .filters-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            min-width: auto;
        }

        .admin-table {
            font-size: 0.75rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.5rem;
        }
    }
</style>