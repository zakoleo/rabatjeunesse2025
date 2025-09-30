<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConcoursParticipant $participant
 */
?>
<div class="concours-participants view">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Détails de l'inscription au Concours</h3>
                            <div>
                                <?php if ($participant->status !== 'verified'): ?>
                                    <?= $this->Html->link('<i class="fas fa-edit"></i> Modifier', 
                                        ['action' => 'edit', $participant->id], 
                                        ['class' => 'btn btn-light btn-sm', 'escape' => false]
                                    ) ?>
                                <?php endif; ?>
                                <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour', 
                                    ['action' => 'index'], 
                                    ['class' => 'btn btn-light btn-sm', 'escape' => false]
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informations du concours</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Référence inscription</th>
                                        <td><?= h($participant->reference_inscription) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type de concours</th>
                                        <td><span class="badge bg-info"><?= h($participant->type_concours) ?></span></td>
                                    </tr>
                                    <tr>
                                        <th>Catégorie</th>
                                        <td><?= $participant->has('concours_category') ? h($participant->concours_category->name) : '' ?></td>
                                    </tr>
                                </table>
                                
                                <h5 class="mt-4">Informations personnelles</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Nom complet</th>
                                        <td><?= h($participant->nom_complet) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date de naissance</th>
                                        <td><?= h($participant->date_naissance->format('d/m/Y')) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Genre</th>
                                        <td><?= h($participant->gender) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Coordonnées</h5>
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
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Statut de l'inscription</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Statut</th>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch($participant->status) {
                                                case 'pending':
                                                    $statusClass = 'warning';
                                                    $statusText = 'En attente de vérification';
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
                                    </tr>
                                    <tr>
                                        <th>Date d'inscription</th>
                                        <td><?= h($participant->created->format('d/m/Y H:i')) ?></td>
                                    </tr>
                                    <?php if ($participant->verified_at): ?>
                                    <tr>
                                        <th>Date de vérification</th>
                                        <td><?= h($participant->verified_at->format('d/m/Y H:i')) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($participant->verification_notes): ?>
                                    <tr>
                                        <th>Notes de vérification</th>
                                        <td><?= nl2br(h($participant->verification_notes)) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5>Documents</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>CIN Recto</th>
                                        <td><?= $participant->cin_recto ? 'Téléchargé' : 'Non fourni' ?></td>
                                    </tr>
                                    <tr>
                                        <th>CIN Verso</th>
                                        <td><?= $participant->cin_verso ? 'Téléchargé' : 'Non fourni' ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>