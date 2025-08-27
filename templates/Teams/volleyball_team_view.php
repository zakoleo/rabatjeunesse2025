<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\VolleyballTeam $team
 */

$this->assign('title', 'Équipe de Volleyball - ' . h($team->nom_equipe));
?>

<div class="volleyball-team-view container">
    <div class="team-header">
        <h1><?= h($team->nom_equipe) ?></h1>
        <div class="team-badges">
            <span class="badge badge-sport">Volleyball</span>
            <span class="badge badge-category"><?= h($team->categorie) ?></span>
            <span class="badge badge-genre"><?= h($team->genre) ?></span>
        </div>
    </div>

    <div class="team-content">
        <div class="team-info-grid">
            <!-- Informations de l'équipe -->
            <div class="info-card">
                <h3><i class="fas fa-volleyball-ball"></i> Informations de l'équipe</h3>
                <div class="info-row">
                    <span class="label">Type de volleyball:</span>
                    <span class="value"><?= h($team->type_volleyball ?? 'N/A') ?></span>
                </div>
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
                    <span class="label">Référence d'inscription:</span>
                    <span class="value reference"><?= h($team->reference_inscription) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Responsable -->
            <div class="info-card">
                <h3><i class="fas fa-user-tie"></i> Responsable</h3>
                <?php if (!empty($team->responsable_nom_complet)): ?>
                <div class="info-row">
                    <span class="label">Nom:</span>
                    <span class="value"><?= h($team->responsable_nom_complet) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Date de naissance:</span>
                    <span class="value"><?= $team->responsable_date_naissance ? $team->responsable_date_naissance->format('d/m/Y') : 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Téléphone:</span>
                    <span class="value"><?= h($team->responsable_tel) ?></span>
                </div>
                <?php if (!empty($team->responsable_whatsapp)): ?>
                <div class="info-row">
                    <span class="label">WhatsApp:</span>
                    <span class="value"><?= h($team->responsable_whatsapp) ?></span>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <p class="no-data">Informations du responsable non disponibles</p>
                <?php endif; ?>
            </div>

            <!-- Entraîneur -->
            <div class="info-card">
                <h3><i class="fas fa-whistle"></i> Entraîneur</h3>
                <?php if (!empty($team->entraineur_nom_complet)): ?>
                <div class="info-row">
                    <span class="label">Nom:</span>
                    <span class="value"><?= h($team->entraineur_nom_complet) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Date de naissance:</span>
                    <span class="value"><?= $team->entraineur_date_naissance ? $team->entraineur_date_naissance->format('d/m/Y') : 'N/A' ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Téléphone:</span>
                    <span class="value"><?= h($team->entraineur_tel) ?></span>
                </div>
                <?php if (!empty($team->entraineur_whatsapp)): ?>
                <div class="info-row">
                    <span class="label">WhatsApp:</span>
                    <span class="value"><?= h($team->entraineur_whatsapp) ?></span>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <p class="no-data">Même personne que le responsable</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Liste des joueurs -->
        <div class="players-section">
            <h3><i class="fas fa-users"></i> Liste des joueurs (<?= count($team->volleyball_teams_joueurs ?? []) ?>)</h3>
            
            <?php if (!empty($team->volleyball_teams_joueurs)): ?>
            <div class="players-grid">
                <?php foreach ($team->volleyball_teams_joueurs as $index => $joueur): ?>
                <div class="player-card">
                    <div class="player-number">#<?= $index + 1 ?></div>
                    <div class="player-info">
                        <h4><?= h($joueur->nom_complet) ?></h4>
                        <p><strong>Né le:</strong> <?= $joueur->date_naissance ? $joueur->date_naissance->format('d/m/Y') : 'N/A' ?></p>
                        <p><strong>Identifiant:</strong> <?= h($joueur->identifiant) ?></p>
                        <p><strong>Taille:</strong> <?= h($joueur->taille_vestimentaire) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="no-players">
                <p>Aucun joueur enregistré pour cette équipe.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="team-actions">
        <?= $this->Html->link(
            '<i class="fas fa-arrow-left"></i> Retour à mes équipes',
            ['controller' => 'Teams', 'action' => 'index'],
            ['class' => 'btn btn-secondary', 'escape' => false]
        ) ?>
        
        <?= $this->Html->link(
            '<i class="fas fa-edit"></i> Modifier',
            ['controller' => 'Teams', 'action' => 'editVolleyball', $team->id],
            ['class' => 'btn btn-primary', 'escape' => false]
        ) ?>
        
        <?= $this->Html->link(
            '<i class="fas fa-download"></i> Télécharger PDF',
            ['controller' => 'Teams', 'action' => 'downloadVolleyballPdf', $team->id],
            ['class' => 'btn btn-success', 'escape' => false]
        ) ?>
    </div>
</div>

<style>
.volleyball-team-view {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.team-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #e67e22;
}

.team-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.team-badges {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
}

.badge-sport {
    background-color: #e67e22;
    color: white;
}

.badge-category {
    background-color: #3498db;
    color: white;
}

.badge-genre {
    background-color: #9b59b6;
    color: white;
}

.team-content {
    margin-bottom: 3rem;
}

.team-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.info-card {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #e67e22;
}

.info-card h3 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-card h3 i {
    color: #e67e22;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #ecf0f1;
}

.info-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.label {
    font-weight: bold;
    color: #7f8c8d;
    min-width: 120px;
}

.value {
    text-align: right;
    color: #2c3e50;
    flex: 1;
    margin-left: 1rem;
}

.reference {
    font-family: 'Courier New', monospace;
    background-color: #ecf0f1;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.no-data {
    color: #95a5a6;
    font-style: italic;
}

.players-section {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #e67e22;
}

.players-section h3 {
    color: #2c3e50;
    margin-bottom: 2rem;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.players-section h3 i {
    color: #e67e22;
}

.players-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.player-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #dee2e6;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    position: relative;
}

.player-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.player-number {
    position: absolute;
    top: -10px;
    right: 15px;
    background: #e67e22;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.player-info h4 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.player-info p {
    margin-bottom: 0.5rem;
    color: #5a6c7d;
    font-size: 0.9rem;
}

.no-players {
    text-align: center;
    padding: 3rem;
    color: #95a5a6;
}

.team-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    padding-top: 2rem;
    border-top: 1px solid #dee2e6;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    .volleyball-team-view {
        padding: 1rem;
    }
    
    .team-header h1 {
        font-size: 2rem;
    }
    
    .team-info-grid {
        grid-template-columns: 1fr;
    }
    
    .players-grid {
        grid-template-columns: 1fr;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .value {
        text-align: left;
        margin-left: 0;
        margin-top: 0.5rem;
    }
    
    .team-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>