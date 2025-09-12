<?php
/**
 * @var \App\View\AppView $this
 * @var array $types
 */
?>
<div class="basketball-types content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🏀 Basketball Types</h2>
            <p class="text-muted mb-0">Gestion des types de terrain pour le basketball</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('🏀 Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <!-- Types List -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-info">Types de terrain Basketball</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($types)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Joueurs Min</th>
                                <th>Joueurs Max</th>
                                <th>Catégories associées</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $type): ?>
                                <tr>
                                    <td><span class="badge badge-info"><?= h($type->name) ?></span></td>
                                    <td><code><?= h($type->code) ?></code></td>
                                    <td><?= $type->min_players ?></td>
                                    <td><?= $type->max_players ?></td>
                                    <td>
                                        <span class="badge badge-light">
                                            <?= count($type->basketball_categories ?? []) ?> catégories
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($type->active): ?>
                                            <span class="badge badge-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= $type->created ? (is_string($type->created) ? $type->created : $type->created->format('Y-m-d H:i')) : 'N/A' ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-basketball-ball fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun type basketball configuré</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>