<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BasketballTeam $team
 */
?>
<div class="page-header">
    <div class="container">
        <h1><?= h($team->nom_equipe) ?></h1>
        <p>Détails de l'équipe de basketball inscrite</p>
    </div>
</div>

<div class="content-section">
    <div class="container">
        <div class="actions mb-4">
            <?= $this->Html->link('Modifier', ['action' => 'editBasketball', $team->id], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('Liste des équipes', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Html->link('Nouvelle équipe de basketball', ['action' => 'addBasketball'], ['class' => 'btn btn-success']) ?>
            <?php if (!empty($team->reference_inscription)): ?>
                <?= $this->Html->link('Télécharger PDF', ['action' => 'downloadBasketballPdf', $team->id], ['class' => 'btn btn-info']) ?>
            <?php endif; ?>
            <?= $this->Form->postLink('Supprimer', ['action' => 'deleteBasketball', $team->id], [
                'confirm' => 'Êtes-vous sûr de vouloir supprimer cette équipe de basketball ?',
                'class' => 'btn btn-danger float-right'
            ]) ?>
        </div>

        <?php if (!empty($team->reference_inscription)): ?>
        <div class="alert alert-success mb-4">
            <strong>Référence d'inscription:</strong> <?= h($team->reference_inscription) ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-2">
            <!-- Informations sur l'équipe -->
            <div class="card">
                <div class="card-header">
                    <h3>Informations sur l'équipe</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th><?= __('Nom de l\'équipe') ?></th>
                            <td><?= h($team->nom_equipe) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Catégorie') ?></th>
                            <td><?= h($team->categorie) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Genre') ?></th>
                            <td><?= h($team->genre) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Type de basketball') ?></th>
                            <td><span class="badge badge-basketball"><?= h($team->type_basketball) ?></span></td>
                        </tr>
                        <tr>
                            <th><?= __('District') ?></th>
                            <td><?= h($team->district) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Organisation') ?></th>
                            <td><?= h($team->organisation) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Date d\'inscription') ?></th>
                            <td><?= h($team->created->format('d/m/Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Adresse -->
            <div class="card">
                <div class="card-header">
                    <h3>Adresse</h3>
                </div>
                <div class="card-body">
                    <p><?= nl2br(h($team->adresse)) ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-2 mt-4">
            <!-- Responsable de l'équipe -->
            <div class="card">
                <div class="card-header">
                    <h3>Responsable de l'équipe</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th><?= __('Nom complet') ?></th>
                            <td><?= h($team->responsable_nom_complet) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Date de naissance') ?></th>
                            <td><?= h($team->responsable_date_naissance ? $team->responsable_date_naissance->format('d/m/Y') : '') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Téléphone') ?></th>
                            <td><?= h($team->responsable_tel) ?></td>
                        </tr>
                        <?php if ($team->responsable_whatsapp): ?>
                        <tr>
                            <th><?= __('WhatsApp') ?></th>
                            <td><?= h($team->responsable_whatsapp) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Entraîneur de l'équipe -->
            <div class="card">
                <div class="card-header">
                    <h3>Entraîneur de l'équipe</h3>
                </div>
                <div class="card-body">
                    <?php if ($team->entraineur_same_as_responsable): ?>
                        <div class="alert alert-info">
                            L'entraîneur est la même personne que le responsable
                        </div>
                    <?php else: ?>
                        <table class="table table-borderless">
                            <tr>
                                <th><?= __('Nom complet') ?></th>
                                <td><?= h($team->entraineur_nom_complet) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Date de naissance') ?></th>
                                <td><?= h($team->entraineur_date_naissance ? $team->entraineur_date_naissance->format('d/m/Y') : '') ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Téléphone') ?></th>
                                <td><?= h($team->entraineur_tel) ?></td>
                            </tr>
                            <?php if ($team->entraineur_whatsapp): ?>
                            <tr>
                                <th><?= __('WhatsApp') ?></th>
                                <td><?= h($team->entraineur_whatsapp) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Joueurs -->
        <?php if (!empty($team->basketball_teams_joueurs)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h3>Liste des joueurs (<?= count($team->basketball_teams_joueurs) ?>)</h3>
                <div class="basketball-info">
                    <?php 
                    $playerCount = count($team->basketball_teams_joueurs);
                    $limits = ['3x3' => ['min' => 3, 'max' => 4], '5x5' => ['min' => 5, 'max' => 8]];
                    $type = $team->type_basketball;
                    if (isset($limits[$type])):
                        $min = $limits[$type]['min'];
                        $max = $limits[$type]['max'];
                        $status = $playerCount >= $min && $playerCount <= $max ? 'valid' : 'invalid';
                    ?>
                    <small class="text-<?= $status === 'valid' ? 'success' : 'danger' ?>">
                        <?= $type ?>: <?= $playerCount ?>/<?= $max ?> joueurs 
                        (minimum: <?= $min ?>, maximum: <?= $max ?>)
                    </small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom complet</th>
                                <th>Date de naissance</th>
                                <th>Identifiant</th>
                                <th>Taille vestimentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($team->basketball_teams_joueurs as $index => $joueur): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= h($joueur->nom_complet) ?></td>
                                <td><?= h($joueur->date_naissance ? $joueur->date_naissance->format('d/m/Y') : '') ?></td>
                                <td><?= h($joueur->identifiant) ?></td>
                                <td><span class="badge badge-size"><?= h($joueur->taille_vestimentaire) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card mt-4">
            <div class="card-body text-center">
                <p class="text-light">Aucun joueur n'a été ajouté à cette équipe de basketball.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .table-borderless th,
    .table-borderless td {
        border: none;
        padding: 0.5rem 1rem;
    }
    
    .table-borderless th {
        color: var(--text-light);
        font-weight: 500;
        width: 40%;
    }
    
    .table-borderless td {
        color: var(--text-dark);
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 20px;
    }
    
    .badge-basketball {
        background-color: #FF6B35;
        color: white;
    }
    
    .badge-size {
        background-color: #6c757d;
        color: white;
    }
    
    .float-right {
        float: right;
    }
    
    .basketball-info {
        margin-top: 0.5rem;
    }
    
    .text-success {
        color: #28a745 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .alert {
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }
    
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
</style>