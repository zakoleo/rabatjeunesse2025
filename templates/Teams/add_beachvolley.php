<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 * @var \App\Model\Entity\User|null $user
 */

$this->assign('title', 'Inscription - Équipe de Beach Volleyball');
?>
<div class="teams form container beachvolley-inscription">
    <div class="inscription-header">
        <h1>Inscription au Tournoi de Beach Volleyball</h1>
        <p class="subtitle">Complétez le formulaire ci-dessous pour inscrire votre équipe</p>
    </div>

    <?php if (!$user): ?>
        <div class="alert alert-warning">
            <h3>Connexion requise</h3>
            <p>Vous devez être connecté pour inscrire une équipe.</p>
            <div class="auth-buttons">
                <?= $this->Html->link('Se connecter', ['controller' => 'Users', 'action' => 'login'], ['class' => 'btn btn-primary']) ?>
                <?= $this->Html->link('Créer un compte', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>
    <?php else: ?>

    <?= $this->Form->create($team, ['type' => 'file', 'id' => 'inscriptionForm', 'novalidate' => true]) ?>
    
    <!-- Progress Bar -->
    <div class="progress-bar">
        <div class="progress-step active" data-step="1">
            <div class="step-circle">1</div>
            <span>Équipe</span>
        </div>
        <div class="progress-line"></div>
        <div class="progress-step" data-step="2">
            <div class="step-circle">2</div>
            <span>Responsable & Entraîneur</span>
        </div>
        <div class="progress-line"></div>
        <div class="progress-step" data-step="3">
            <div class="step-circle">3</div>
            <span>Joueurs</span>
        </div>
    </div>

    <!-- Wizard Container -->
    <div class="wizard-container">
        <!-- Step 1: Équipe -->
        <div class="wizard-step active" data-step="1">
            <section class="form-section">
                <div class="section-header">
                    <h2>Informations sur l'équipe</h2>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('nom_equipe', [
                        'label' => 'Nom de l\'équipe *',
                        'required' => true
                    ]) ?>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('beachvolley_category_id', [
                            'label' => 'Catégorie d\'âge *',
                            'options' => $beachvolleyCategories,
                            'required' => true,
                            'empty' => 'Sélectionner une catégorie'
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('genre', [
                            'label' => 'Genre *',
                            'options' => [
                                '' => 'Sélectionner le genre',
                                'Homme' => 'Homme',
                                'Femme' => 'Femme',
                                'Mixte' => 'Mixte'
                            ],
                            'required' => true,
                            'empty' => false
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('type_beachvolley', [
                            'label' => 'Type de beach volleyball *',
                            'options' => [
                                '' => 'Sélectionner le type',
                                '2x2' => 'Beach Volleyball classique (2x2)',
                                '3x3' => 'Beach Volleyball à 3 (3x3)'
                            ],
                            'required' => true,
                            'empty' => false
                        ]) ?>
                        <small class="form-text text-muted" id="type-beachvolley-help">2x2: 2-4 joueurs | 3x3: 3-6 joueurs</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('football_district_id', [
                            'label' => 'District *',
                            'options' => $footballDistricts,
                            'required' => true,
                            'empty' => 'Sélectionner un district'
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('football_organisation_id', [
                            'label' => 'Organisation *',
                            'options' => $footballOrganisations,
                            'required' => true,
                            'empty' => 'Sélectionner une organisation'
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('adresse', [
                        'label' => 'Adresse complète de l\'équipe *',
                        'type' => 'textarea',
                        'required' => true,
                        'rows' => 3
                    ]) ?>
                </div>
            </section>
        </div>

        <!-- Step 2: Responsable & Entraîneur -->
        <div class="wizard-step" data-step="2">
            <section class="form-section">
                <div class="section-header">
                    <h2>Responsable de l'équipe</h2>
                    <p class="section-description">Le responsable est la personne de contact officielle de l'équipe</p>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('responsable_nom_complet', [
                            'label' => 'Nom complet du responsable *',
                            'required' => true
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('responsable_date_naissance', [
                            'label' => 'Date de naissance *',
                            'type' => 'date',
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('responsable_tel', [
                            'label' => 'Téléphone *',
                            'type' => 'tel',
                            'required' => true
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('responsable_whatsapp', [
                            'label' => 'WhatsApp (optionnel)',
                            'type' => 'tel'
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('responsable_cin_recto', [
                            'label' => 'CIN - Recto *',
                            'type' => 'file',
                            'accept' => 'image/*',
                            'required' => true
                        ]) ?>
                        <small class="form-text text-muted">Format accepté: JPG, PNG, PDF (max. 5MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('responsable_cin_verso', [
                            'label' => 'CIN - Verso *',
                            'type' => 'file',
                            'accept' => 'image/*',
                            'required' => true
                        ]) ?>
                        <small class="form-text text-muted">Format accepté: JPG, PNG, PDF (max. 5MB)</small>
                    </div>
                </div>
            </section>

            <!-- Entraîneur Section -->
            <section class="form-section">
                <div class="section-header">
                    <h2>Entraîneur de l'équipe</h2>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-container">
                        <?= $this->Form->control('entraineur_same_as_responsable', [
                            'type' => 'checkbox',
                            'label' => false,
                            'id' => 'entraineur_same_as_responsable'
                        ]) ?>
                        <span class="checkmark"></span>
                        L'entraîneur est le même que le responsable
                    </label>
                </div>
                
                <div id="entraineur-fields">
                    <div class="form-row">
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_nom_complet', [
                                'label' => 'Nom complet de l\'entraîneur *',
                                'required' => false
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_date_naissance', [
                                'label' => 'Date de naissance *',
                                'type' => 'date',
                                'required' => false
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_tel', [
                                'label' => 'Téléphone *',
                                'type' => 'tel',
                                'required' => false
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_whatsapp', [
                                'label' => 'WhatsApp (optionnel)',
                                'type' => 'tel'
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_cin_recto', [
                                'label' => 'CIN - Recto *',
                                'type' => 'file',
                                'accept' => 'image/*',
                                'required' => false
                            ]) ?>
                            <small class="form-text text-muted">Format accepté: JPG, PNG, PDF (max. 5MB)</small>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_cin_verso', [
                                'label' => 'CIN - Verso *',
                                'type' => 'file',
                                'accept' => 'image/*',
                                'required' => false
                            ]) ?>
                            <small class="form-text text-muted">Format accepté: JPG, PNG, PDF (max. 5MB)</small>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Step 3: Joueurs -->
        <div class="wizard-step" data-step="3">
            <section class="form-section">
                <div class="section-header">
                    <h2>Liste des joueurs</h2>
                    <p class="section-description">Ajoutez tous les joueurs de votre équipe (minimum 2, maximum 6)</p>
                </div>
                
                <div class="players-container">
                    <div class="players-header">
                        <h3>Joueurs inscrits (<span id="player-count">0</span>/6)</h3>
                        <button type="button" class="btn btn-secondary" id="add-player">
                            <i class="fas fa-plus"></i> Ajouter un joueur
                        </button>
                    </div>
                    
                    <div id="players-list">
                        <!-- Players will be added here dynamically -->
                    </div>
                    
                    <div class="player-template" style="display: none;">
                        <div class="player-card">
                            <div class="player-header">
                                <h4>Joueur <span class="player-number"></span></h4>
                                <button type="button" class="btn btn-danger btn-sm remove-player">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="text" name="joueurs[INDEX][nom_complet]" placeholder="Nom complet *" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="date" name="joueurs[INDEX][date_naissance]" placeholder="Date de naissance *" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="text" name="joueurs[INDEX][identifiant]" placeholder="N° CIN ou Passeport *" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <select name="joueurs[INDEX][taille_vestimentaire]" class="form-control" required>
                                        <option value="">Taille vestimentaire *</option>
                                        <option value="XS">XS</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="XXXL">XXXL</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Règlement -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="checkbox-container">
                            <?= $this->Form->control('accepter_reglement', [
                                'type' => 'checkbox',
                                'label' => false,
                                'required' => true,
                                'id' => 'accepter_reglement'
                            ]) ?>
                            <span class="checkmark"></span>
                            J'accepte le <a href="#" target="_blank">règlement du tournoi</a> et confirme que toutes les informations sont exactes *
                        </label>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="wizard-navigation">
        <button type="button" class="btn btn-secondary" id="prev-step" style="display: none;">
            <i class="fas fa-arrow-left"></i> Précédent
        </button>
        
        <button type="button" class="btn btn-primary" id="next-step">
            Suivant <i class="fas fa-arrow-right"></i>
        </button>
        
        <button type="submit" class="btn btn-success" id="submit-form" style="display: none;">
            <i class="fas fa-check"></i> Finaliser l'inscription
        </button>
    </div>

    <?= $this->Form->end() ?>

    <?php endif; ?>
</div>

<!-- Include JavaScript -->
<script>
    // Pass the base URL to JavaScript
    window.APP_BASE_URL = <?= json_encode($this->Url->build('/', ['fullBase' => false])) ?>;
</script>
<?= $this->Html->script('inscription-form') ?>

<style>
.beachvolley-inscription {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
}

.inscription-header {
    text-align: center;
    margin-bottom: 3rem;
}

.inscription-header h1 {
    color: #f39c12; /* Orange beach color */
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #666;
    font-size: 1.1rem;
}

.progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 2rem 0;
    padding: 0 2rem;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.progress-step.active {
    opacity: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 0.5rem;
    transition: background-color 0.3s ease;
}

.progress-step.active .step-circle {
    background-color: #f39c12;
    color: white;
}

.progress-line {
    width: 100px;
    height: 2px;
    background-color: #ddd;
    margin: 0 1rem;
}

.wizard-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 2rem;
    margin-bottom: 2rem;
}

.wizard-step {
    display: none;
}

.wizard-step.active {
    display: block;
}

.form-section {
    margin-bottom: 2rem;
}

.section-header h2 {
    color: #f39c12;
    margin-bottom: 0.5rem;
}

.section-description {
    color: #666;
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #f39c12;
    box-shadow: 0 0 0 2px rgba(243, 156, 18, 0.2);
}

.checkbox-container {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f8f9fa;
}

.players-container {
    margin: 2rem 0;
}

.players-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.player-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    background-color: #f8f9fa;
}

.player-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.wizard-navigation {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
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
}

.btn-primary {
    background-color: #f39c12;
    color: white;
}

.btn-primary:hover {
    background-color: #e67e22;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.alert {
    padding: 1rem;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.alert-warning {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

@media (max-width: 768px) {
    .beachvolley-inscription {
        padding: 1rem;
    }
    
    .progress-bar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .progress-line {
        width: 2px;
        height: 30px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>