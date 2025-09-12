<?php
/**
 * @var \App\View\AppView $this
 * @var array $categories
 */
?>
<div class="volleyball-categories content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📋 Volleyball Categories</h2>
        <?= $this->Html->link('🏐 Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>
    
    <div class="card shadow">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Catégories Volleyball</h6></div>
        <div class="card-body">
            <?php if (!empty($categories)): ?>
                <table class="table table-bordered">
                    <thead><tr><th>Nom</th><th>Types associés</th><th>Statut</th><th>Créé le</th></tr></thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?= h($category->name) ?></span></td>
                                <td><span class="badge badge-light"><?= count($category->volleyball_types ?? []) ?> types</span></td>
                                <td><?= $category->active ? '<span class="badge badge-success">Actif</span>' : '<span class="badge badge-secondary">Inactif</span>' ?></td>
                                <td><small class="text-muted"><?= $category->created ? (is_string($category->created) ? $category->created : $category->created->format('Y-m-d H:i')) : 'N/A' ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">Aucune catégorie volleyball configurée</p>
            <?php endif; ?>
        </div>
    </div>
</div>