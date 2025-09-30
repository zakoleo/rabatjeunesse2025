<?php
/**
 * @var \App\View\AppView $this
 * @var array $stats
 * @var array $categories
 */
?>
<div class="crosstraining-management index">
    <div class="container-fluid">
        <h2 class="mb-4">Gestion Cross Training</h2>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Catégories actives</h5>
                        <h3 class="mb-0"><?= $stats['categories_count'] ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total participants</h5>
                        <h3 class="mb-0"><?= $stats['participants_count'] ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">En attente</h5>
                        <h3 class="mb-0"><?= $stats['pending_count'] ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Vérifiés</h5>
                        <h3 class="mb-0"><?= $stats['verified_count'] ?></h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="btn-group" role="group">
                    <?= $this->Html->link('<i class="fas fa-list"></i> Gérer les catégories', 
                        ['action' => 'categories'], 
                        ['class' => 'btn btn-primary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link('<i class="fas fa-users"></i> Voir les participants', 
                        ['action' => 'participants'], 
                        ['class' => 'btn btn-secondary', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link('<i class="fas fa-clock"></i> Participants en attente', 
                        ['action' => 'participants', '?' => ['status' => 'pending']], 
                        ['class' => 'btn btn-warning', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>
        
        <!-- Categories Overview -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Catégories Cross Training</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Genre</th>
                                <th>Tranche d'âge</th>
                                <th>Participants</th>
                                <th>Min/Max joueurs</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= h($category->name) ?></td>
                                <td><?= h($category->gender) ?></td>
                                <td><?= h($category->age_category) ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?= count($category->crosstraining_participants) ?> participants
                                    </span>
                                </td>
                                <td><?= $category->min_players ?> / <?= $category->max_players ?></td>
                                <td>
                                    <?php if ($category->active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $this->Html->link('<i class="fas fa-users"></i>', 
                                        ['action' => 'participants', '?' => ['category_id' => $category->id]], 
                                        ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir les participants']
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>