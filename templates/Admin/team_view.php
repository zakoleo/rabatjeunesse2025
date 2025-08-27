<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team|\App\Model\Entity\BasketballTeam|\App\Model\Entity\HandballTeam|\App\Model\Entity\VolleyballTeam|\App\Model\Entity\BeachvolleyTeam $team
 * @var string $sportType
 */

$this->assign('title', 'Administration - Vérification équipe');

// Add admin CSS
echo $this->Html->css('admin');
?>

<div class="admin-team-view">
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1><i class="fas fa-search"></i> Vérification d'équipe</h1>
                <div class="team-meta">
                    <span class="sport-badge sport-<?= $sportType ?>">
                        <?= ucfirst($sportType) ?>
                    </span>
                    <span class="team-name"><?= h($team->nom_equipe) ?></span>
                </div>
            </div>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour', ['action' => 'teams'], ['class' => 'btn btn-secondary', 'escape' => false]) ?>
                
                <?php 
                $teamStatus = $team->status ?? 'pending';
                $statusConfig = [
                    'pending' => ['icon' => 'fas fa-clock', 'label' => 'En attente de vérification', 'class' => 'status-pending'],
                    'verified' => ['icon' => 'fas fa-check-circle', 'label' => 'Équipe vérifiée', 'class' => 'status-verified'],
                    'rejected' => ['icon' => 'fas fa-times-circle', 'label' => 'Équipe rejetée', 'class' => 'status-rejected']
                ];
                $config = $statusConfig[$teamStatus];
                ?>
                
                <span class="status-badge-large <?= $config['class'] ?>">
                    <i class="<?= $config['icon'] ?>"></i> <?= $config['label'] ?>
                </span>
                
                <?php if (!empty($team->verified_at)): ?>
                    <div class="verification-info">
                        <small>Vérifiée le <?= $team->verified_at->format('d/m/Y à H:i') ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="verification-content">
        <!-- Team Information -->
        <div class="info-section">
            <h2><i class="fas fa-info-circle"></i> Informations de l'équipe</h2>
            <div class="info-grid">
                <div class="info-card">
                    <h3>Détails généraux</h3>
                    <div class="info-rows">
                        <div class="info-row">
                            <span class="label">Nom de l'équipe:</span>
                            <span class="value"><?= h($team->nom_equipe) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Catégorie:</span>
                            <span class="value"><?= h($team->categorie) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Genre:</span>
                            <span class="value"><?= h($team->genre) ?></span>
                        </div>
                        <?php if (isset($team->type_basketball)): ?>
                            <div class="info-row">
                                <span class="label">Type Basketball:</span>
                                <span class="value"><?= h($team->type_basketball) ?></span>
                            </div>
                        <?php elseif (isset($team->type_handball)): ?>
                            <div class="info-row">
                                <span class="label">Type Handball:</span>
                                <span class="value"><?= h($team->type_handball) ?></span>
                            </div>
                        <?php elseif (isset($team->type_volleyball)): ?>
                            <div class="info-row">
                                <span class="label">Type Volleyball:</span>
                                <span class="value"><?= h($team->type_volleyball) ?></span>
                            </div>
                        <?php elseif (isset($team->type_beachvolley)): ?>
                            <div class="info-row">
                                <span class="label">Type Beach Volleyball:</span>
                                <span class="value"><?= h($team->type_beachvolley) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="label">District:</span>
                            <span class="value"><?= h($team->district) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Organisation:</span>
                            <span class="value"><?= h($team->organisation) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Adresse:</span>
                            <span class="value"><?= h($team->adresse) ?></span>
                        </div>
                        <?php if (!empty($team->reference_inscription)): ?>
                            <div class="info-row">
                                <span class="label">Référence:</span>
                                <span class="value reference"><?= h($team->reference_inscription) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="label">Date d'inscription:</span>
                            <span class="value"><?= $team->created->format('d/m/Y à H:i') ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- User Information -->
                <div class="info-card">
                    <h3>Utilisateur</h3>
                    <div class="info-rows">
                        <div class="info-row">
                            <span class="label">Nom d'utilisateur:</span>
                            <span class="value"><?= h($team->user->username) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Email:</span>
                            <span class="value"><?= h($team->user->email) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Membre depuis:</span>
                            <span class="value"><?= $team->user->created->format('d/m/Y') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsable & Entraineur Information -->
        <?php if (!empty($team->responsable_nom_complet)): ?>
            <div class="info-section">
                <h2><i class="fas fa-user-tie"></i> Responsable & Entraîneur</h2>
                <div class="info-grid">
                    <!-- Responsable -->
                    <div class="info-card">
                        <h3>Responsable</h3>
                        <div class="info-rows">
                            <div class="info-row">
                                <span class="label">Nom complet:</span>
                                <span class="value"><?= h($team->responsable_nom_complet) ?></span>
                            </div>
                            <?php if (!empty($team->responsable_date_naissance)): ?>
                                <div class="info-row">
                                    <span class="label">Date de naissance:</span>
                                    <span class="value"><?= $team->responsable_date_naissance->format('d/m/Y') ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($team->responsable_tel)): ?>
                                <div class="info-row">
                                    <span class="label">Téléphone:</span>
                                    <span class="value"><?= h($team->responsable_tel) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($team->responsable_whatsapp)): ?>
                                <div class="info-row">
                                    <span class="label">WhatsApp:</span>
                                    <span class="value"><?= h($team->responsable_whatsapp) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- CIN Documents -->
                        <div class="documents-section">
                            <h4>Documents CIN</h4>
                            <div class="documents-grid">
                                <?php if (!empty($team->responsable_cin_recto)): ?>
                                    <div class="document-item">
                                        <label>CIN Recto:</label>
                                        <a href="<?= $this->Url->build('/' . $team->responsable_cin_recto) ?>" target="_blank" class="document-link">
                                            <i class="fas fa-file-image"></i> Voir document
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($team->responsable_cin_verso)): ?>
                                    <div class="document-item">
                                        <label>CIN Verso:</label>
                                        <a href="<?= $this->Url->build('/' . $team->responsable_cin_verso) ?>" target="_blank" class="document-link">
                                            <i class="fas fa-file-image"></i> Voir document
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Entraineur -->
                    <div class="info-card">
                        <h3>Entraîneur</h3>
                        <?php if (!empty($team->entraineur_same_as_responsable) && $team->entraineur_same_as_responsable): ?>
                            <div class="same-person-notice">
                                <i class="fas fa-info-circle"></i>
                                Même personne que le responsable
                            </div>
                        <?php elseif (!empty($team->entraineur_nom_complet)): ?>
                            <div class="info-rows">
                                <div class="info-row">
                                    <span class="label">Nom complet:</span>
                                    <span class="value"><?= h($team->entraineur_nom_complet) ?></span>
                                </div>
                                <?php if (!empty($team->entraineur_date_naissance)): ?>
                                    <div class="info-row">
                                        <span class="label">Date de naissance:</span>
                                        <span class="value"><?= $team->entraineur_date_naissance->format('d/m/Y') ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($team->entraineur_tel)): ?>
                                    <div class="info-row">
                                        <span class="label">Téléphone:</span>
                                        <span class="value"><?= h($team->entraineur_tel) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($team->entraineur_whatsapp)): ?>
                                    <div class="info-row">
                                        <span class="label">WhatsApp:</span>
                                        <span class="value"><?= h($team->entraineur_whatsapp) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- CIN Documents -->
                            <div class="documents-section">
                                <h4>Documents CIN</h4>
                                <div class="documents-grid">
                                    <?php if (!empty($team->entraineur_cin_recto)): ?>
                                        <div class="document-item">
                                            <label>CIN Recto:</label>
                                            <a href="<?= $this->Url->build('/' . $team->entraineur_cin_recto) ?>" target="_blank" class="document-link">
                                                <i class="fas fa-file-image"></i> Voir document
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($team->entraineur_cin_verso)): ?>
                                        <div class="document-item">
                                            <label>CIN Verso:</label>
                                            <a href="<?= $this->Url->build('/' . $team->entraineur_cin_verso) ?>" target="_blank" class="document-link">
                                                <i class="fas fa-file-image"></i> Voir document
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="no-data">
                                <i class="fas fa-exclamation-circle"></i>
                                Informations de l'entraîneur non renseignées
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Players List -->
        <?php
        $players = [];
        if (isset($team->basketball_teams_joueurs)) {
            $players = $team->basketball_teams_joueurs;
        } elseif (isset($team->handball_teams_joueurs)) {
            $players = $team->handball_teams_joueurs;
        } elseif (isset($team->volleyball_teams_joueurs)) {
            $players = $team->volleyball_teams_joueurs;
        } elseif (isset($team->beachvolley_teams_joueurs)) {
            $players = $team->beachvolley_teams_joueurs;
        } elseif (isset($team->joueurs)) {
            $players = $team->joueurs;
        }
        ?>
        
        <?php if (!empty($players)): ?>
            <div class="info-section">
                <h2><i class="fas fa-users"></i> Liste des joueurs (<?= is_array($players) ? count($players) : 0 ?>)</h2>
                <div class="players-table-container">
                    <table class="players-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom complet</th>
                                <th>Date de naissance</th>
                                <th>Âge</th>
                                <th>Identifiant</th>
                                <th>Taille vêtement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($players as $index => $player): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td class="player-name"><?= h($player->nom_complet) ?></td>
                                    <td><?= $player->date_naissance->format('d/m/Y') ?></td>
                                    <td>
                                        <?php
                                        $birthDate = $player->date_naissance;
                                        $today = new DateTime();
                                        $age = $today->diff($birthDate)->y;
                                        echo $age . ' ans';
                                        ?>
                                    </td>
                                    <td><?= h($player->identifiant) ?></td>
                                    <td><?= h($player->taille_vestimentaire) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <!-- Verification Actions -->
        <div class="verification-actions-section">
            <h2><i class="fas fa-clipboard-check"></i> Actions de vérification</h2>
            
            <?php if (!empty($team->verification_notes)): ?>
                <div class="current-notes">
                    <h3>Notes de vérification actuelles</h3>
                    <div class="notes-content">
                        <?= nl2br(h($team->verification_notes)) ?>
                    </div>
                    <?php if (!empty($team->verified_at) && !empty($team->verified_by)): ?>
                        <div class="notes-meta">
                            <small class="text-muted">
                                Ajouté le <?= $team->verified_at->format('d/m/Y à H:i') ?>
                                <?php if (!empty($team->verified_by)): ?>
                                    par l'administrateur #<?= $team->verified_by ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="verification-forms">
                <?php $currentStatus = $team->status ?? 'pending'; ?>
                
                <?php if ($currentStatus === 'pending'): ?>
                    <div class="verification-actions">
                        <!-- Verify Form -->
                        <div class="action-form verify-form">
                            <?= $this->Form->create(null, [
                                'url' => ['action' => 'verifyTeam', $sportType, $team->id],
                                'class' => 'verification-form'
                            ]) ?>
                            <h3><i class="fas fa-check-circle text-success"></i> Vérifier cette équipe</h3>
                            
                            <div class="form-group">
                                <?= $this->Form->control('verification_notes', [
                                    'type' => 'textarea',
                                    'label' => 'Notes de vérification (optionnel)',
                                    'placeholder' => 'Ajoutez des notes sur la vérification...',
                                    'class' => 'form-control',
                                    'rows' => 3
                                ]) ?>
                            </div>
                            
                            <div class="form-actions">
                                <?= $this->Form->button(
                                    '<i class="fas fa-check"></i> Vérifier l\'équipe',
                                    [
                                        'class' => 'btn btn-success',
                                        'escape' => false,
                                        'onclick' => 'return confirm("Confirmer la vérification de cette équipe ?")'
                                    ]
                                ) ?>
                            </div>
                            
                            <?= $this->Form->end() ?>
                        </div>

                        <!-- Reject Form -->
                        <div class="action-form reject-form">
                            <?= $this->Form->create(null, [
                                'url' => ['action' => 'rejectTeam', $sportType, $team->id],
                                'class' => 'verification-form'
                            ]) ?>
                            <h3><i class="fas fa-times-circle text-danger"></i> Rejeter cette équipe</h3>
                            
                            <div class="form-group">
                                <?= $this->Form->control('verification_notes', [
                                    'type' => 'textarea',
                                    'label' => 'Motif de rejet (requis)',
                                    'placeholder' => 'Expliquez pourquoi cette équipe est rejetée...',
                                    'class' => 'form-control',
                                    'rows' => 3,
                                    'required' => true
                                ]) ?>
                            </div>
                            
                            <div class="form-actions">
                                <?= $this->Form->button(
                                    '<i class="fas fa-times"></i> Rejeter l\'équipe',
                                    [
                                        'class' => 'btn btn-danger',
                                        'escape' => false,
                                        'onclick' => 'return confirm("Confirmer le rejet de cette équipe ?")'
                                    ]
                                ) ?>
                            </div>
                            
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Reset Form -->
                    <div class="action-form reset-form">
                        <?= $this->Form->create(null, [
                            'url' => ['action' => 'resetTeamStatus', $sportType, $team->id],
                            'class' => 'verification-form'
                        ]) ?>
                        <h3><i class="fas fa-undo text-warning"></i> Réinitialiser le statut</h3>
                        <p>Cette action remettra l'équipe en statut "En attente" et supprimera toutes les notes de vérification.</p>
                        
                        <div class="form-actions">
                            <?= $this->Form->button(
                                '<i class="fas fa-undo"></i> Réinitialiser le statut',
                                [
                                    'class' => 'btn btn-warning',
                                    'escape' => false,
                                    'onclick' => 'return confirm("Confirmer la réinitialisation du statut de cette équipe ?")'
                                ]
                            ) ?>
                        </div>
                        
                        <?= $this->Form->end() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-team-view {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left h1 {
        margin: 0 0 1rem 0;
        font-size: 2rem;
    }

    .team-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .sport-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        color: white;
        text-transform: uppercase;
    }

    .sport-badge.sport-football { background: rgba(76, 175, 80, 0.9); }
    .sport-badge.sport-basketball { background: rgba(255, 107, 53, 0.9); }
    .sport-badge.sport-handball { background: rgba(231, 76, 60, 0.9); }
    .sport-badge.sport-volleyball { background: rgba(52, 152, 219, 0.9); }
    .sport-badge.sport-beachvolley { background: rgba(243, 156, 18, 0.9); }

    .team-name {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .status-badge-large {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-badge-large.status-pending {
        background: rgba(255, 193, 7, 0.2);
        color: #856404;
        border: 2px solid rgba(255, 193, 7, 0.3);
    }

    .status-badge-large.status-verified {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 2px solid rgba(40, 167, 69, 0.3);
    }

    .status-badge-large.status-rejected {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 2px solid rgba(220, 53, 69, 0.3);
    }

    .verification-info {
        color: rgba(255, 255, 255, 0.8);
        font-style: italic;
    }

    .verification-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .info-section h2 {
        margin: 0 0 1.5rem 0;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .info-card h3 {
        margin: 0 0 1.5rem 0;
        color: #495057;
        font-size: 1.25rem;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }

    .info-rows {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .label {
        font-weight: 600;
        color: #6c757d;
        min-width: 140px;
        flex-shrink: 0;
    }

    .value {
        text-align: right;
        color: #2c3e50;
        flex: 1;
        margin-left: 1rem;
        word-break: break-word;
    }

    .reference {
        font-family: 'Courier New', monospace;
        background: #e9ecef;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .documents-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e9ecef;
    }

    .documents-section h4 {
        margin: 0 0 1rem 0;
        color: #495057;
    }

    .documents-grid {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .document-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .document-item label {
        font-weight: 500;
        color: #6c757d;
    }

    .document-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: background 0.3s;
    }

    .document-link:hover {
        background: #0056b3;
        color: white;
    }

    .same-person-notice {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background: #e7f3ff;
        border: 1px solid #b8daff;
        border-radius: 8px;
        color: #004085;
    }

    .no-data {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        color: #856404;
    }

    .players-table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .players-table {
        width: 100%;
        border-collapse: collapse;
    }

    .players-table th,
    .players-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .players-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .players-table tbody tr:hover {
        background: #f8f9fa;
    }

    .player-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.875rem;
    }

    .btn-secondary { background: #6c757d; color: white; }
    .btn-secondary:hover { background: #545b62; color: white; }

    .btn-success { background: #28a745; color: white; }
    .btn-success:hover { background: #1e7e34; color: white; }

    .btn-danger { background: #dc3545; color: white; }
    .btn-danger:hover { background: #c82333; color: white; }

    .btn-warning { background: #ffc107; color: #212529; }
    .btn-warning:hover { background: #e0a800; color: #212529; }

    /* Verification Actions Section */
    .verification-actions-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: 2rem;
    }

    .current-notes {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #007bff;
    }

    .current-notes h3 {
        margin: 0 0 1rem 0;
        color: #495057;
        font-size: 1.1rem;
    }

    .notes-content {
        background: white;
        border-radius: 6px;
        padding: 1rem;
        border: 1px solid #dee2e6;
        white-space: pre-wrap;
        word-wrap: break-word;
        margin-bottom: 0.5rem;
    }

    .notes-meta {
        text-align: right;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .verification-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .action-form {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .verify-form {
        border: 2px solid #28a745;
    }

    .reject-form {
        border: 2px solid #dc3545;
    }

    .reset-form {
        border: 2px solid #ffc107;
        max-width: 500px;
        margin: 0 auto;
    }

    .verification-form {
        padding: 2rem;
        background: white;
    }

    .verification-form h3 {
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
    }

    .text-success { color: #28a745 !important; }
    .text-danger { color: #dc3545 !important; }
    .text-warning { color: #ffc107 !important; }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 0.875rem;
        font-family: inherit;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-actions {
        text-align: center;
        padding-top: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-team-view {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .team-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .value {
            text-align: left;
            margin-left: 0;
        }

        .players-table {
            font-size: 0.875rem;
        }

        .players-table th,
        .players-table td {
            padding: 0.5rem;
        }

        .verification-actions {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .verification-actions-section {
            padding: 1rem;
        }

        .verification-form {
            padding: 1rem;
        }
    }
</style>