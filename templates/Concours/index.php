<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConcoursParticipant[] $participants
 */
?>
<div class="concours-participants index">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Mes inscriptions aux Concours</h3>
                            <?= $this->Html->link('<i class="fas fa-plus"></i> Nouvelle inscription', 
                                ['action' => 'add'], 
                                ['class' => 'btn btn-light btn-sm', 'escape' => false]
                            ) ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!$participants->isEmpty()): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Référence</th>
                                            <th>Nom complet</th>
                                            <th>Type de concours</th>
                                            <th>Catégorie</th>
                                            <th>Genre</th>
                                            <th>Status</th>
                                            <th>Date d'inscription</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($participants as $participant): ?>
                                        <tr>
                                            <td><?= h($participant->reference_inscription) ?></td>
                                            <td><?= h($participant->nom_complet) ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= h($participant->type_concours) ?></span>
                                            </td>
                                            <td><?= $participant->has('concours_category') ? h($participant->concours_category->name) : '' ?></td>
                                            <td><?= h($participant->gender) ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
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
                                            <td class="actions">
                                                <?= $this->Html->link('<i class="fas fa-eye"></i>', 
                                                    ['action' => 'view', $participant->id], 
                                                    ['class' => 'btn btn-info btn-sm', 'escape' => false, 'title' => 'Voir']
                                                ) ?>
                                                <?php if ($participant->status !== 'verified'): ?>
                                                    <?= $this->Html->link('<i class="fas fa-edit"></i>', 
                                                        ['action' => 'edit', $participant->id], 
                                                        ['class' => 'btn btn-warning btn-sm', 'escape' => false, 'title' => 'Modifier']
                                                    ) ?>
                                                    <?= $this->Form->postLink('<i class="fas fa-trash"></i>', 
                                                        ['action' => 'delete', $participant->id], 
                                                        [
                                                            'class' => 'btn btn-danger btn-sm', 
                                                            'escape' => false, 
                                                            'title' => 'Supprimer',
                                                            'confirm' => __('Êtes-vous sûr de vouloir supprimer cette inscription ?')
                                                        ]
                                                    ) ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <h4 class="alert-heading">Aucune inscription</h4>
                                <p>Vous n'avez pas encore d'inscription aux concours.</p>
                                <hr>
                                <p class="mb-0">
                                    <?= $this->Html->link('Créer une nouvelle inscription', 
                                        ['action' => 'add'], 
                                        ['class' => 'btn btn-primary']
                                    ) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>