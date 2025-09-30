<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SportsurbainsParticipant $participant
 */
?>

<div class="participant-details">
    <div class="row">
        <div class="col-md-6">
            <h6 class="mb-3">Informations personnelles</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>Référence:</strong></td>
                    <td><code><?= h($participant->reference_inscription) ?></code></td>
                </tr>
                <tr>
                    <td><strong>Type de sport:</strong></td>
                    <td><span class="badge badge-primary"><?= h($participant->type_sport) ?></span></td>
                </tr>
                <tr>
                    <td><strong>Nom complet:</strong></td>
                    <td><?= h($participant->nom_complet) ?></td>
                </tr>
                <tr>
                    <td><strong>Date de naissance:</strong></td>
                    <td><?= $participant->date_naissance->format('d/m/Y') ?></td>
                </tr>
                <tr>
                    <td><strong>Genre:</strong></td>
                    <td><?= h($participant->gender) ?></td>
                </tr>
                <tr>
                    <td><strong>CIN:</strong></td>
                    <td><?= h($participant->cin) ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6 class="mb-3">Contact</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?= $participant->has('user') ? h($participant->user->email) : h($participant->email ?: '-') ?></td>
                </tr>
                <tr>
                    <td><strong>Téléphone:</strong></td>
                    <td><?= h($participant->telephone) ?></td>
                </tr>
                <tr>
                    <td><strong>WhatsApp:</strong></td>
                    <td><?= h($participant->whatsapp ?: '-') ?></td>
                </tr>
            </table>
            
            <h6 class="mb-3 mt-4">Statut</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>Statut actuel:</strong></td>
                    <td>
                        <?php
                        $statusClass = 'secondary';
                        $statusText = 'En attente';
                        switch($participant->status) {
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
                        <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Date inscription:</strong></td>
                    <td><?= $participant->created->format('d/m/Y H:i') ?></td>
                </tr>
                <?php if ($participant->verified_at): ?>
                <tr>
                    <td><strong>Date vérification:</strong></td>
                    <td><?= $participant->verified_at->format('d/m/Y H:i') ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <h6 class="mb-3">Catégorie</h6>
            <?php if ($participant->has('sportsurbains_category')): ?>
                <p>
                    <span class="badge badge-info">
                        <?= h($participant->sportsurbains_category->gender) ?> - 
                        <?= h($participant->sportsurbains_category->age_category) ?>
                    </span>
                </p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($participant->verification_notes): ?>
    <div class="row mt-3">
        <div class="col-md-12">
            <h6 class="mb-3">Notes de vérification</h6>
            <div class="alert alert-info">
                <?= nl2br(h($participant->verification_notes)) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <h6 class="mb-3">Documents</h6>
            <table class="table table-sm">
                <tr>
                    <td><strong>CIN Recto:</strong></td>
                    <td><?= $participant->cin_recto ? 'Téléchargé ✓' : 'Non fourni' ?></td>
                </tr>
                <tr>
                    <td><strong>CIN Verso:</strong></td>
                    <td><?= $participant->cin_verso ? 'Téléchargé ✓' : 'Non fourni' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>