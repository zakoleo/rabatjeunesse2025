<?php
/**
 * @var \App\View\AppView $this
 * @var array $categories
 * @var array $types
 * @var array $relationshipMatrix
 */
?>
<div class="basketball-relationships content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🔗 Basketball Relations</h2>
            <p class="text-muted mb-0">Gestion des relations catégories-types pour le basketball</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('🏀 Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <!-- Relationships Matrix -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-success">Relations Catégories ↔ Types Basketball</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($categories) && !empty($types)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Catégorie / Type</th>
                                <?php foreach ($types as $type): ?>
                                    <th class="text-center">
                                        <span class="badge badge-info"><?= h($type->code) ?></span>
                                        <br>
                                        <small><?= h($type->name) ?></small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-primary"><?= h($category->name) ?></span>
                                        <br>
                                        <small class="text-muted"><?= h($category->age_range ?? '') ?></small>
                                    </td>
                                    <?php foreach ($types as $type): ?>
                                        <td class="text-center">
                                            <?php if (isset($relationshipMatrix[$category->id][$type->id])): ?>
                                                <i class="fas fa-check-circle text-success fa-lg"></i>
                                            <?php else: ?>
                                                <i class="fas fa-times-circle text-muted"></i>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <p class="small text-muted">
                        <i class="fas fa-info-circle"></i>
                        Les relations définissent quels types de terrain sont disponibles pour chaque catégorie d'âge.
                    </p>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-link fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucune catégorie ou type configuré pour établir des relations</p>
                    <div class="btn-group">
                        <?= $this->Html->link('Configurer les catégories', ['action' => 'categories'], ['class' => 'btn btn-primary btn-sm']) ?>
                        <?= $this->Html->link('Configurer les types', ['action' => 'types'], ['class' => 'btn btn-info btn-sm']) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>