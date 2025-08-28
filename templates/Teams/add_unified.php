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
        'categoryField' => 'football_category_id',
        'categoryOptions' => $footballCategories ?? [],
        'districtField' => 'football_district_id',
        'districtOptions' => $footballDistricts ?? [],
        'organisationField' => 'football_organisation_id',
        'organisationOptions' => $footballOrganisations ?? [],
        'typeField' => 'type_football',
        'typeOptions' => [
            '' => 'Sélectionner le type',
            '5x5' => 'Football à 5 (5x5)',
            '6x6' => 'Football à 6 (6x6)',
            '11x11' => 'Football à 11 (11x11)'
        ]
    ],
    'basketball' => [
        'title' => 'Basketball',
        'categoryField' => 'basketball_category_id',
        'categoryOptions' => $basketballCategories ?? [],
        'districtField' => 'basketball_district_id',
        'districtOptions' => $footballDistricts ?? [],
        'organisationField' => 'basketball_organisation_id',
        'organisationOptions' => $footballOrganisations ?? [],
        'typeField' => 'type_basketball',
        'typeOptions' => [
            '' => 'Sélectionner le type',
            '5x5' => 'Basketball classique (5x5)'
        ]
    ],
    'handball' => [
        'title' => 'Handball',
        'categoryField' => 'handball_category_id',
        'categoryOptions' => $handballCategories ?? [],
        'districtField' => 'handball_district_id',
        'districtOptions' => $footballDistricts ?? [],
        'organisationField' => 'handball_organisation_id',
        'organisationOptions' => $footballOrganisations ?? [],
        'typeField' => 'type_handball',
        'typeOptions' => [
            '' => 'Sélectionner le type',
            '7x7' => 'Handball classique (7x7)'
        ]
    ],
    'volleyball' => [
        'title' => 'Volleyball',
        'categoryField' => 'volleyball_category_id',
        'categoryOptions' => $volleyballCategories ?? [],
        'districtField' => 'volleyball_district_id',
        'districtOptions' => $footballDistricts ?? [],
        'organisationField' => 'volleyball_organisation_id',
        'organisationOptions' => $footballOrganisations ?? [],
        'typeField' => 'type_volleyball',
        'typeOptions' => [
            '' => 'Sélectionner le type',
            '6x6' => 'Volleyball classique (6x6)'
        ]
    ],
    'beachvolley' => [
        'title' => 'Beach Volleyball',
        'categoryField' => 'beachvolley_category_id',
        'categoryOptions' => $beachvolleyCategories ?? [],
        'districtField' => 'beachvolley_district_id',
        'districtOptions' => $footballDistricts ?? [],
        'organisationField' => 'beachvolley_organisation_id',
        'organisationOptions' => $footballOrganisations ?? [],
        'typeField' => 'type_beachvolley',
        'typeOptions' => [
            '' => 'Sélectionner le type',
            '2x2' => 'Beach Volleyball (2x2)'
        ]
    ]
];

$config = $sportConfigs[$sport] ?? $sportConfigs['football'];
?>
<div class="teams form container" data-sport="<?= $sport ?>">
    <div class="inscription-header">
        <h1>Inscription au Tournoi de <?= $config['title'] ?></h1>
        <p class="subtitle">Complétez le formulaire ci-dessous pour inscrire votre équipe</p>
    </div>

    <?= $this->Form->create($team, ['type' => 'file', 'id' => 'inscriptionForm']) ?>
    
    <!-- Hidden field to identify sport type -->
    <input type="hidden" id="sport-type" value="<?= $sport ?>">
    
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
                        <?= $this->Form->control($config['categoryField'], [
                            'label' => 'Catégorie d\'âge *',
                            'options' => $config['categoryOptions'],
                            'required' => true,
                            'empty' => 'Sélectionner une catégorie',
                            'id' => $sport . '-category-id'
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
                        <?= $this->Form->control($config['typeField'], [
                            'label' => 'Type de ' . strtolower($config['title']) . ' *',
                            'options' => $config['typeOptions'],
                            'required' => true,
                            'empty' => false,
                            'id' => 'type-' . $sport
                        ]) ?>
                        <small class="form-text text-muted" id="type-<?= $sport ?>-help"></small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control($config['districtField'], [
                            'label' => 'District (Quartier) *',
                            'options' => $config['districtOptions'],
                            'required' => true,
                            'empty' => 'Sélectionner un district'
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control($config['organisationField'], [
                            'label' => 'Type d\'organisation *',
                            'options' => $config['organisationOptions'],
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
                <p class="info-text">Nombre de joueurs requis : <span id="nombreJoueursRequis"></span></p>
                
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
</div>

<?= $this->Html->css('unified-form') ?>
<script>
    // Pass the base URL and sport type to JavaScript
    window.APP_BASE_URL = <?= json_encode($this->Url->build('/', ['fullBase' => false])) ?>;
    window.SPORT_TYPE = <?= json_encode($sport) ?>;
</script>
<?= $this->Html->script('unified-form') ?>