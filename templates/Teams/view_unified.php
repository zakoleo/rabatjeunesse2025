<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 * @var string $sport Sport type (football, basketball, handball, volleyball, beachvolley)
 */

// Define sport configurations
$sportConfigs = [
    'football' => [
        'title' => 'Football',
        'typeField' => 'type_football',
        'editAction' => 'edit',
        'addAction' => 'add',
        'deleteAction' => 'delete',
        'downloadAction' => 'downloadPdf',
        'playersField' => 'joueurs'
    ],
    'basketball' => [
        'title' => 'Basketball',
        'typeField' => 'type_basketball', 
        'editAction' => 'editBasketball',
        'addAction' => 'addBasketball',
        'deleteAction' => 'deleteBasketball',
        'downloadAction' => 'downloadBasketballPdf',
        'playersField' => 'basketball_teams_joueurs'
    ],
    'handball' => [
        'title' => 'Handball',
        'typeField' => 'type_handball',
        'editAction' => 'editHandball',
        'addAction' => 'addHandball', 
        'deleteAction' => 'deleteHandball',
        'downloadAction' => 'downloadHandballPdf',
        'playersField' => 'handball_teams_joueurs'
    ],
    'volleyball' => [
        'title' => 'Volleyball',
        'typeField' => 'type_volleyball',
        'editAction' => 'editVolleyball',
        'addAction' => 'addVolleyball',
        'deleteAction' => 'deleteVolleyball', 
        'downloadAction' => 'downloadVolleyballPdf',
        'playersField' => 'volleyball_teams_joueurs'
    ],
    'beachvolley' => [
        'title' => 'Beach Volleyball',
        'typeField' => 'type_beachvolley',
        'editAction' => 'editBeachvolley',
        'addAction' => 'addBeachvolley',
        'deleteAction' => 'deleteBeachvolley',
        'downloadAction' => 'downloadBeachvolleyPdf', 
        'playersField' => 'beachvolley_teams_joueurs'
    ]
];

$config = $sportConfigs[$sport] ?? $sportConfigs['football'];
$players = isset($team->{$config['playersField']}) ? $team->{$config['playersField']} : [];
?>

<div class="team-view-container" data-sport="<?= $sport ?>">
    <!-- Header -->
    <div class="team-view-header">
        <div class="header-content">
            <div class="team-info">
                <h1 class="team-name"><?= h($team->nom_equipe) ?></h1>
                <p class="team-subtitle">Équipe de <?= $config['title'] ?> inscrite</p>
                <?php if (!empty($team->reference_inscription)): ?>
                    <div class="reference-badge">
                        <span class="reference-label">Référence:</span>
                        <span class="reference-number"><?= h($team->reference_inscription) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="sport-icon">
                <div class="sport-badge" data-sport="<?= $sport ?>">
                    <?= strtoupper(substr($config['title'], 0, 2)) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="team-actions">
        <?= $this->Html->link(
            '<i class="icon-edit"></i>Modifier', 
            ['action' => $config['editAction'], $team->id], 
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
        <?= $this->Html->link(
            '<i class="icon-list"></i>Liste des équipes', 
            ['action' => 'index'], 
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
        <?= $this->Html->link(
            '<i class="icon-plus"></i>Nouvelle équipe', 
            ['action' => $config['addAction']], 
            ['class' => 'btn btn-success', 'escape' => false]
        ) ?>
        <?php if (!empty($team->reference_inscription)): ?>
            <?= $this->Html->link(
                '<i class="icon-download"></i>PDF', 
                ['action' => $config['downloadAction'], $team->id], 
                ['class' => 'btn btn-info', 'escape' => false]
            ) ?>
        <?php endif; ?>
        <?= $this->Form->postLink(
            '<i class="icon-trash"></i>Supprimer', 
            ['action' => $config['deleteAction'], $team->id], [
                'confirm' => 'Êtes-vous sûr de vouloir supprimer cette équipe ?',
                'class' => 'btn btn-danger',
                'escape' => false
            ]
        ) ?>
    </div>

    <!-- Main Content -->
    <div class="team-content">
        <!-- Team Information Grid -->
        <div class="info-grid">
            <!-- Team Details -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="icon-team"></i>Informations sur l'équipe</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Nom de l'équipe</span>
                        <span class="info-value"><?= h($team->nom_equipe) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Catégorie</span>
                        <span class="info-value"><?= h($team->categorie) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Genre</span>
                        <span class="info-value">
                            <span class="badge badge-genre"><?= h($team->genre) ?></span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Type de <?= strtolower($config['title']) ?></span>
                        <span class="info-value">
                            <span class="badge badge-sport"><?= h($team->{$config['typeField']}) ?></span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">District</span>
                        <span class="info-value"><?= h($team->district) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Organisation</span>
                        <span class="info-value"><?= h($team->organisation) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date d'inscription</span>
                        <span class="info-value"><?= h($team->created->format('d/m/Y à H:i')) ?></span>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="icon-location"></i>Adresse</h3>
                </div>
                <div class="card-body">
                    <div class="address-content">
                        <?= nl2br(h($team->adresse)) ?>
                    </div>
                </div>
            </div>

            <!-- Team Manager -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="icon-user"></i>Responsable de l'équipe</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Nom complet</span>
                        <span class="info-value"><?= h($team->responsable_nom_complet) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date de naissance</span>
                        <span class="info-value"><?= h($team->responsable_date_naissance ? $team->responsable_date_naissance->format('d/m/Y') : '') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Téléphone</span>
                        <span class="info-value">
                            <a href="tel:<?= h($team->responsable_tel) ?>" class="phone-link">
                                <?= h($team->responsable_tel) ?>
                            </a>
                        </span>
                    </div>
                    <?php if ($team->responsable_whatsapp): ?>
                    <div class="info-row">
                        <span class="info-label">WhatsApp</span>
                        <span class="info-value">
                            <a href="https://wa.me/<?= h(str_replace([' ', '-', '(', ')'], '', $team->responsable_whatsapp)) ?>" 
                               class="whatsapp-link" target="_blank">
                                <?= h($team->responsable_whatsapp) ?>
                            </a>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Coach -->
            <div class="info-card">
                <div class="card-header">
                    <h3><i class="icon-coach"></i>Entraîneur de l'équipe</h3>
                </div>
                <div class="card-body">
                    <?php if ($team->entraineur_same_as_responsable): ?>
                        <div class="same-person-notice">
                            <i class="icon-info"></i>
                            <span>L'entraîneur est la même personne que le responsable</span>
                        </div>
                    <?php else: ?>
                        <div class="info-row">
                            <span class="info-label">Nom complet</span>
                            <span class="info-value"><?= h($team->entraineur_nom_complet) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date de naissance</span>
                            <span class="info-value"><?= h($team->entraineur_date_naissance ? $team->entraineur_date_naissance->format('d/m/Y') : '') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Téléphone</span>
                            <span class="info-value">
                                <a href="tel:<?= h($team->entraineur_tel) ?>" class="phone-link">
                                    <?= h($team->entraineur_tel) ?>
                                </a>
                            </span>
                        </div>
                        <?php if ($team->entraineur_whatsapp): ?>
                        <div class="info-row">
                            <span class="info-label">WhatsApp</span>
                            <span class="info-value">
                                <a href="https://wa.me/<?= h(str_replace([' ', '-', '(', ')'], '', $team->entraineur_whatsapp)) ?>" 
                                   class="whatsapp-link" target="_blank">
                                    <?= h($team->entraineur_whatsapp) ?>
                                </a>
                            </span>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Players Section -->
        <?php if (!empty($players)): ?>
        <div class="players-section">
            <div class="players-header">
                <h3><i class="icon-players"></i>Liste des joueurs</h3>
                <span class="players-count"><?= count($players) ?> joueur<?= count($players) > 1 ? 's' : '' ?></span>
            </div>
            
            <div class="players-grid">
                <?php foreach ($players as $index => $player): ?>
                <div class="player-card">
                    <div class="player-number"><?= $player->numero_maillot ?? ($index + 1) ?></div>
                    <div class="player-info">
                        <h4 class="player-name"><?= h($player->nom_complet) ?></h4>
                        <div class="player-details">
                            <?php if (!empty($player->date_naissance)): ?>
                                <span class="player-age">
                                    <?= h($player->date_naissance->format('d/m/Y')) ?>
                                    <?php 
                                        $today = \Cake\Chronos\Chronos::now();
                                        $birthDate = \Cake\Chronos\Chronos::parse($player->date_naissance);
                                        $age = $today->diffInYears($birthDate);
                                    ?>
                                    (<?= $age ?> ans)
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($player->position)): ?>
                                <span class="player-position"><?= h($player->position) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($player->numero_licence)): ?>
                            <div class="player-license">
                                Licence: <?= h($player->numero_licence) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($player->tel)): ?>
                            <div class="player-contact">
                                <a href="tel:<?= h($player->tel) ?>" class="phone-link">
                                    <?= h($player->tel) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="no-players">
            <div class="no-players-icon">
                <i class="icon-players"></i>
            </div>
            <h3>Aucun joueur</h3>
            <p>Aucun joueur n'a été ajouté à cette équipe pour le moment.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->Html->css('team-view-unified') ?>

<!-- Icons (using simple CSS icons for better performance) -->
<style>
.icon-edit::before { content: "✏️"; }
.icon-list::before { content: "📄"; }
.icon-plus::before { content: "➕"; }
.icon-download::before { content: "⬇️"; }
.icon-trash::before { content: "🗑️"; }
.icon-team::before { content: "👥"; }
.icon-location::before { content: "📍"; }
.icon-user::before { content: "👤"; }
.icon-coach::before { content: "🏃"; }
.icon-info::before { content: "ℹ️"; }
.icon-players::before { content: "⚽"; }

[data-sport="football"] .icon-players::before { content: "⚽"; }
[data-sport="basketball"] .icon-players::before { content: "🏀"; }
[data-sport="handball"] .icon-players::before { content: "🤾"; }
[data-sport="volleyball"] .icon-players::before { content: "🏐"; }
[data-sport="beachvolley"] .icon-players::before { content: "🏖️"; }
</style>