<?php
/**
 * @var \App\View\AppView $this
 * @var array $stats
 * @var array $categories
 */
?>
<div class="volleyball-management-dashboard content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🏐 Volleyball Dashboard</h2>
            <p class="text-muted mb-0">Configuration des catégories d'âge et types de terrain</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('📋 Catégories', ['action' => 'categories'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('🏟️ Types', ['action' => 'types'], ['class' => 'btn btn-info']) ?>
            <?= $this->Html->link('🔗 Relations', ['action' => 'relationships'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Catégories d'âge</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['categories_count'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Types de terrain</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['types_count'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Relations actives</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['relationships_count'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Équipes inscrites</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                        try {
                            $teamsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('VolleyballTeams');
                            echo $teamsTable->find()->count();
                        } catch (Exception $e) {
                            echo '—';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">🏐 Catégories Volleyball</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                        <?php foreach (array_slice($categories, 0, 5) as $category): ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span class="badge badge-primary"><?= h($category->name) ?></span>
                                <small><?= count($category->volleyball_types ?? []) ?> types</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">Aucune catégorie configurée</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">🏟️ Types Volleyball</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($types)): ?>
                        <?php foreach (array_slice($types, 0, 5) as $type): ?>
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <span class="badge badge-info"><?= h($type->name) ?></span>
                                <small><?= $type->min_players ?>-<?= $type->max_players ?> joueurs</small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">Aucun type configuré</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>