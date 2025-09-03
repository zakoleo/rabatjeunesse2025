<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 * @var string $sport
 * @var array $players
 * @var string $categoryName
 * @var string $districtName
 * @var string $organisationName
 */

$this->assign('title', 'Détails de l\'équipe - ' . h($team->nom_equipe));
?>

<div class="team-details-page">
    <!-- Header Section -->
    <div class="details-header">
        <div class="header-content">
            <div class="team-title">
                <h1><?= h($team->nom_equipe) ?></h1>
                <div class="team-meta">
                    <span class="sport-badge sport-<?= strtolower($sport) ?>"><?= h(ucfirst($sport)) ?></span>
                    <span class="team-id">ID: <?= $team->id ?></span>
                    <span class="creation-date">Inscrit le <?= $team->created->format('d/m/Y à H:i') ?></span>
                </div>
            </div>
            <div class="header-actions">
                <div class="status-control">
                    <label for="team-status">Statut de l'équipe:</label>
                    <select id="team-status" class="form-control status-select" data-team-id="<?= $team->id ?>" data-sport="<?= strtolower($sport) ?>">
                        <option value="pending" <?= ($team->status ?? 'pending') === 'pending' ? 'selected' : '' ?>>En attente</option>
                        <option value="verified" <?= ($team->status ?? 'pending') === 'verified' ? 'selected' : '' ?>>Vérifiée</option>
                        <option value="rejected" <?= ($team->status ?? 'pending') === 'rejected' ? 'selected' : '' ?>>Rejetée</option>
                    </select>
                </div>
                <button class="btn btn-secondary" onclick="goBackToTeams()">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </button>
            </div>
        </div>
    </div>

    <div class="details-content">
        <!-- Team Information Card -->
        <div class="info-card">
            <div class="card-header">
                <h2><i class="fas fa-users"></i> Informations de l'équipe</h2>
                <span class="status-badge status-<?= $team->status ?? 'pending' ?>" id="current-status">
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
            <div class="card-body">
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
                    
                    <?php if (!empty($team->genre)): ?>
                    <div class="info-item">
                        <label>Genre:</label>
                        <span><?= h($team->genre) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->type_football)): ?>
                    <div class="info-item">
                        <label>Type de football:</label>
                        <span><?= h($team->type_football) ?></span>
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
                    
                    <?php if (!empty($team->adresse)): ?>
                    <div class="info-item">
                        <label>Adresse postale:</label>
                        <span><?= h($team->adresse) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->contact_telephone)): ?>
                    <div class="info-item">
                        <label>Téléphone:</label>
                        <span><?= h($team->contact_telephone) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->contact_email)): ?>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?= h($team->contact_email) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Responsible/Manager Information -->
        <?php if (!empty($team->user)): ?>
        <div class="info-card">
            <div class="card-header">
                <h2><i class="fas fa-user-tie"></i> Responsable</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nom d'utilisateur:</label>
                        <span><?= h($team->user->username) ?></span>
                    </div>
                    <?php if (!empty($team->user->email)): ?>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?= h($team->user->email) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($team->responsable_nom_complet)): ?>
                    <div class="info-item">
                        <label>Nom complet:</label>
                        <span><?= h($team->responsable_nom_complet) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($team->responsable_date_naissance)): ?>
                    <div class="info-item">
                        <label>Date de naissance:</label>
                        <span><?= is_object($team->responsable_date_naissance) ? $team->responsable_date_naissance->format('d/m/Y') : h($team->responsable_date_naissance) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($team->responsable_tel)): ?>
                    <div class="info-item">
                        <label>Téléphone:</label>
                        <span><?= h($team->responsable_tel) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($team->responsable_whatsapp)): ?>
                    <div class="info-item">
                        <label>WhatsApp:</label>
                        <span><?= h($team->responsable_whatsapp) ?></span>
                    </div>
                    <?php endif; ?>
                    <!-- Fallback for older field names -->
                    <?php if (!empty($team->responsable_nom) && empty($team->responsable_nom_complet)): ?>
                    <div class="info-item">
                        <label>Nom du responsable:</label>
                        <span><?= h($team->responsable_nom) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($team->responsable_telephone) && empty($team->responsable_tel)): ?>
                    <div class="info-item">
                        <label>Téléphone:</label>
                        <span><?= h($team->responsable_telephone) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Coach Information -->
        <?php if (!empty($team->entraineur_nom_complet) || !empty($team->entraineur_nom) || !empty($team->entraineur_same_as_responsable)): ?>
        <div class="info-card">
            <div class="card-header">
                <h2><i class="fas fa-whistle"></i> Entraîneur</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <?php if (!empty($team->entraineur_same_as_responsable)): ?>
                    <div class="info-item">
                        <label>Information:</label>
                        <span class="badge-info">Identique au responsable</span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->entraineur_nom_complet)): ?>
                    <div class="info-item">
                        <label>Nom complet:</label>
                        <span><?= h($team->entraineur_nom_complet) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->entraineur_date_naissance)): ?>
                    <div class="info-item">
                        <label>Date de naissance:</label>
                        <span><?= is_object($team->entraineur_date_naissance) ? $team->entraineur_date_naissance->format('d/m/Y') : h($team->entraineur_date_naissance) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->entraineur_tel)): ?>
                    <div class="info-item">
                        <label>Téléphone:</label>
                        <span><?= h($team->entraineur_tel) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->entraineur_whatsapp)): ?>
                    <div class="info-item">
                        <label>WhatsApp:</label>
                        <span><?= h($team->entraineur_whatsapp) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Fallback for older field names -->
                    <?php if (!empty($team->entraineur_nom) && empty($team->entraineur_nom_complet)): ?>
                    <div class="info-item">
                        <label>Nom:</label>
                        <span><?= h($team->entraineur_nom) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->entraineur_telephone) && empty($team->entraineur_tel)): ?>
                    <div class="info-item">
                        <label>Téléphone:</label>
                        <span><?= h($team->entraineur_telephone) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->entraineur_email)): ?>
                    <div class="info-item">
                        <label>Email:</label>
                        <span><?= h($team->entraineur_email) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Players List -->
        <?php 
        // Debug output
        error_log('Template - Players variable: ' . (isset($players) ? 'set' : 'not set'));
        if (isset($players)) {
            error_log('Template - Players count: ' . count($players));
            if (!empty($players)) {
                error_log('Template - First player: ' . json_encode($players[0]));
            }
        }
        ?>
        <?php if (!empty($players)): ?>
        <div class="info-card players-card">
            <div class="card-header">
                <h2><i class="fas fa-running"></i> Joueurs (<?= count($players) ?>)</h2>
            </div>
            <div class="card-body">
                <div class="players-table-container">
                    <table class="players-table">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Date de naissance</th>
                                <th>Âge</th>
                                <th>Identifiant</th>
                                <?php if ($sport === 'football'): ?>
                                <th>Position</th>
                                <?php endif; ?>
                                <th>Taille vestimentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($players as $player): ?>
                            <tr>
                                <td><?= h($player->nom_complet ?? '') ?></td>
                                <td>
                                    <?php 
                                    $birthDate = $player->date_naissance ?? null;
                                    if ($birthDate) {
                                        if (is_string($birthDate)) {
                                            echo date('d/m/Y', strtotime($birthDate));
                                        } else {
                                            echo $birthDate->format('d/m/Y');
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if ($birthDate) {
                                        // Convert CakePHP Date/DateTime to PHP DateTime
                                        if (is_string($birthDate)) {
                                            $birth = new DateTime($birthDate);
                                        } elseif (is_object($birthDate) && method_exists($birthDate, 'format')) {
                                            $birth = new DateTime($birthDate->format('Y-m-d'));
                                        } else {
                                            $birth = $birthDate;
                                        }
                                        
                                        $now = new DateTime();
                                        $age = $now->diff($birth);
                                        echo $age->y . ' ans';
                                    }
                                    ?>
                                </td>
                                <td><?= h($player->identifiant ?? '') ?></td>
                                <?php if ($sport === 'football'): ?>
                                <td><?= h($player->position ?? '') ?></td>
                                <?php endif; ?>
                                <td><?= h($player->taille_vestimentaire ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="info-card players-card">
            <div class="card-header">
                <h2><i class="fas fa-running"></i> Joueurs</h2>
            </div>
            <div class="card-body">
                <div class="no-players">
                    <i class="fas fa-info-circle"></i>
                    <p>Aucun joueur trouvé pour cette équipe.</p>
                    <p><small>Sport: <?= h($sport) ?> | Team ID: <?= h($team->id) ?></small></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Documents -->
        <div class="info-card">
            <div class="card-header">
                <h2><i class="fas fa-file-alt"></i> Documents</h2>
            </div>
            <div class="card-body">
                <div class="documents-grid">
                    <!-- Responsable Documents -->
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
                            <img src="<?= $this->Url->build('/' . $team->responsable_cin_recto) ?>" alt="CIN Responsable Recto" class="document-img">
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
                            <img src="<?= $this->Url->build('/' . $team->responsable_cin_verso) ?>" alt="CIN Responsable Verso" class="document-img">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Entraineur Documents -->
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
                            <img src="<?= $this->Url->build('/' . $team->entraineur_cin_recto) ?>" alt="CIN Entraîneur Recto" class="document-img">
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
                            <img src="<?= $this->Url->build('/' . $team->entraineur_cin_verso) ?>" alt="CIN Entraîneur Verso" class="document-img">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Legacy Documents -->
                    <?php if (!empty($team->document_autorisation)): ?>
                    <div class="document-item">
                        <div class="document-header">
                            <i class="fas fa-file-pdf"></i>
                            <span>Autorisation</span>
                            <button class="btn btn-sm btn-outline toggle-image" onclick="toggleDocument('autorisation', '<?= pathinfo($team->document_autorisation, PATHINFO_EXTENSION) ?>')">
                                <i class="fas fa-eye"></i> Voir
                            </button>
                        </div>
                        <div class="document-image" id="autorisation" style="display: none;">
                            <?php $ext = strtolower(pathinfo($team->document_autorisation, PATHINFO_EXTENSION)); ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                <img src="<?= $this->Url->build('/' . $team->document_autorisation) ?>" alt="Document Autorisation" class="document-img">
                            <?php else: ?>
                                <iframe src="<?= $this->Url->build('/' . $team->document_autorisation) ?>" class="document-pdf" frameborder="0"></iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->document_licence)): ?>
                    <div class="document-item">
                        <div class="document-header">
                            <i class="fas fa-file-pdf"></i>
                            <span>Licence</span>
                            <button class="btn btn-sm btn-outline toggle-image" onclick="toggleDocument('licence', '<?= pathinfo($team->document_licence, PATHINFO_EXTENSION) ?>')">
                                <i class="fas fa-eye"></i> Voir
                            </button>
                        </div>
                        <div class="document-image" id="licence" style="display: none;">
                            <?php $ext = strtolower(pathinfo($team->document_licence, PATHINFO_EXTENSION)); ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                <img src="<?= $this->Url->build('/' . $team->document_licence) ?>" alt="Document Licence" class="document-img">
                            <?php else: ?>
                                <iframe src="<?= $this->Url->build('/' . $team->document_licence) ?>" class="document-pdf" frameborder="0"></iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team->document_medical)): ?>
                    <div class="document-item">
                        <div class="document-header">
                            <i class="fas fa-file-pdf"></i>
                            <span>Certificat médical</span>
                            <button class="btn btn-sm btn-outline toggle-image" onclick="toggleDocument('medical', '<?= pathinfo($team->document_medical, PATHINFO_EXTENSION) ?>')">
                                <i class="fas fa-eye"></i> Voir
                            </button>
                        </div>
                        <div class="document-image" id="medical" style="display: none;">
                            <?php $ext = strtolower(pathinfo($team->document_medical, PATHINFO_EXTENSION)); ?>
                            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                <img src="<?= $this->Url->build('/' . $team->document_medical) ?>" alt="Certificat Médical" class="document-img">
                            <?php else: ?>
                                <iframe src="<?= $this->Url->build('/' . $team->document_medical) ?>" class="document-pdf" frameborder="0"></iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (empty($team->responsable_cin_recto) && empty($team->responsable_cin_verso) && 
                             empty($team->entraineur_cin_recto) && empty($team->entraineur_cin_verso) &&
                             empty($team->document_autorisation) && empty($team->document_licence) && 
                             empty($team->document_medical)): ?>
                    <div class="no-documents">
                        <i class="fas fa-info-circle"></i>
                        <span>Aucun document disponible</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Verification Notes -->
        <div class="info-card">
            <div class="card-header">
                <h2><i class="fas fa-clipboard-list"></i> Notes de vérification</h2>
            </div>
            <div class="card-body">
                <div class="notes-section">
                    <textarea id="verification-notes" class="form-control" rows="4" placeholder="Ajouter des notes de vérification..."><?= h($team->verification_notes ?? '') ?></textarea>
                    <button id="save-notes" class="btn btn-primary" data-team-id="<?= $team->id ?>" data-sport="<?= strtolower($sport) ?>">
                        <i class="fas fa-save"></i> Sauvegarder les notes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.team-details-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: #f8fafc;
    min-height: 100vh;
    font-size: 18px;
}

.details-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.team-title h1 {
    color: #1e293b;
    margin: 0 0 1rem 0;
    font-size: 2.5rem;
    font-weight: 700;
}

.team-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.sport-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
}

.sport-football { background: #28a745; }
.sport-basketball { background: #fd7e14; }
.sport-handball { background: #17a2b8; }
.sport-volleyball { background: #6610f2; }
.sport-beachvolley { background: #ffc107; color: #212529; }

.team-id {
    background: #e9ecef;
    color: #6c757d;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.creation-date {
    color: #6c757d;
    font-size: 1.2rem;
}

.header-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: flex-end;
}

.status-control {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-end;
}

.status-control label {
    font-weight: 600;
    color: #374151;
    font-size: 1.2rem;
}

.status-select {
    padding: 0.75rem 1rem;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    font-weight: 500;
    min-width: 150px;
    font-size: 1.2rem;
}

.details-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    background: #f8fafc;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-header i {
    color: #3b82f6;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-verified {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.card-body {
    padding: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
    font-size: 1.2rem;
}

.info-item span {
    color: #1f2937;
    font-size: 1.4rem;
    padding: 0.5rem 0;
    line-height: 1.5;
}

.players-table-container {
    overflow-x: auto;
}

.players-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.players-table th {
    background: #f8fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #e5e7eb;
    color: #374151;
    font-size: 1.2rem;
}

.players-table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    color: #1f2937;
    font-size: 1.2rem;
    line-height: 1.5;
}

.players-table tr:hover {
    background: #f9fafb;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.document-item {
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    font-size: 1.2rem;
}

.document-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.document-image {
    margin-top: 1rem;
    text-align: center;
}

.document-img {
    max-width: 100%;
    max-height: 500px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: transform 0.3s ease;
}

.document-img:hover {
    transform: scale(1.05);
}

.document-pdf {
    width: 100%;
    height: 500px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
}

.toggle-image {
    margin-left: auto;
}

.toggle-image.active i {
    transform: rotate(180deg);
}

.document-item i {
    color: #dc2626;
    font-size: 1.5rem;
}

.document-item i.fa-id-card {
    color: #3b82f6;
}

.badge-info {
    background: #dbeafe;
    color: #1e40af;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 500;
}

.no-documents {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 2rem;
    color: #6b7280;
    font-style: italic;
    font-size: 1.2rem;
}

.no-documents i {
    color: #9ca3af;
    font-size: 1.2rem;
}

.no-players {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.no-players i {
    color: #9ca3af;
    font-size: 2rem;
    margin-bottom: 1rem;
}

.no-players p {
    margin: 0.5rem 0;
    font-size: 1.2rem;
}

.no-players small {
    color: #9ca3af;
    font-size: 1rem;
}

.notes-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notes-section textarea {
    padding: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-family: inherit;
    resize: vertical;
    font-size: 1.2rem;
    line-height: 1.5;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1.2rem;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-outline {
    background: transparent;
    border: 1px solid #6b7280;
    color: #6b7280;
}

.btn-outline:hover {
    background: #6b7280;
    color: white;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .team-details-page {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .team-title h1 {
        font-size: 2rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status change functionality
    const statusSelect = document.getElementById('team-status');
    
    // Store the original value
    const originalValue = statusSelect.value;
    
    statusSelect.addEventListener('change', function() {
        const teamId = this.dataset.teamId;
        const sport = this.dataset.sport;
        const newStatus = this.value;
        
        console.log('Status change - Team ID:', teamId, 'Sport:', sport, 'New Status:', newStatus);
        
        if (confirm('Êtes-vous sûr de vouloir changer le statut de cette équipe ?')) {
            updateTeamStatus(teamId, sport, newStatus, this);
        } else {
            // Reset to original value if cancelled
            this.value = originalValue;
        }
    });
    
    // Save notes functionality
    document.getElementById('save-notes').addEventListener('click', function() {
        const teamId = this.dataset.teamId;
        const sport = this.dataset.sport;
        const notes = document.getElementById('verification-notes').value;
        
        saveVerificationNotes(teamId, sport, notes, this);
    });
});

// Helper function to get CSRF token
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('CSRF Token:', token ? 'Found' : 'Not found');
    return token;
}

function goBackToTeams() {
    // Get the current sport from the URL or from the page
    const sport = '<?= strtolower($sport) ?>';
    const baseUrl = '<?= $this->Url->build("/") ?>';
    const teamsUrl = `${baseUrl}admin/teams?sport=${sport}`;
    
    window.location.href = teamsUrl;
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

function updateTeamStatus(teamId, sport, newStatus, selectElement) {
    console.log('updateTeamStatus called with:', { teamId, sport, newStatus });
    
    selectElement.disabled = true;
    
    const updateUrl = getStatusUpdateUrl(sport);
    console.log('Update URL:', updateUrl);
    
    // Get CSRF token
    const csrfToken = getCsrfToken();
    
    const requestBody = {
        team_id: teamId,
        status: newStatus
    };
    
    // Add CSRF token to request data
    if (csrfToken) {
        requestBody._csrfToken = csrfToken;
    }
    
    console.log('Request body:', requestBody);
    
    fetch(updateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': csrfToken || ''
        },
        body: new URLSearchParams(requestBody)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            console.error('Response not OK:', response.status, response.statusText);
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Update the status badge
            const statusBadge = document.getElementById('current-status');
            statusBadge.className = `status-badge status-${newStatus}`;
            
            const statusLabels = {
                'pending': 'En attente',
                'verified': 'Vérifiée',
                'rejected': 'Rejetée'
            };
            statusBadge.textContent = statusLabels[newStatus];
            
            showNotification('Statut mis à jour avec succès', 'success');
        } else {
            showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur de connexion', 'error');
    })
    .finally(() => {
        selectElement.disabled = false;
    });
}

function saveVerificationNotes(teamId, sport, notes, buttonElement) {
    const originalText = buttonElement.innerHTML;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
    buttonElement.disabled = true;
    
    const notesUrl = getNotesUpdateUrl(sport);
    console.log('Notes URL:', notesUrl);
    
    // Get CSRF token
    const csrfToken = getCsrfToken();
    
    const requestData = {
        team_id: teamId,
        notes: notes
    };
    
    // Add CSRF token to request data
    if (csrfToken) {
        requestData._csrfToken = csrfToken;
    }
    
    console.log('Sending data:', requestData);
    
    fetch(notesUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': csrfToken || ''
        },
        body: new URLSearchParams(requestData)
    })
    .then(response => {
        console.log('Notes response status:', response.status);
        console.log('Notes response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Notes response error text:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        
        return response.json().catch(e => {
            console.error('JSON parse error:', e);
            return response.text().then(text => {
                console.error('Response was not JSON:', text);
                throw new Error('Invalid JSON response: ' + text);
            });
        });
    })
    .then(data => {
        console.log('Notes response data:', data);
        if (data.success) {
            showNotification('Notes sauvegardées avec succès', 'success');
        } else {
            showNotification(data.message || 'Erreur lors de la sauvegarde', 'error');
        }
    })
    .catch(error => {
        console.error('Notes save error:', error);
        showNotification('Erreur: ' + error.message, 'error');
    })
    .finally(() => {
        buttonElement.innerHTML = originalText;
        buttonElement.disabled = false;
    });
}

// Function to toggle image visibility
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

// Function to toggle documents (images or PDFs)
function toggleDocument(documentId, extension) {
    toggleImage(documentId);
}

// Function to open image in full size modal
function openImageModal(imgSrc) {
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="modal-overlay" onclick="closeImageModal()">
            <div class="modal-content" onclick="event.stopPropagation()">
                <button class="modal-close" onclick="closeImageModal()">&times;</button>
                <img src="${imgSrc}" alt="Document" class="modal-img">
            </div>
        </div>
    `;
    
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.querySelector('.image-modal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
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
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        ${type === 'success' ? 'background: #10b981;' : 'background: #ef4444;'}
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>