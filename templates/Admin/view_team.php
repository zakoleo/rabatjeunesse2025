<?php
/**
 * @var \App\View\AppView $this
 * @var object $team
 * @var string $sport
 * @var array $players
 * @var string $categoryName
 * @var string $districtName
 * @var string $organisationName
 */

$this->assign('title', 'Détails de l\'équipe - ' . h($team->nom_equipe));
?>

<div class="team-detail-page">
    <!-- Header -->
    <div class="detail-header">
        <div class="header-left">
            <div class="team-name">
                <h1><?= h($team->nom_equipe) ?></h1>
                <div class="team-meta">
                    <span class="sport-badge sport-<?= strtolower($sport) ?>">
                        <i class="fas fa-<?= $sport === 'football' ? 'futbol' : ($sport === 'basketball' ? 'basketball-ball' : ($sport === 'handball' ? 'handball' : ($sport === 'volleyball' ? 'volleyball-ball' : 'umbrella-beach'))) ?>"></i>
                        <?= ucfirst($sport) ?>
                    </span>
                    <span class="team-id">ID: <?= $team->id ?></span>
                    <span class="registration-date">
                        <i class="fas fa-calendar"></i>
                        Inscrite le <?= $team->created->format('d/m/Y à H:i') ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="header-actions">
            <div class="status-section">
                <div class="current-status">
                    <span class="status-label">Statut actuel:</span>
                    <span class="status-badge status-<?= $team->status ?? 'pending' ?>">
                        <?php
                        $statusLabels = [
                            'pending' => 'En attente',
                            'verified' => 'Vérifiée',
                            'rejected' => 'Rejetée'
                        ];
                        echo $statusLabels[$team->status ?? 'pending'] ?? 'Inconnu';
                        ?>
                    </span>
                </div>
                
                <div class="status-actions">
                    <select class="status-change-select" id="statusSelect">
                        <option value="">Changer le statut...</option>
                        <option value="pending" <?= ($team->status ?? 'pending') === 'pending' ? 'disabled' : '' ?>>
                            Mettre en attente
                        </option>
                        <option value="verified" <?= ($team->status ?? 'pending') === 'verified' ? 'disabled' : '' ?>>
                            Vérifier l'équipe
                        </option>
                        <option value="rejected" <?= ($team->status ?? 'pending') === 'rejected' ? 'disabled' : '' ?>>
                            Rejeter l'équipe
                        </option>
                    </select>
                    <button class="btn btn-primary" id="changeStatusBtn" disabled>
                        <i class="fas fa-save"></i> Changer
                    </button>
                </div>
            </div>
            
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour à la liste', 
                ['action' => 'teams'], 
                ['class' => 'btn btn-secondary', 'escape' => false]) ?>
        </div>
    </div>

    <div class="detail-content">
        <div class="detail-grid">
            <!-- Team Information -->
            <div class="detail-section">
                <div class="section-header">
                    <h2><i class="fas fa-info-circle"></i> Informations de l'équipe</h2>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nom de l'équipe:</label>
                            <span><?= h($team->nom_equipe) ?></span>
                        </div>
                        <?php if (!empty($categoryName)): ?>
                            <div class="info-item">
                                <label>Catégorie:</label>
                                <span><?= h($categoryName) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Genre:</label>
                            <span><?= h($team->genre ?? 'Non spécifié') ?></span>
                        </div>
                        <?php if ($sport === 'football' && !empty($team->type_football)): ?>
                            <div class="info-item">
                                <label>Type de football:</label>
                                <span><?= h($team->type_football) ?></span>
                            </div>
                        <?php elseif ($sport === 'basketball' && !empty($team->type_basketball)): ?>
                            <div class="info-item">
                                <label>Type de basketball:</label>
                                <span><?= h($team->type_basketball) ?></span>
                            </div>
                        <?php elseif ($sport === 'handball' && !empty($team->type_handball)): ?>
                            <div class="info-item">
                                <label>Type de handball:</label>
                                <span><?= h($team->type_handball) ?></span>
                            </div>
                        <?php elseif ($sport === 'volleyball' && !empty($team->type_volleyball)): ?>
                            <div class="info-item">
                                <label>Type de volleyball:</label>
                                <span><?= h($team->type_volleyball) ?></span>
                            </div>
                        <?php elseif ($sport === 'beachvolley' && !empty($team->type_beachvolley)): ?>
                            <div class="info-item">
                                <label>Type de beach volleyball:</label>
                                <span><?= h($team->type_beachvolley) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($districtName)): ?>
                            <div class="info-item">
                                <label>District:</label>
                                <span><?= h($districtName) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($organisationName)): ?>
                            <div class="info-item">
                                <label>Organisation:</label>
                                <span><?= h($organisationName) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Adresse:</label>
                            <span><?= h($team->adresse ?? 'Non spécifiée') ?></span>
                        </div>
                        <?php if (!empty($team->reference_inscription)): ?>
                            <div class="info-item">
                                <label>Référence:</label>
                                <span><?= h($team->reference_inscription) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="detail-section">
                <div class="section-header">
                    <h2><i class="fas fa-user"></i> Propriétaire</h2>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Utilisateur:</label>
                            <span><?= h($team->user->username ?? 'Non assigné') ?></span>
                        </div>
                        <div class="info-item">
                            <label>Email:</label>
                            <span><?= h($team->user->email ?? 'Non spécifié') ?></span>
                        </div>
                        <div class="info-item">
                            <label>Compte créé le:</label>
                            <span><?= $team->user->created ? $team->user->created->format('d/m/Y') : 'Inconnu' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsible Information -->
            <div class="detail-section">
                <div class="section-header">
                    <h2><i class="fas fa-user-tie"></i> Responsable de l'équipe</h2>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nom complet:</label>
                            <span><?= h($team->responsable_nom_complet ?? 'Non spécifié') ?></span>
                        </div>
                        <div class="info-item">
                            <label>Date de naissance:</label>
                            <span><?= $team->responsable_date_naissance ? $team->responsable_date_naissance->format('d/m/Y') : 'Non spécifiée' ?></span>
                        </div>
                        <div class="info-item">
                            <label>Téléphone:</label>
                            <span><?= h($team->responsable_tel ?? 'Non spécifié') ?></span>
                        </div>
                        <div class="info-item">
                            <label>WhatsApp:</label>
                            <span><?= h($team->responsable_whatsapp ?? 'Non spécifié') ?></span>
                        </div>
                    </div>
                    
                    <div class="documents-section">
                        <h4>Documents CIN</h4>
                        <div class="documents-grid">
                            <?php if (!empty($team->responsable_cin_recto)): ?>
                                <div class="document-item">
                                    <div class="document-header">
                                        <i class="fas fa-id-card"></i>
                                        <span>CIN Responsable (Recto)</span>
                                        <button class="btn btn-sm btn-outline toggle-image" onclick="toggleImage('responsable-recto')">
                                            <i class="fas fa-eye"></i> Voir
                                        </button>
                                    </div>
                                    <div class="document-image" id="responsable-recto" style="display: none;">
                                        <img src="/uploads/<?= h($team->responsable_cin_recto) ?>" alt="CIN Responsable Recto" class="document-img" onclick="openModal(this)">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($team->responsable_cin_verso)): ?>
                                <div class="document-item">
                                    <div class="document-header">
                                        <i class="fas fa-id-card"></i>
                                        <span>CIN Responsable (Verso)</span>
                                        <button class="btn btn-sm btn-outline toggle-image" onclick="toggleImage('responsable-verso')">
                                            <i class="fas fa-eye"></i> Voir
                                        </button>
                                    </div>
                                    <div class="document-image" id="responsable-verso" style="display: none;">
                                        <img src="/uploads/<?= h($team->responsable_cin_verso) ?>" alt="CIN Responsable Verso" class="document-img" onclick="openModal(this)">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coach Information -->
            <?php if (!$team->entraineur_same_as_responsable): ?>
                <div class="detail-section">
                    <div class="section-header">
                        <h2><i class="fas fa-whistle"></i> Entraîneur</h2>
                    </div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Nom complet:</label>
                                <span><?= h($team->entraineur_nom_complet ?? 'Non spécifié') ?></span>
                            </div>
                            <div class="info-item">
                                <label>Date de naissance:</label>
                                <span><?= $team->entraineur_date_naissance ? $team->entraineur_date_naissance->format('d/m/Y') : 'Non spécifiée' ?></span>
                            </div>
                            <div class="info-item">
                                <label>Téléphone:</label>
                                <span><?= h($team->entraineur_tel ?? 'Non spécifié') ?></span>
                            </div>
                            <div class="info-item">
                                <label>WhatsApp:</label>
                                <span><?= h($team->entraineur_whatsapp ?? 'Non spécifié') ?></span>
                            </div>
                        </div>
                        
                        <div class="documents-section">
                            <h4>Documents CIN</h4>
                            <div class="documents-grid">
                                <?php if (!empty($team->entraineur_cin_recto)): ?>
                                    <div class="document-item">
                                        <div class="document-header">
                                            <i class="fas fa-id-card"></i>
                                            <span>CIN Entraîneur (Recto)</span>
                                            <button class="btn btn-sm btn-outline toggle-image" onclick="toggleImage('entraineur-recto')">
                                                <i class="fas fa-eye"></i> Voir
                                            </button>
                                        </div>
                                        <div class="document-image" id="entraineur-recto" style="display: none;">
                                            <img src="/uploads/<?= h($team->entraineur_cin_recto) ?>" alt="CIN Entraîneur Recto" class="document-img" onclick="openModal(this)">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($team->entraineur_cin_verso)): ?>
                                    <div class="document-item">
                                        <div class="document-header">
                                            <i class="fas fa-id-card"></i>
                                            <span>CIN Entraîneur (Verso)</span>
                                            <button class="btn btn-sm btn-outline toggle-image" onclick="toggleImage('entraineur-verso')">
                                                <i class="fas fa-eye"></i> Voir
                                            </button>
                                        </div>
                                        <div class="document-image" id="entraineur-verso" style="display: none;">
                                            <img src="/uploads/<?= h($team->entraineur_cin_verso) ?>" alt="CIN Entraîneur Verso" class="document-img" onclick="openModal(this)">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="detail-section">
                    <div class="section-header">
                        <h2><i class="fas fa-whistle"></i> Entraîneur</h2>
                    </div>
                    <div class="section-content">
                        <div class="info-notice">
                            <i class="fas fa-info-circle"></i>
                            L'entraîneur est la même personne que le responsable
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Players List -->
            <div class="detail-section full-width">
                <div class="section-header">
                    <h2><i class="fas fa-users"></i> Liste des joueurs (<?= count($players) ?>)</h2>
                </div>
                <div class="section-content">
                    <?php if (empty($players)): ?>
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Aucun joueur enregistré</p>
                        </div>
                    <?php else: ?>
                        <div class="players-table-container">
                            <table class="players-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom complet</th>
                                        <th>Date de naissance</th>
                                        <th>Identifiant</th>
                                        <th>Taille</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($players as $index => $player): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= h($player->nom_complet ?? $player->full_name ?? 'Non spécifié') ?></td>
                                            <td>
                                                <?php
                                                $birthDate = $player->date_naissance ?? $player->birth_date ?? null;
                                                echo $birthDate ? $birthDate->format('d/m/Y') : 'Non spécifiée';
                                                ?>
                                            </td>
                                            <td><?= h($player->identifiant ?? $player->identifier ?? 'Non spécifié') ?></td>
                                            <td><?= h($player->taille_vestimentaire ?? $player->jersey_size ?? 'Non spécifiée') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Verification Notes -->
            <?php if (!empty($team->verification_notes) || $team->status === 'rejected'): ?>
                <div class="detail-section full-width">
                    <div class="section-header">
                        <h2><i class="fas fa-sticky-note"></i> Notes de vérification</h2>
                    </div>
                    <div class="section-content">
                        <div class="notes-section">
                            <?php if (!empty($team->verification_notes)): ?>
                                <p><?= nl2br(h($team->verification_notes)) ?></p>
                            <?php else: ?>
                                <p class="text-muted">Aucune note de vérification</p>
                            <?php endif; ?>
                            
                            <div class="notes-form">
                                <textarea id="verificationNotes" class="form-control" rows="3" placeholder="Ajouter une note de vérification..."><?= h($team->verification_notes ?? '') ?></textarea>
                                <button class="btn btn-primary" id="saveNotesBtn">
                                    <i class="fas fa-save"></i> Sauvegarder les notes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.team-detail-page {
    max-width: none;
    margin: 0;
    padding: 0;
}

.detail-header {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.team-name h1 {
    color: #1e293b;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
}

.team-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.sport-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
}

.sport-football { background: #22c55e; }
.sport-basketball { background: #f97316; }
.sport-handball { background: #06b6d4; }
.sport-volleyball { background: #8b5cf6; }
.sport-beachvolley { background: #eab308; color: #1f2937; }

.team-id {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
}

.registration-date {
    color: #64748b;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-width: 300px;
}

.status-section {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.current-status {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.status-label {
    font-weight: 600;
    color: #374151;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fbbf24;
}

.status-verified {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #34d399;
}

.status-rejected {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #f87171;
}

.status-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.status-change-select {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.9rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.detail-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.detail-section.full-width {
    grid-column: 1 / -1;
}

.section-header {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.section-header h2 {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-content {
    padding: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
}

.info-item span {
    color: #1f2937;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-notice {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    color: #1e40af;
    font-weight: 500;
}

.documents-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.documents-section h4 {
    color: #374151;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.document-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.players-table-container {
    overflow-x: auto;
}

.players-table {
    width: 100%;
    border-collapse: collapse;
}

.players-table th {
    background: #f8fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
}

.players-table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    color: #1f2937;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.notes-form {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
    align-items: flex-end;
}

.notes-form textarea {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    resize: vertical;
    font-family: inherit;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.9rem;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #2563eb;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
}

.btn-outline {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #374151;
}

.btn-outline:hover {
    background: #f9fafb;
}

/* Inline Document Display Styles */
.document-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.document-header span {
    flex: 1;
    font-weight: 500;
    color: #374151;
}

.toggle-image {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.75rem !important;
    border: 1px solid #d1d5db;
    background: white;
    color: #374151;
    transition: all 0.2s;
}

.toggle-image:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.toggle-image.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.document-image {
    margin-top: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.document-img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.2s;
}

.document-img:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

/* Modal Styles */
.image-modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 90%;
    max-height: 90%;
    border-radius: 8px;
    overflow: hidden;
}

.modal-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 25px;
    color: #fff;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
    z-index: 10001;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s;
}

.modal-close:hover {
    background: rgba(0, 0, 0, 0.8);
}

@media (max-width: 1024px) {
    .detail-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        min-width: auto;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .notes-form {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('statusSelect');
    const changeStatusBtn = document.getElementById('changeStatusBtn');
    const saveNotesBtn = document.getElementById('saveNotesBtn');
    const verificationNotes = document.getElementById('verificationNotes');
    
    // Enable/disable change status button
    statusSelect.addEventListener('change', function() {
        changeStatusBtn.disabled = !this.value;
    });
    
    // Change status functionality
    changeStatusBtn.addEventListener('click', function() {
        const newStatus = statusSelect.value;
        if (!newStatus) return;
        
        const statusLabels = {
            'pending': 'en attente',
            'verified': 'vérifiée',
            'rejected': 'rejetée'
        };
        
        if (confirm(`Êtes-vous sûr de vouloir marquer cette équipe comme ${statusLabels[newStatus]} ?`)) {
            updateTeamStatus(newStatus);
        }
    });
    
    // Save notes functionality
    if (saveNotesBtn) {
        saveNotesBtn.addEventListener('click', function() {
            const notes = verificationNotes.value;
            saveVerificationNotes(notes);
        });
    }
    
    function getStatusUpdateUrl(sport) {
        const baseUrl = '<?= $this->Url->build("/") ?>';
        switch(sport.toLowerCase()) {
            case 'football':
                return `${baseUrl}admin/update-football-team-status`;
            case 'basketball':
                return `${baseUrl}admin/update-basketball-team-status`;
            case 'handball':
                return `${baseUrl}admin/update-handball-team-status`;
            case 'volleyball':
                return `${baseUrl}admin/update-volleyball-team-status`;
            case 'beachvolley':
                return `${baseUrl}admin/update-beachvolley-team-status`;
            default:
                return `${baseUrl}admin/update-team-status`;
        }
    }
    
    function updateTeamStatus(newStatus) {
        changeStatusBtn.disabled = true;
        changeStatusBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
        
        const sport = '<?= strtolower($sport) ?>';
        const updateUrl = getStatusUpdateUrl(sport);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                team_id: <?= $team->id ?>,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the current status display
                const statusBadge = document.querySelector('.status-badge');
                statusBadge.className = `status-badge status-${newStatus}`;
                
                const statusLabels = {
                    'pending': 'En attente',
                    'verified': 'Vérifiée',
                    'rejected': 'Rejetée'
                };
                statusBadge.textContent = statusLabels[newStatus];
                
                // Reset the select and button
                statusSelect.value = '';
                changeStatusBtn.disabled = true;
                changeStatusBtn.innerHTML = '<i class="fas fa-save"></i> Changer';
                
                showNotification('Statut mis à jour avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
                changeStatusBtn.disabled = false;
                changeStatusBtn.innerHTML = '<i class="fas fa-save"></i> Changer';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur de connexion', 'error');
            changeStatusBtn.disabled = false;
            changeStatusBtn.innerHTML = '<i class="fas fa-save"></i> Changer';
        });
    }
    
    function getNotesUpdateUrl(sport) {
        const baseUrl = '<?= $this->Url->build("/") ?>';
        switch(sport.toLowerCase()) {
            case 'football':
                return `${baseUrl}admin/save-football-verification-notes`;
            case 'basketball':
                return `${baseUrl}admin/save-basketball-verification-notes`;
            case 'handball':
                return `${baseUrl}admin/save-handball-verification-notes`;
            case 'volleyball':
                return `${baseUrl}admin/save-volleyball-verification-notes`;
            case 'beachvolley':
                return `${baseUrl}admin/save-beachvolley-verification-notes`;
            default:
                return `${baseUrl}admin/save-verification-notes`;
        }
    }
    
    function saveVerificationNotes(notes) {
        saveNotesBtn.disabled = true;
        saveNotesBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
        
        const sport = '<?= strtolower($sport) ?>';
        const notesUrl = getNotesUpdateUrl(sport);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch(notesUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                team_id: <?= $team->id ?>,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Notes sauvegardées avec succès', 'success');
            } else {
                showNotification(data.message || 'Erreur lors de la sauvegarde', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur de connexion', 'error');
        })
        .finally(() => {
            saveNotesBtn.disabled = false;
            saveNotesBtn.innerHTML = '<i class="fas fa-save"></i> Sauvegarder les notes';
        });
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 2rem;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            ${type === 'success' ? 'background: #10b981;' : 'background: #ef4444;'}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 4000);
    }
});
</script>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img id="modalImage" class="modal-image" src="" alt="">
    </div>
</div>

<script>
    function toggleImage(imageId) {
        const imageContainer = document.getElementById(imageId);
        const button = event.target.closest('.toggle-image');
        const icon = button.querySelector('i');
        
        if (imageContainer.style.display === 'none' || imageContainer.style.display === '') {
            imageContainer.style.display = 'block';
            button.classList.add('active');
            icon.className = 'fas fa-eye-slash';
            button.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer';
        } else {
            imageContainer.style.display = 'none';
            button.classList.remove('active');
            icon.className = 'fas fa-eye';
            button.innerHTML = '<i class="fas fa-eye"></i> Voir';
        }
    }

    function openModal(img) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = 'block';
        modalImg.src = img.src;
        modalImg.alt = img.alt;
        
        // Close modal when clicking outside the image
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>