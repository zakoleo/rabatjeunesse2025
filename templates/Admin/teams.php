<?php
/**
 * @var \App\View\AppView $this
 * @var array $teams
 * @var string $sport
 * @var string $status
 */

$this->assign('title', 'Gestion des Équipes');
?>

<div class="page-header">
    <div class="container">
        <h1>Gestion des équipes</h1>
        <p>Administrez toutes les équipes inscrites aux tournois de football, basketball, handball, volleyball et beach volleyball</p>
    </div>
</div>

<div class="content-section">
    <div class="container">
        <div class="actions mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex gap-2">
                        <select id="sport-filter" class="form-control" style="width: 200px;">
                            <option value="all" <?= $sport === 'all' ? 'selected' : '' ?>>Tous les sports</option>
                            <option value="football" <?= $sport === 'football' ? 'selected' : '' ?>>Football</option>
                            <option value="basketball" <?= $sport === 'basketball' ? 'selected' : '' ?>>Basketball</option>
                            <option value="handball" <?= $sport === 'handball' ? 'selected' : '' ?>>Handball</option>
                            <option value="volleyball" <?= $sport === 'volleyball' ? 'selected' : '' ?>>Volleyball</option>
                            <option value="beachvolley" <?= $sport === 'beachvolley' ? 'selected' : '' ?>>Beach Volleyball</option>
                        </select>
                        
                        <select id="status-filter" class="form-control" style="width: 180px;">
                            <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Tous les statuts</option>
                            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="verified" <?= $status === 'verified' ? 'selected' : '' ?>>Vérifiée</option>
                            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejetée</option>
                        </select>
                        
                        <button id="apply-filters" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Appliquer
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <?= $this->Html->link('<i class="fas fa-plus-circle"></i> Nouvelle Équipe', 
                        ['controller' => 'Teams', 'action' => 'add'], 
                        ['class' => 'btn btn-success', 'escape' => false]) ?>
                </div>
            </div>
        </div>

        <?php 
        // Organize teams by sport
        $teamsBySport = [];
        foreach ($teams ?? [] as $team) {
            $sportName = strtolower(str_replace(' ', '', $team['sport']));
            if (!isset($teamsBySport[$sportName])) {
                $teamsBySport[$sportName] = [];
            }
            $teamsBySport[$sportName][] = $team;
        }
        
        $sportConfigs = [
            'football' => ['name' => 'Football', 'icon' => 'futbol', 'color' => 'football'],
            'basketball' => ['name' => 'Basketball', 'icon' => 'basketball-ball', 'color' => 'basketball'],
            'handball' => ['name' => 'Handball', 'icon' => 'hand-paper', 'color' => 'handball'],
            'volleyball' => ['name' => 'Volleyball', 'icon' => 'volleyball-ball', 'color' => 'volleyball'],
            'beachvolley' => ['name' => 'Beach Volleyball', 'icon' => 'umbrella-beach', 'color' => 'beachvolley']
        ];
        ?>
        
        <?php if (empty($teams)): ?>
            <div class="empty-state text-center py-5">
                <i class="fas fa-users fa-4x text-muted"></i>
                <h3 class="mt-3">Aucune équipe trouvée</h3>
                <p class="text-muted">Aucune équipe ne correspond aux filtres sélectionnés.</p>
                <?= $this->Html->link('<i class="fas fa-plus"></i> Ajouter une équipe', 
                    ['controller' => 'Teams', 'action' => 'add'], 
                    ['class' => 'btn btn-primary btn-lg mt-3', 'escape' => false]) ?>
            </div>
        <?php else: ?>
            <?php if ($sport !== 'all'): ?>
                <!-- Single sport view -->
                <?php $sportKey = $sport; ?>
                <?php if (isset($teamsBySport[$sportKey]) && count($teamsBySport[$sportKey]) > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3><i class="fas fa-<?= $sportConfigs[$sportKey]['icon'] ?>"></i> <?= $sportConfigs[$sportKey]['name'] ?> (<?= count($teamsBySport[$sportKey]) ?>)</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Référence</th>
                                            <th>Nom de l'équipe</th>
                                            <th>Responsable</th>
                                            <th>Catégorie</th>
                                            <th>Type</th>
                                            <th>District</th>
                                            <th>Date d'inscription</th>
                                            <th>Statut</th>
                                            <th class="actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($teamsBySport[$sportKey] as $team): ?>
                                        <tr>
                                            <td>
                                                <span class="reference-badge <?= $sportConfigs[$sportKey]['color'] ?>"><?= h($team['reference'] ?? strtoupper($sportKey[0]) . '-' . $team['id']) ?></span>
                                            </td>
                                            <td>
                                                <strong><?= h($team['nom_equipe']) ?></strong>
                                            </td>
                                            <td>
                                                <?= h($team['user']['username'] ?? 'Non assigné') ?>
                                            </td>
                                            <td>
                                                <?= h($team['categorie'] ?? $team['category'] ?? 'N/A') ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $sportConfigs[$sportKey]['color'] ?>"><?= h($team['type_football'] ?? $team['type'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <?= h($team['district'] ?? 'N/A') ?>
                                            </td>
                                            <td>
                                                <?= $team['created']->format('d/m/Y') ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'secondary';
                                                $statusText = 'En attente';
                                                switch($team['status'] ?? 'pending') {
                                                    case 'verified':
                                                        $statusClass = 'success';
                                                        $statusText = 'Vérifiée';
                                                        break;
                                                    case 'rejected':
                                                        $statusClass = 'danger';
                                                        $statusText = 'Rejetée';
                                                        break;
                                                    case 'pending':
                                                        $statusClass = 'warning';
                                                        $statusText = 'En attente';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td class="actions">
                                                <button class="btn btn-xs btn-info view-team" data-team-id="<?= $team['id'] ?>" data-sport="<?= h($sportKey) ?>" title="Visualiser">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?= $this->Form->postLink('<i class="fas fa-trash"></i>', 
                                                    ['action' => 'deleteTeam', $team['id'], '?' => ['sport' => $sportKey]], 
                                                    [
                                                        'confirm' => 'Êtes-vous sûr de vouloir supprimer l\'équipe ' . $team['nom_equipe'] . '?',
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'escape' => false,
                                                        'title' => 'Supprimer'
                                                    ]) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- All sports view -->
                <?php foreach ($sportConfigs as $sportKey => $config): ?>
                    <?php if (isset($teamsBySport[$sportKey]) && count($teamsBySport[$sportKey]) > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3><i class="fas fa-<?= $config['icon'] ?>"></i> Équipes de <?= $config['name'] ?> (<?= count($teamsBySport[$sportKey]) ?>)</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Référence</th>
                                            <th>Nom de l'équipe</th>
                                            <th>Responsable</th>
                                            <th>Catégorie</th>
                                            <th>Type</th>
                                            <th>District</th>
                                            <th>Date d'inscription</th>
                                            <th>Statut</th>
                                            <th class="actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($teamsBySport[$sportKey] as $team): ?>
                                        <tr>
                                            <td>
                                                <span class="reference-badge <?= $config['color'] ?>"><?= h($team['reference'] ?? strtoupper($sportKey[0]) . '-' . $team['id']) ?></span>
                                            </td>
                                            <td>
                                                <strong><?= h($team['nom_equipe']) ?></strong>
                                            </td>
                                            <td>
                                                <?= h($team['user']['username'] ?? 'Non assigné') ?>
                                            </td>
                                            <td>
                                                <?= h($team['categorie'] ?? $team['category'] ?? 'N/A') ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $config['color'] ?>"><?= h($team['type_football'] ?? $team['type'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <?= h($team['district'] ?? 'N/A') ?>
                                            </td>
                                            <td>
                                                <?= $team['created']->format('d/m/Y') ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = 'secondary';
                                                $statusText = 'En attente';
                                                switch($team['status'] ?? 'pending') {
                                                    case 'verified':
                                                        $statusClass = 'success';
                                                        $statusText = 'Vérifiée';
                                                        break;
                                                    case 'rejected':
                                                        $statusClass = 'danger';
                                                        $statusText = 'Rejetée';
                                                        break;
                                                    case 'pending':
                                                        $statusClass = 'warning';
                                                        $statusText = 'En attente';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td class="actions">
                                                <button class="btn btn-xs btn-info view-team" data-team-id="<?= $team['id'] ?>" data-sport="<?= h($sportKey) ?>" title="Visualiser">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <?= $this->Form->postLink('<i class="fas fa-trash"></i>', 
                                                    ['action' => 'deleteTeam', $team['id'], '?' => ['sport' => $sportKey]], 
                                                    [
                                                        'confirm' => 'Êtes-vous sûr de vouloir supprimer l\'équipe ' . $team['nom_equipe'] . '?',
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'escape' => false,
                                                        'title' => 'Supprimer'
                                                    ]) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const applyFiltersBtn = document.getElementById('apply-filters');
    const sportFilter = document.getElementById('sport-filter');
    const statusFilter = document.getElementById('status-filter');
    
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            const sport = sportFilter.value;
            const status = statusFilter.value;
            
            let url = '/admin/teams?';
            if (sport !== 'all') {
                url += 'sport=' + sport + '&';
            }
            if (status !== 'all') {
                url += 'status=' + status;
            }
            
            // Remove trailing & or ?
            url = url.replace(/[&?]$/, '');
            
            window.location.href = url;
        });
    }
    
    // View team functionality
    document.querySelectorAll('.view-team').forEach(button => {
        button.addEventListener('click', function() {
            const teamId = this.getAttribute('data-team-id');
            const sport = this.getAttribute('data-sport');
            
            window.location.href = '/admin/view-team/' + sport + '/' + teamId;
        });
    });
    
    // Status change functionality removed - statuses are now display-only labels
});
</script>

<style>
/* Page header styling */
.page-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.page-header p {
    font-size: 1.25rem;
    opacity: 0.9;
}

/* Content section */
.content-section {
    padding: 2rem 0;
}

/* Card styling */
.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem;
}

.card-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.card-body {
    padding: 0;
}

/* Table styling */
.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #dee2e6;
}

/* Reference badges */
.reference-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Status badges */
.badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

.badge-secondary {
    background-color: #6c757d;
    color: #fff;
}

/* Extra small buttons */
.btn-xs {
    padding: 0.25rem 0.4rem !important;
    font-size: 0.75rem !important;
    line-height: 1.2 !important;
    border-radius: 0.2rem !important;
}

/* Make action buttons more compact */
.actions .btn {
    margin: 0 2px;
}

.reference-badge.football {
    background: #e3f2fd;
    color: #1976d2;
}

.reference-badge.basketball {
    background: #fff3e0;
    color: #f57c00;
}

.reference-badge.handball {
    background: #f3e5f5;
    color: #7b1fa2;
}

.reference-badge.volleyball {
    background: #e8f5e9;
    color: #388e3c;
}

.reference-badge.beachvolley {
    background: #fff8e1;
    color: #fbc02d;
}

/* Sport badges */
.badge-football {
    background: #1976d2;
    color: white;
}

.badge-basketball {
    background: #f57c00;
    color: white;
}

.badge-handball {
    background: #7b1fa2;
    color: white;
}

.badge-volleyball {
    background: #388e3c;
    color: white;
}

.badge-beachvolley {
    background: #fbc02d;
    color: #333;
}

/* Status select */
.status-select {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Actions column */
.actions {
    white-space: nowrap;
    text-align: center;
}

.actions .btn {
    margin: 0 0.25rem;
}

/* Filter controls */
.d-flex.gap-2 {
    gap: 0.5rem;
}

/* Empty state */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state i {
    color: #6c757d;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #343a40;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 2rem;
}

/* Icon styles */
.fas {
    margin-right: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 2rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .table {
        font-size: 0.875rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
    }
    
    .actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>

