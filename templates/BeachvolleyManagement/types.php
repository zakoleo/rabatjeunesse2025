<?php
/**
 * @var \App\View\AppView $this
 * @var array $types
 */
?>
<div class="beachvolley-types content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🏖️ Beach Volleyball Types</h2>
        <?= $this->Html->link('🏖️ Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>
    
    <div class="card shadow">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-info">Types de terrain Beach Volleyball</h6></div>
        <div class="card-body">
            <?php if (!empty($types)): ?>
                <table class="table table-bordered">
                    <thead><tr><th>Nom</th><th>Code</th><th>Joueurs</th><th>Catégories</th><th>Statut</th></tr></thead>
                    <tbody>
                        <?php foreach ($types as $type): ?>
                            <tr>
                                <td><span class="badge badge-info"><?= h($type->name) ?></span></td>
                                <td><code><?= h($type->code) ?></code></td>
                                <td><?= $type->min_players ?>-<?= $type->max_players ?></td>
                                <td><span class="badge badge-light"><?= count($type->beachvolley_categories ?? []) ?> catégories</span></td>
                                <td><?= $type->active ? '<span class="badge badge-success">Actif</span>' : '<span class="badge badge-secondary">Inactif</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">Aucun type beach volleyball configuré</p>
            <?php endif; ?>
        </div>
    </div>
</div>