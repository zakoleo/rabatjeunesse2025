<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CrosstrainingParticipant $participant
 */
?>
<div class="crosstraining-management view-participant">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Détails du participant Cross Training</h2>
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour aux participants', 
                ['action' => 'participants'], 
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Informations personnelles</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Référence inscription</th>
                                        <td><?= h($participant->reference_inscription) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nom complet</th>
                                        <td><?= h($participant->nom_complet) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date de naissance</th>
                                        <td><?= h($participant->date_naissance->format('d/m/Y')) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Âge</th>
                                        <td>
                                            <?php 
                                            $age = $participant->date_naissance ? 
                                                $participant->date_naissance->diffInYears(\Cake\Chronos\ChronosDate::now()) : '-';
                                            echo $age . ' ans';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lieu de naissance</th>
                                        <td><?= h($participant->lieu_naissance) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Genre</th>
                                        <td><?= h($participant->gender) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>CIN</th>
                                        <td><?= h($participant->cin) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Téléphone</th>
                                        <td><?= h($participant->telephone) ?></td>
                                    </tr>
                                    <tr>
                                        <th>WhatsApp</th>
                                        <td><?= h($participant->whatsapp) ?: 'Non renseigné' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= h($participant->email) ?: 'Non renseigné' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Taille T-shirt</th>
                                        <td><?= h($participant->taille_tshirt) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Catégorie</th>
                                        <td>
                                            <?= $participant->has('crosstraining_category') ? 
                                                h($participant->crosstraining_category->name) : '-' ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Documents fournis</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td><i class="fas fa-id-card"></i> CIN Recto</td>
                                <td>
                                    <?php if ($participant->cin_recto): ?>
                                        <span class="text-success"><i class="fas fa-check"></i> Fourni</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fas fa-times"></i> Non fourni</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-id-card"></i> CIN Verso</td>
                                <td>
                                    <?php if ($participant->cin_verso): ?>
                                        <span class="text-success"><i class="fas fa-check"></i> Fourni</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fas fa-times"></i> Non fourni</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Statut de l'inscription</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $statusClass = '';
                        $statusText = '';
                        $statusIcon = '';
                        switch($participant->status) {
                            case 'pending':
                                $statusClass = 'warning';
                                $statusText = 'En attente de vérification';
                                $statusIcon = 'clock';
                                break;
                            case 'verified':
                                $statusClass = 'success';
                                $statusText = 'Inscription vérifiée';
                                $statusIcon = 'check-circle';
                                break;
                            case 'rejected':
                                $statusClass = 'danger';
                                $statusText = 'Inscription rejetée';
                                $statusIcon = 'times-circle';
                                break;
                        }
                        ?>
                        <div class="alert alert-<?= $statusClass ?> text-center">
                            <i class="fas fa-<?= $statusIcon ?> fa-3x mb-3"></i>
                            <h5><?= $statusText ?></h5>
                        </div>
                        
                        <table class="table">
                            <tr>
                                <th>Date inscription</th>
                                <td><?= h($participant->created->format('d/m/Y H:i')) ?></td>
                            </tr>
                            <?php if ($participant->verified_at): ?>
                            <tr>
                                <th>Date vérification</th>
                                <td><?= h($participant->verified_at->format('d/m/Y H:i')) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($participant->verification_notes): ?>
                            <tr>
                                <th>Notes</th>
                                <td><?= nl2br(h($participant->verification_notes)) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                        
                        <?php if ($participant->status === 'pending'): ?>
                        <div>
                            <button type="button" class="btn btn-success btn-block mb-2" 
                                    data-toggle="modal" 
                                    data-target="#verifyModal">
                                <i class="fas fa-check"></i> Vérifier l'inscription
                            </button>
                            <button type="button" class="btn btn-danger btn-block" 
                                    data-toggle="modal" 
                                    data-target="#rejectModal">
                                <i class="fas fa-times"></i> Rejeter l'inscription
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Informations utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($participant->has('user')): ?>
                        <table class="table">
                            <tr>
                                <th>Nom</th>
                                <td><?= h($participant->user->nom) ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?= h($participant->user->email) ?></td>
                            </tr>
                        </table>
                        <?php else: ?>
                        <p class="text-muted">Aucune information utilisateur disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($participant->status === 'pending'): ?>
<!-- Verify Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['action' => 'verify', $participant->id]]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Vérifier l'inscription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Confirmer la vérification de l'inscription de <strong><?= h($participant->nom_complet) ?></strong>?</p>
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
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['action' => 'reject', $participant->id]]) ?>
            <div class="modal-header">
                <h5 class="modal-title">Rejeter l'inscription</h5>
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