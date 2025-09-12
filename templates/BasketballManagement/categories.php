<?php
/**
 * @var \App\View\AppView $this
 * @var array $categories
 */
?>
<div class="basketball-categories content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📋 Basketball Categories</h2>
            <p class="text-muted mb-0">Gestion des catégories d'âge pour le basketball</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('🏀 Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <!-- Categories List -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Catégories Basketball</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($categories)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Tranche d'âge</th>
                                <th>Date de naissance</th>
                                <th>Types associés</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><span class="badge badge-primary"><?= h($category->name) ?></span></td>
                                    <td><?= h($category->age_range ?? 'Non défini') ?></td>
                                    <td>
                                        <small>
                                            <?= h($category->min_date ?? '') ?> - <?= h($category->max_date ?? '') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">
                                            <?= count($category->basketball_types ?? []) ?> types
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($category->active): ?>
                                            <span class="badge badge-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= $category->created ? (is_string($category->created) ? $category->created : $category->created->format('Y-m-d H:i')) : 'N/A' ?>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune catégorie basketball configurée</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>