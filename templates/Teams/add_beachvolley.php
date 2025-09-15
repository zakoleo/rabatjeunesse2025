<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 * @var \App\Model\Entity\User|null $user
 */

$this->assign('title', 'Inscription - Équipe de Beach Volleyball');
?>
<div class="teams form container">
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
                            'empty' => 'Sélectionner une catégorie',
                            'id' => 'beachvolley-category-id'
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
                            'label' => 'CIN Recto *',
                            'type' => 'file',
                            'required' => true,
                            'accept' => 'image/*,.pdf',
                            'templates' => [
                                'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face avant de la CIN - Formats acceptés : JPG, PNG, PDF</small></div>'
                            ]
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('responsable_cin_verso', [
                            'label' => 'CIN Verso *',
                            'type' => 'file',
                            'required' => true,
                            'accept' => 'image/*,.pdf',
                            'templates' => [
                                'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face arrière de la CIN - Formats acceptés : JPG, PNG, PDF</small></div>'
                            ]
                        ]) ?>
                    </div>
                </div>
            </section>

            <!-- Entraîneur Section -->
            <section class="form-section">
                <div class="section-header">
                    <h2>Entraîneur de l'équipe</h2>
                </div>
                
                <div class="form-group checkbox-group">
                    <?= $this->Form->control('entraineur_same_as_responsable', [
                        'type' => 'checkbox',
                        'label' => 'L\'entraîneur est la même personne que le responsable',
                        'id' => 'sameAsResponsable'
                    ]) ?>
                </div>
                
                <div id="entraineurFields">
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
                                'label' => 'CIN Recto *',
                                'type' => 'file',
                                'required' => true,
                                'accept' => 'image/*,.pdf',
                                'templates' => [
                                    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face avant de la CIN - Formats acceptés : JPG, PNG, PDF</small></div>'
                                ]
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_cin_verso', [
                                'label' => 'CIN Verso *',
                                'type' => 'file',
                                'required' => true,
                                'accept' => 'image/*,.pdf',
                                'templates' => [
                                    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face arrière de la CIN - Formats acceptés : JPG, PNG, PDF</small></div>'
                                ]
                            ]) ?>
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
                
                <p class="info-text">Nombre de joueurs requis : <span id="nombreJoueursRequis"></span></p>
                
                <div id="joueursContainer">
                    <!-- Les joueurs seront ajoutés dynamiquement ici -->
                </div>
                
                <button type="button" id="ajouterJoueur" class="btn btn-add">
                    Ajouter un joueur
                </button>
                
            <!-- Règlement -->
            <section class="form-section">
                <div class="form-group checkbox-group">
                    <?= $this->Form->control('accepter_reglement', [
                        'type' => 'checkbox',
                        'label' => 'J\'accepte le règlement du tournoi et les conditions de participation *',
                        'required' => true
                    ]) ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="wizard-navigation">
        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
            <i class="fas fa-arrow-left"></i> Précédent
        </button>
        
        <button type="button" class="btn btn-primary" id="nextBtn">
            Suivant <i class="fas fa-arrow-right"></i>
        </button>
        
        <button type="submit" class="btn btn-success" style="display: none;">
            <i class="fas fa-check"></i> Finaliser l'inscription
        </button>
    </div>

    <?= $this->Form->end() ?>

    <?php endif; ?>
</div>

<!-- Include JavaScript -->
<script>
    // Pass properly generated URLs to JavaScript
    window.API_URLS = {
        getBeachvolleyCategories: <?= json_encode($this->Url->build(['controller' => 'Teams', 'action' => 'getBeachvolleyCategories'])) ?>,
        getBeachvolleyTypes: <?= json_encode($this->Url->build(['controller' => 'Teams', 'action' => 'getBeachvolleyTypes'])) ?>,
        getSports: <?= json_encode($this->Url->build(['controller' => 'Teams', 'action' => 'getSports'])) ?>,
        testEndpoint: <?= json_encode($this->Url->build(['controller' => 'Teams', 'action' => 'testEndpoint'])) ?>
    };
    // Keep base URL for backward compatibility
    window.APP_BASE_URL = <?= json_encode($this->Url->build('/', ['fullBase' => false])) ?>;
</script>
<?= $this->Html->css('inscription-form') ?>
<?= $this->Html->css('form-validation') ?>
<?= $this->Html->script('beachvolley-wizard-validation') ?>