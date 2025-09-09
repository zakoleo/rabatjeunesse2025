<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Team> $footballTeams
 * @var iterable<\App\Model\Entity\BasketballTeam> $basketballTeams
 * @var iterable<\App\Model\Entity\HandballTeam> $handballTeams
 * @var iterable<\App\Model\Entity\VolleyballTeam> $volleyballTeams
 */
?>
<div class="page-header">
    <div class="container">
        <h1>Mes équipes</h1>
        <p>Gérez vos équipes inscrites aux tournois de football, basketball, handball et volleyball</p>
    </div>
</div>

<div class="content-section">
    <div class="container">
        <div class="actions mb-4">
            <?= $this->Html->link('Inscrire nouvel équipe', ['controller' => 'Sports', 'action' => 'index'], ['class' => 'btn btn-primary btn-large']) ?>
        </div>
        
        <!-- Football Teams Section -->
        <?php if (count($footballTeams) > 0): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h3>Équipes de Football (<?= count($footballTeams) ?>)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Nom de l'équipe</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th>District</th>
                                <th>Date d'inscription</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($footballTeams as $team): ?>
                            <tr>
                                <td>
                                    <span class="reference-badge football"><?= h($team->reference_inscription ?? 'FB-TEMP') ?></span>
                                </td>
                                <td>
                                    <strong><?= h($team->nom_equipe) ?></strong>
                                </td>
                                <td>
                                    <?= h($team->categorie) ?>
                                </td>
                                <td>
                                    <span class="badge badge-football"><?= h($team->type_football) ?></span>
                                </td>
                                <td>
                                    <?= h($team->district) ?>
                                </td>
                                <td>
                                    <?= h($team->created->format('d/m/Y')) ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Voir', ['action' => 'view', $team->id], ['class' => 'btn btn-sm btn-info', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Modifier', ['action' => 'edit', $team->id], ['class' => 'btn btn-sm btn-warning', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> PDF', ['action' => 'downloadPdf', $team->id], ['class' => 'btn btn-sm btn-success', 'escape' => false, 'title' => 'Télécharger le PDF']) ?>
                                    <?= $this->Form->postLink('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>', ['action' => 'delete', $team->id], [
                                        'confirm' => __('Êtes-vous sûr de vouloir supprimer l\'équipe {0}?', $team->nom_equipe),
                                        'class' => 'btn btn-sm btn-danger',
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

        <!-- Basketball Teams Section -->
        <?php if (count($basketballTeams) > 0): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h3>Équipes de Basketball (<?= count($basketballTeams) ?>)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Nom de l'équipe</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th>District</th>
                                <th>Joueurs</th>
                                <th>Date d'inscription</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($basketballTeams as $team): ?>
                            <tr>
                                <td>
                                    <span class="reference-badge basketball"><?= h($team->reference_inscription ?? 'BB-TEMP') ?></span>
                                </td>
                                <td>
                                    <strong><?= h($team->nom_equipe) ?></strong>
                                </td>
                                <td>
                                    <?= h($team->categorie) ?>
                                </td>
                                <td>
                                    <span class="badge badge-basketball"><?= h($team->type_basketball) ?></span>
                                </td>
                                <td>
                                    <?= h($team->district) ?>
                                </td>
                                <td>
                                    <span class="player-count">
                                        <?= count($team->basketball_teams_joueurs ?? []) ?>
                                        <?php 
                                        $limits = ['3x3' => ['min' => 3, 'max' => 4], '5x5' => ['min' => 5, 'max' => 8]];
                                        $type = $team->type_basketball;
                                        if (isset($limits[$type])): 
                                            $playerCount = count($team->basketball_teams_joueurs ?? []);
                                            $min = $limits[$type]['min'];
                                            $max = $limits[$type]['max'];
                                            $status = $playerCount >= $min && $playerCount <= $max ? 'valid' : 'invalid';
                                        ?>
                                        <small class="text-<?= $status === 'valid' ? 'success' : 'danger' ?>">
                                            (<?= $min ?>-<?= $max ?>)
                                        </small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <?= h($team->created->format('d/m/Y')) ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Voir', ['action' => 'basketballTeamView', $team->id], ['class' => 'btn btn-sm btn-info', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Modifier', ['action' => 'editBasketball', $team->id], ['class' => 'btn btn-sm btn-warning', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> PDF', ['action' => 'downloadBasketballPdf', $team->id], ['class' => 'btn btn-sm btn-success', 'escape' => false, 'title' => 'Télécharger le PDF']) ?>
                                    <?= $this->Form->postLink('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>', ['action' => 'deleteBasketball', $team->id], [
                                        'confirm' => __('Êtes-vous sûr de vouloir supprimer l\'équipe {0}?', $team->nom_equipe),
                                        'class' => 'btn btn-sm btn-danger',
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

        <!-- Handball Teams Section -->
        <?php if (count($handballTeams) > 0): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h3>Équipes de Handball (<?= count($handballTeams) ?>)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Nom de l'équipe</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th>District</th>
                                <th>Joueurs</th>
                                <th>Date d'inscription</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($handballTeams as $team): ?>
                            <tr>
                                <td>
                                    <span class="reference-badge handball"><?= h($team->reference_inscription ?? 'HB-TEMP') ?></span>
                                </td>
                                <td>
                                    <strong><?= h($team->nom_equipe) ?></strong>
                                </td>
                                <td>
                                    <?= h($team->categorie) ?>
                                </td>
                                <td>
                                    <span class="badge badge-handball"><?= h($team->type_handball) ?></span>
                                </td>
                                <td>
                                    <?= h($team->district) ?>
                                </td>
                                <td>
                                    <span class="player-count">
                                        <?= count($team->handball_teams_joueurs ?? []) ?>
                                        <?php 
                                        $limits = ['7x7' => ['min' => 7, 'max' => 12], '5x5' => ['min' => 5, 'max' => 8]];
                                        $type = $team->type_handball;
                                        if (isset($limits[$type])): 
                                            $playerCount = count($team->handball_teams_joueurs ?? []);
                                            $min = $limits[$type]['min'];
                                            $max = $limits[$type]['max'];
                                            $status = $playerCount >= $min && $playerCount <= $max ? 'valid' : 'invalid';
                                        ?>
                                        <small class="text-<?= $status === 'valid' ? 'success' : 'danger' ?>">
                                            (<?= $min ?>-<?= $max ?>)
                                        </small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <?= h($team->created->format('d/m/Y')) ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Voir', ['action' => 'handballTeamView', $team->id], ['class' => 'btn btn-sm btn-info', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Modifier', ['action' => 'editHandball', $team->id], ['class' => 'btn btn-sm btn-warning', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> PDF', ['action' => 'downloadHandballPdf', $team->id], ['class' => 'btn btn-sm btn-success', 'escape' => false, 'title' => 'Télécharger le PDF']) ?>
                                    <?= $this->Form->postLink('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>', ['action' => 'deleteHandball', $team->id], [
                                        'confirm' => __('Êtes-vous sûr de vouloir supprimer l\'équipe {0}?', $team->nom_equipe),
                                        'class' => 'btn btn-sm btn-danger',
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

        <!-- Volleyball Teams Section -->
        <?php if (count($volleyballTeams) > 0): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h3>Équipes de Volleyball (<?= count($volleyballTeams) ?>)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Nom de l'équipe</th>
                                <th>Catégorie</th>
                                <th>Type</th>
                                <th>District</th>
                                <th>Joueurs</th>
                                <th>Date d'inscription</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($volleyballTeams as $team): ?>
                            <tr>
                                <td>
                                    <span class="reference-badge volleyball"><?= h($team->reference_inscription ?? 'VB-TEMP') ?></span>
                                </td>
                                <td>
                                    <strong><?= h($team->nom_equipe) ?></strong>
                                </td>
                                <td>
                                    <?= h($team->categorie) ?>
                                </td>
                                <td>
                                    <span class="badge badge-volleyball">
                                        <?= h($team->type_volleyball ?? 'N/A') ?>
                                        <?php 
                                        $type = $team->type_volleyball ?? '';
                                        if ($type === '6x6') {
                                            $min = 6; $max = 12;
                                        } elseif ($type === '4x4') {
                                            $min = 4; $max = 8;
                                        } else {
                                            $min = $max = null;
                                        }
                                        ?>
                                        <?php if ($min && $max): ?>
                                        <small class="d-block text-muted">
                                            (<?= $min ?>-<?= $max ?>)
                                        </small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <?= h($team->district) ?>
                                </td>
                                <td>
                                    <span class="badge badge-light">
                                        <?= count($team->volleyball_teams_joueurs ?? []) ?> joueurs
                                    </span>
                                </td>
                                <td>
                                    <?= h($team->created->format('d/m/Y')) ?>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg> Voir', ['action' => 'volleyballTeamView', $team->id], ['class' => 'btn btn-sm btn-info', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Modifier', ['action' => 'editVolleyball', $team->id], ['class' => 'btn btn-sm btn-warning', 'escape' => false]) ?>
                                    <?= $this->Html->link('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> PDF', ['action' => 'downloadVolleyballPdf', $team->id], ['class' => 'btn btn-sm btn-success', 'escape' => false, 'title' => 'Télécharger le PDF']) ?>
                                    <?= $this->Form->postLink('<svg class="icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path></svg>', ['action' => 'deleteVolleyball', $team->id], [
                                        'confirm' => __('Êtes-vous sûr de vouloir supprimer l\'équipe {0}?', $team->nom_equipe),
                                        'class' => 'btn btn-sm btn-danger',
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

        <!-- Empty State -->
        <?php if (count($footballTeams) === 0 && count($basketballTeams) === 0 && count($handballTeams) === 0 && count($volleyballTeams) === 0): ?>
        <div class="card">
            <div class="card-body text-center">
                <div class="empty-state">
                    <svg class="icon-large mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    <h3>Aucune équipe inscrite</h3>
                    <p class="text-light">Vous n'avez pas encore inscrit d'équipe.</p>
                    <div class="mt-3">
                        <?= $this->Html->link('Inscrire nouvel équipe', ['controller' => 'Sports', 'action' => 'index'], ['class' => 'btn btn-primary btn-large']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .actions {
        margin-bottom: 2rem;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 20px;
    }
    
    .badge-football {
        background-color: var(--info-color);
        color: white;
    }
    
    .badge-basketball {
        background-color: #FF6B35;
        color: white;
    }
    
    .badge-handball {
        background-color: #D2691E;
        color: white;
    }
    
    .btn-basketball {
        background-color: #FF6B35;
        color: white;
        border: none;
    }
    
    .btn-basketball:hover {
        background-color: #e55a2b;
        color: white;
    }
    
    .btn-handball {
        background-color: #D2691E;
        color: white;
        border: none;
    }
    
    .btn-handball:hover {
        background-color: #b5591a;
        color: white;
    }
    
    .empty-state {
        padding: 3rem 0;
    }
    
    .icon-large {
        width: 80px;
        height: 80px;
        color: var(--text-light);
    }
    
    .empty-state h3 {
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        font-size: 1.125rem;
    }
    
    table .actions {
        white-space: nowrap;
    }
    
    table .actions .btn {
        margin-right: 0.25rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-info {
        background-color: var(--info-color);
        color: white;
    }
    
    .btn-info:hover {
        background-color: #2980b9;
    }
    
    .btn-warning {
        background-color: var(--warning-color);
        color: white;
    }
    
    .btn-warning:hover {
        background-color: #e67e22;
    }
    
    .btn-success {
        background-color: var(--success-color);
        color: white;
    }
    
    .btn-success:hover {
        background-color: #27ae60;
    }
    
    .reference-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: monospace;
        font-weight: 600;
        font-size: 0.875rem;
        white-space: nowrap;
    }
    
    .reference-badge.football {
        background: #E8F4F8;
        color: #2C3E50;
    }
    
    .reference-badge.basketball {
        background: #FFE8DC;
        color: #FF6B35;
    }
    
    .reference-badge.handball {
        background: #FDF0E8;
        color: #D2691E;
    }
    
    .player-count {
        font-weight: 600;
    }
    
    .card-header h3 {
        margin: 0;
        color: var(--text-dark);
        font-size: 1.2rem;
    }
    
    .mb-4 {
        margin-bottom: 1.5rem;
    }
    
    .me-2 {
        margin-right: 0.5rem;
    }
    
    .icon-sm {
        width: 16px;
        height: 16px;
        vertical-align: text-bottom;
        margin-right: 4px;
    }
    
    /* Pagination Styles */
    .paginator {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #E0E0E0;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
        align-items: center;
    }
    
    .pagination li {
        margin: 0;
    }
    
    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        text-decoration: none;
        color: var(--text-dark);
        background: white;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination a:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .pagination .active span {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        font-weight: 600;
    }
    
    .pagination .disabled {
        opacity: 0.5;
        pointer-events: none;
    }
    
    .pagination .disabled a {
        color: #B0B0B0;
        cursor: not-allowed;
    }
    
    .pagination-icon {
        width: 18px;
        height: 18px;
    }
    
    .pagination .first a,
    .pagination .last a {
        padding: 0 8px;
    }
    
    .pagination-info {
        text-align: center;
        color: var(--text-light);
        font-size: 0.9rem;
        margin: 0;
    }
    
    .pagination-info strong {
        color: var(--text-dark);
        font-weight: 600;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .pagination {
            gap: 0.25rem;
        }
        
        .pagination a,
        .pagination span {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            font-size: 0.875rem;
        }
        
        .pagination-icon {
            width: 16px;
            height: 16px;
        }
        
        .pagination .ellipsis {
            display: none;
        }
    }
</style>