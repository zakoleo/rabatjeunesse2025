<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CrosstrainingCategory[]|\Cake\Collection\CollectionInterface $categories
 */
?>
<div class="crosstraining-management categories">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Catégories Cross Training</h2>
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour au tableau de bord', 
                ['action' => 'index'], 
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Genre</th>
                                <th>Catégorie d'âge</th>
                                <th>Plage de dates</th>
                                <th>Min/Max participants</th>
                                <th>Statut</th>
                                <th>Créé</th>
                                <th>Modifié</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $this->Number->format($category->id) ?></td>
                                <td><?= h($category->name) ?></td>
                                <td><?= h($category->gender) ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= h($category->age_category) ?></span>
                                </td>
                                <td>
                                    <?php if ($category->date_range_start && $category->date_range_end): ?>
                                        <?= h($category->date_range_start->format('d/m/Y')) ?> - 
                                        <?= h($category->date_range_end->format('d/m/Y')) ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= $this->Number->format($category->min_players) ?> / 
                                    <?= $this->Number->format($category->max_players) ?>
                                </td>
                                <td>
                                    <?php if ($category->active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($category->created->format('d/m/Y H:i')) ?></td>
                                <td><?= h($category->modified->format('d/m/Y H:i')) ?></td>
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
                
                <!-- Pagination -->
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->first('<< ' . __('Premier')) ?>
                        <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('Suivant') . ' >') ?>
                        <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
                    </ul>
                    <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, {{count}} résultats au total')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>