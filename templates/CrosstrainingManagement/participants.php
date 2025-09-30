<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CrosstrainingParticipant[]|\Cake\Collection\CollectionInterface $participants
 * @var array $categories
 */
?>
<div class="crosstraining-management participants">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Participants Cross Training</h2>
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour au tableau de bord', 
                ['action' => 'index'], 
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3']) ?>
                <div class="col-md-4">
                    <?= $this->Form->control('status', [
                        'label' => 'Statut',
                        'options' => [
                            '' => 'Tous',
                            'pending' => 'En attente',
                            'verified' => 'Vérifié',
                            'rejected' => 'Rejeté'
                        ],
                        'value' => $this->request->getQuery('status'),
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $this->Form->control('category_id', [
                        'label' => 'Catégorie',
                        'options' => ['' => 'Toutes'] + $categories,
                        'value' => $this->request->getQuery('category_id'),
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <?= $this->Form->button('<i class="fas fa-filter"></i> Filtrer', [
                        'type' => 'submit',
                        'class' => 'btn btn-primary',
                        'escape' => false
                    ]) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
        
        <!-- Participants Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?= $this->Paginator->sort('reference_inscription', 'Référence') ?></th>
                                <th><?= $this->Paginator->sort('nom_complet', 'Nom') ?></th>
                                <th>Catégorie</th>
                                <th><?= $this->Paginator->sort('gender', 'Genre') ?></th>
                                <th><?= $this->Paginator->sort('date_naissance', 'Âge') ?></th>
                                <th><?= $this->Paginator->sort('status', 'Statut') ?></th>
                                <th><?= $this->Paginator->sort('created', 'Date inscription') ?></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?= h($participant->reference_inscription) ?></td>
                                <td><?= h($participant->nom_complet) ?></td>
                                <td>
                                    <?= $participant->has('crosstraining_category') ? 
                                        h($participant->crosstraining_category->name) : '-' ?>
                                </td>
                                <td><?= h($participant->gender) ?></td>
                                <td>
                                    <?php 
                                    $age = $participant->date_naissance ? 
                                        $participant->date_naissance->diffInYears(\Cake\Chronos\ChronosDate::now()) . ' ans' : '-';
                                    echo $age;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    switch($participant->status) {
                                        case 'pending':
                                            $statusClass = 'warning';
                                            $statusText = 'En attente';
                                            break;
                                        case 'verified':
                                            $statusClass = 'success';
                                            $statusText = 'Vérifié';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'danger';
                                            $statusText = 'Rejeté';
                                            break;
                                    }
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td><?= h($participant->created->format('d/m/Y H:i')) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?= $this->Html->link('<i class="fas fa-eye"></i>', 
                                            ['action' => 'viewParticipant', $participant->id], 
                                            ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir']
                                        ) ?>
                                        
                                        <?php if ($participant->status === 'pending'): ?>
                                            <button type="button" class="btn btn-sm btn-success" 
                                                    data-toggle="modal" 
                                                    data-target="#verifyModal<?= $participant->id ?>"
                                                    title="Vérifier">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-toggle="modal" 
                                                    data-target="#rejectModal<?= $participant->id ?>"
                                                    title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Verify Modal -->
                            <?php if ($participant->status === 'pending'): ?>
                            <div class="modal fade" id="verifyModal<?= $participant->id ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <?= $this->Form->create(null, ['url' => ['action' => 'verify', $participant->id]]) ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Vérifier le participant</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Confirmer la vérification de <strong><?= h($participant->nom_complet) ?></strong>?</p>
                                            <?= $this->Form->control('verification_notes', [
                                                'label' => 'Notes (optionnel)',
                                                'type' => 'textarea',
                                                'rows' => 3,
                                                'class' => 'form-control'
                                            ]) ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <?= $this->Form->button('Vérifier', ['class' => 'btn btn-success']) ?>
                                        </div>
                                        <?= $this->Form->end() ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal<?= $participant->id ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <?= $this->Form->create(null, ['url' => ['action' => 'reject', $participant->id]]) ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Rejeter le participant</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Rejeter l'inscription de <strong><?= h($participant->nom_complet) ?></strong>?</p>
                                            <?= $this->Form->control('verification_notes', [
                                                'label' => 'Raison du rejet',
                                                'type' => 'textarea',
                                                'rows' => 3,
                                                'class' => 'form-control',
                                                'required' => true
                                            ]) ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <?= $this->Form->button('Rejeter', ['class' => 'btn btn-danger']) ?>
                                        </div>
                                        <?= $this->Form->end() ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
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