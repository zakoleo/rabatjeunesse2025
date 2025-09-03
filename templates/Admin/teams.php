<?php
/**
 * @var \App\View\AppView $this
 * @var array $teams
 * @var string $sport
 * @var string $status
 */

$this->assign('title', 'Gestion des Équipes');
?>

<div class="admin-page admin-teams-page">
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="header-content">
            <div class="header-info">
                <h1><i class="fas fa-users"></i> Gestion des Équipes</h1>
                <p>Gérez toutes les équipes inscrites, validez les inscriptions et suivez les statuts</p>
                <div class="stats-summary">
                    <span class="stat-item">
                        <strong><?= count($teams ?? []) ?></strong> équipes affichées
                    </span>
                    <?php if (!empty($sport) && $sport !== 'all'): ?>
                        <span class="stat-item">
                            Sport: <strong><?= ucfirst($sport) ?></strong>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($status) && $status !== 'all'): ?>
                        <span class="stat-item">
                            Statut: <strong><?= ucfirst($status) ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-plus-circle"></i> Nouvelle Équipe', 
                    ['controller' => 'Teams', 'action' => 'add'], 
                    ['class' => 'btn btn-primary header-btn', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                    ['action' => 'export'], 
                    ['class' => 'btn btn-secondary header-btn', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <!-- Teams Management Section -->
    <div class="content-section teams-management-section">
        <!-- Filters -->
        <div class="widget-header">
            <h3><i class="fas fa-filter"></i> Filtres et Recherche</h3>
            <div class="widget-actions">
                <div class="filter-group">
                    <select id="sport-filter" class="form-control">
                        <option value="all" <?= $sport === 'all' ? 'selected' : '' ?>>Tous les sports</option>
                        <option value="football" <?= $sport === 'football' ? 'selected' : '' ?>>Football</option>
                        <option value="basketball" <?= $sport === 'basketball' ? 'selected' : '' ?>>Basketball</option>
                        <option value="handball" <?= $sport === 'handball' ? 'selected' : '' ?>>Handball</option>
                        <option value="volleyball" <?= $sport === 'volleyball' ? 'selected' : '' ?>>Volleyball</option>
                        <option value="beachvolley" <?= $sport === 'beachvolley' ? 'selected' : '' ?>>Beach Volleyball</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <select id="status-filter" class="form-control">
                        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Tous les statuts</option>
                        <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="verified" <?= $status === 'verified' ? 'selected' : '' ?>>Vérifiée</option>
                        <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejetée</option>
                    </select>
                </div>
                
                <button id="apply-filters" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter"></i> Appliquer
                </button>
            </div>
        </div>

        <?php if (empty($teams)): ?>
            <div class="empty-state">
                <i class="fas fa-users fa-4x"></i>
                <h3>Aucune équipe trouvée</h3>
                <p>Aucune équipe ne correspond aux filtres sélectionnés.</p>
                <?= $this->Html->link('<i class="fas fa-plus"></i> Ajouter une équipe', 
                    ['controller' => 'Teams', 'action' => 'add'], 
                    ['class' => 'btn btn-primary', 'escape' => false]) ?>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-users"></i> Équipe</th>
                            <th><i class="fas fa-futbol"></i> Sport</th>
                            <th><i class="fas fa-user"></i> Responsable</th>
                            <th><i class="fas fa-calendar"></i> Inscription</th>
                            <th><i class="fas fa-flag"></i> Statut</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teams as $team): ?>
                            <tr class="team-row" data-team-id="<?= $team['id'] ?>" data-sport="<?= h(strtolower(str_replace(' ', '', $team['sport']))) ?>">
                                <td class="team-info">
                                    <div class="team-details">
                                        <div class="team-name"><?= h($team['nom_equipe']) ?></div>
                                        <small class="team-id">ID: <?= $team['id'] ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="sport-badge sport-<?= strtolower(str_replace(' ', '', $team['sport'])) ?>">
                                        <?= h($team['sport']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <?= h($team['user']['username'] ?? 'Non assigné') ?>
                                    </div>
                                </td>
                                <td class="date-info">
                                    <div class="date"><?= $team['created']->format('d/m/Y') ?></div>
                                    <small class="time"><?= $team['created']->format('H:i') ?></small>
                                </td>
                                <td>
                                    <span class="status-badge <?= $team['status'] ?? 'pending' ?>">
                                        <?php
                                        $statusLabels = [
                                            'pending' => 'En attente',
                                            'verified' => 'Vérifiée',
                                            'rejected' => 'Rejetée'
                                        ];
                                        echo $statusLabels[$team['status'] ?? 'pending'] ?? 'Inconnu';
                                        ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <select class="status-select form-control" data-team-id="<?= $team['id'] ?>" data-sport="<?= h(strtolower(str_replace(' ', '', $team['sport']))) ?>">
                                        <option value="pending" <?= ($team['status'] ?? 'pending') === 'pending' ? 'selected' : '' ?>>En attente</option>
                                        <option value="verified" <?= ($team['status'] ?? 'pending') === 'verified' ? 'selected' : '' ?>>Vérifier</option>
                                        <option value="rejected" <?= ($team['status'] ?? 'pending') === 'rejected' ? 'selected' : '' ?>>Rejeter</option>
                                    </select>
                                    <button class="btn btn-sm btn-info view-team" data-team-id="<?= $team['id'] ?>" data-sport="<?= h(strtolower(str_replace(' ', '', $team['sport']))) ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
    </tbody>
</table>
            </div>
        <?php endif; ?>
    </div>
</div>

