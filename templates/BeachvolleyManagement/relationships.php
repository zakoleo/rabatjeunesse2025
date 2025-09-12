<?php
/**
 * @var \App\View\AppView $this
 * @var array $categories
 * @var array $types
 * @var array $relationshipMatrix
 */
?>
<div class="beachvolley-relationships content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🔗 Beach Volleyball Relations</h2>
        <?= $this->Html->link('🏖️ Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>
    
    <div class="card shadow">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-success">Relations Catégories ↔ Types Beach Volleyball</h6></div>
        <div class="card-body">
            <?php if (!empty($categories) && !empty($types)): ?>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Catégorie / Type</th>
                            <?php foreach ($types as $type): ?>
                                <th class="text-center"><span class="badge badge-info"><?= h($type->code) ?></span></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?= h($category->name) ?></span></td>
                                <?php foreach ($types as $type): ?>
                                    <td class="text-center">
                                        <?= isset($relationshipMatrix[$category->id][$type->id]) ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-muted"></i>' ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">Aucune catégorie ou type configuré</p>
            <?php endif; ?>
        </div>
    </div>
</div>