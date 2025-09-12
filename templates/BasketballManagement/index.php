<?php
/**
 * @var \App\View\AppView $this
 * @var array $stats
 * @var array $categories
 */
?>
<div class="basketball-management-dashboard content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🏀 Basketball Dashboard</h2>
            <p class="text-muted mb-0">Configuration des catégories d'âge et types de terrain</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('📋 Catégories', ['action' => 'categories'], ['class' => 'btn btn-primary btn-modern']) ?>
            <?= $this->Html->link('🏟️ Types', ['action' => 'types'], ['class' => 'btn btn-info btn-modern']) ?>
            <?= $this->Html->link('🔗 Relations', ['action' => 'relationships'], ['class' => 'btn btn-success btn-modern']) ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Catégories d'âge
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['categories_count'] ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Types de terrain
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['types_count'] ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle info">
                                <i class="fas fa-basketball-ball"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Relations actives
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['relationships_count'] ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle success">
                                <i class="fas fa-link"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Équipes inscrites
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                // Count basketball teams
                                $teamsCount = 0;
                                try {
                                    $teamsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('BasketballTeams');
                                    $teamsCount = $teamsTable->find()->count();
                                } catch (Exception $e) {
                                    $teamsCount = '—';
                                }
                                echo $teamsCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle warning">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Categories Overview -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow modern-card">
                <div class="card-header modern-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Catégories d'âge Basketball
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Tranche d'âge</th>
                                        <th>Types associés</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($categories, 0, 5) as $category): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-primary"><?= h($category->name) ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= h($category->age_range ?? 'Non défini') ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light">
                                                    <?= count($category->basketball_types ?? []) ?> types
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($category->active): ?>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-times-circle text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center">
                            <?= $this->Html->link(
                                'Voir toutes les catégories →',
                                ['action' => 'categories'],
                                ['class' => 'btn btn-link btn-sm']
                            ) ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune catégorie configurée</p>
                            <?= $this->Html->link(
                                'Ajouter une catégorie',
                                ['action' => 'categories'],
                                ['class' => 'btn btn-primary']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Types Overview -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow modern-card">
                <div class="card-header modern-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-basketball-ball me-2"></i>Types de terrain Basketball
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($types)): ?>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Code</th>
                                        <th>Joueurs</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($types, 0, 5) as $type): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-info"><?= h($type->name) ?></span>
                                            </td>
                                            <td>
                                                <code class="small"><?= h($type->code) ?></code>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= $type->min_players ?>-<?= $type->max_players ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($type->active): ?>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-times-circle text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center">
                            <?= $this->Html->link(
                                'Voir tous les types →',
                                ['action' => 'types'],
                                ['class' => 'btn btn-link btn-sm']
                            ) ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-basketball-ball fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun type configuré</p>
                            <?= $this->Html->link(
                                'Ajouter un type',
                                ['action' => 'types'],
                                ['class' => 'btn btn-info']
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow modern-card">
                <div class="card-header modern-header">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-bolt me-2"></i>Actions rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <?= $this->Html->link(
                                '<i class="fas fa-plus-circle"></i> Nouvelle catégorie',
                                ['action' => 'categories'],
                                ['class' => 'btn btn-outline-primary btn-block', 'escape' => false]
                            ) ?>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <?= $this->Html->link(
                                '<i class="fas fa-plus-circle"></i> Nouveau type',
                                ['action' => 'types'],
                                ['class' => 'btn btn-outline-info btn-block', 'escape' => false]
                            ) ?>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <?= $this->Html->link(
                                '<i class="fas fa-link"></i> Gérer les relations',
                                ['action' => 'relationships'],
                                ['class' => 'btn btn-outline-success btn-block', 'escape' => false]
                            ) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
<?php // Include the modern styling for the dashboard ?>
.basketball-management-dashboard .modern-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    transition: all 0.3s;
}

.basketball-management-dashboard .modern-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.3);
}

.basketball-management-dashboard .modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.basketball-management-dashboard .btn-modern {
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.3s;
}

.basketball-management-dashboard .btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.basketball-management-dashboard .modern-icon-circle {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.basketball-management-dashboard .modern-icon-circle.info {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

.basketball-management-dashboard .modern-icon-circle.success {
    background: linear-gradient(135deg, #10b981, #059669);
}

.basketball-management-dashboard .modern-icon-circle.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.basketball-management-dashboard .border-left-primary {
    border-left: 0.25rem solid #3b82f6 !important;
}

.basketball-management-dashboard .border-left-info {
    border-left: 0.25rem solid #06b6d4 !important;
}

.basketball-management-dashboard .border-left-success {
    border-left: 0.25rem solid #10b981 !important;
}

.basketball-management-dashboard .border-left-warning {
    border-left: 0.25rem solid #f59e0b !important;
}
</style>