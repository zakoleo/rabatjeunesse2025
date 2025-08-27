<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 * @var \App\Model\Entity\User|null $user
 */

$this->assign('title', 'Inscription - Équipe de Handball');
?>
<div class="teams form container handball-inscription">
    <div class="inscription-header">
        <h1>Inscription au Tournoi de Handball</h1>
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
                        <?= $this->Form->control('handball_category_id', [
                            'label' => 'Catégorie d\'âge *',
                            'options' => $handballCategories,
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
                                'Femme' => 'Femme'
                            ],
                            'required' => true,
                            'empty' => false
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('type_handball', [
                            'label' => 'Type de handball *',
                            'options' => [
                                '' => 'Sélectionner le type',
                                '7x7' => 'Handball à 7 (7x7)',
                                '5x5' => 'Handball à 5 (5x5)'
                            ],
                            'required' => true,
                            'empty' => false
                        ]) ?>
                        <small class="form-text text-muted" id="type-handball-help">7x7: 7-12 joueurs | 5x5: 5-8 joueurs</small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('handball_district_id', [
                            'label' => 'District (Quartier) *',
                            'options' => $footballDistricts,
                            'required' => true,
                            'empty' => 'Sélectionner un district'
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('handball_organisation_id', [
                            'label' => 'Type d\'organisation *',
                            'options' => $footballOrganisations,
                            'required' => true,
                            'empty' => 'Sélectionner le type'
                        ]) ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('adresse', [
                        'label' => 'Adresse postale *',
                        'type' => 'textarea',
                        'rows' => 3,
                        'required' => true
                    ]) ?>
                </div>
            </section>
        </div>
        
        <!-- Step 2: Encadrement (Responsable et Entraîneur) -->
        <div class="wizard-step" data-step="2">
            <!-- Responsable de l'équipe -->
            <section class="form-section">
                <div class="section-header">
                    <h2>Responsable de l'équipe</h2>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('responsable_nom_complet', [
                            'label' => 'Nom complet *',
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
                            'label' => 'Numéro de téléphone *',
                            'type' => 'tel',
                            'pattern' => '[0-9]{10}',
                            'required' => true
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('responsable_whatsapp', [
                            'label' => 'Numéro WhatsApp',
                            'type' => 'tel',
                            'pattern' => '[0-9]{10}',
                            'templates' => [
                                'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Laissez vide si identique au numéro principal</small></div>'
                            ]
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
            
            <!-- Entraîneur de l'équipe -->
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
                                'label' => 'Nom complet *',
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_date_naissance', [
                                'label' => 'Date de naissance *',
                                'type' => 'date',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_tel', [
                                'label' => 'Numéro de téléphone *',
                                'type' => 'tel',
                                'pattern' => '[0-9]{10}',
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_whatsapp', [
                                'label' => 'Numéro WhatsApp',
                                'type' => 'tel',
                                'pattern' => '[0-9]{10}',
                                'templates' => [
                                    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Laissez vide si identique au numéro principal</small></div>'
                                ]
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
            <!-- Liste des joueurs -->
            <section class="form-section">
                <div class="section-header">
                    <h2>Liste des joueurs</h2>
                </div>
                <p class="info-text">Nombre de joueurs requis :</p>
                <div id="nombreJoueursRequis"></div>
                
                <div id="joueursContainer">
                    <!-- Les joueurs seront ajoutés dynamiquement ici -->
                </div>
                
                <button type="button" id="ajouterJoueur" class="btn btn-add">
                    Ajouter un joueur
                </button>
            </section>
            
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
        <button type="button" id="prevBtn" class="btn btn-secondary" style="display: none;">Précédent</button>
        <button type="button" id="nextBtn" class="btn btn-primary">Suivant</button>
        <button type="submit" class="btn btn-primary" style="display: none;">Soumettre l'inscription</button>
    </div>
    
    <?= $this->Form->end() ?>
    <?php endif; ?>
</div>

<?= $this->Html->css('inscription-form') ?>
<script src="<?= $this->Url->webroot('js/handball-inscription-form.js?v=' . time()) ?>"></script>