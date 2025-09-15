<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\HandballTeam $team
 * @var array $footballCategories
 * @var array $footballDistricts
 * @var array $footballOrganisations
 */

$this->assign('title', 'Modifier l\'équipe de Handball');
?>
<div class="teams form container">
    <div class="inscription-header">
        <h1>Modifier l'équipe de Handball</h1>
        <p class="subtitle">Mettez à jour les informations de votre équipe</p>
    </div>

    <?= $this->Form->create($team, ['type' => 'file', 'id' => 'editHandballForm']) ?>
    
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
                            'options' => $footballCategories,
                            'required' => true,
                            'empty' => 'Sélectionner une catégorie',
                            'id' => 'handball-category-id'
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
                            'empty' => false,
                            'id' => 'type-handball'
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
                            'label' => 'CIN Recto (optionnel)',
                            'type' => 'file',
                            'required' => false,
                            'accept' => 'image/*,.pdf',
                            'templates' => [
                                'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face avant de la CIN - Laissez vide pour conserver le fichier actuel</small></div>'
                            ]
                        ]) ?>
                    </div>
                    
                    <div class="form-group">
                        <?= $this->Form->control('responsable_cin_verso', [
                            'label' => 'CIN Verso (optionnel)',
                            'type' => 'file',
                            'required' => false,
                            'accept' => 'image/*,.pdf',
                            'templates' => [
                                'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face arrière de la CIN - Laissez vide pour conserver le fichier actuel</small></div>'
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
                                'label' => 'CIN Recto (optionnel)',
                                'type' => 'file',
                                'required' => false,
                                'accept' => 'image/*,.pdf',
                                'templates' => [
                                    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face avant de la CIN - Laissez vide pour conserver le fichier actuel</small></div>'
                                ]
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('entraineur_cin_verso', [
                                'label' => 'CIN Verso (optionnel)',
                                'type' => 'file',
                                'required' => false,
                                'accept' => 'image/*,.pdf',
                                'templates' => [
                                    'inputContainer' => '<div class="input {{type}}{{required}}">{{content}}<small>Face arrière de la CIN - Laissez vide pour conserver le fichier actuel</small></div>'
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
                    <!-- Les joueurs existants seront ajoutés dynamiquement ici -->
                    <?php if (!empty($team->joueurs)): ?>
                        <?php foreach ($team->joueurs as $index => $joueur): ?>
                        <div class="joueur-form" data-index="<?= $index ?>">
                            <div class="joueur-header">
                                <h4>Joueur <?= $index + 1 ?></h4>
                                <button type="button" class="btn btn-danger btn-sm supprimerJoueur">Supprimer</button>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <?= $this->Form->control("joueurs.{$index}.nom_complet", [
                                        'label' => 'Nom complet *',
                                        'required' => true,
                                        'value' => $joueur->nom_complet ?? ''
                                    ]) ?>
                                </div>
                                <div class="form-group">
                                    <?= $this->Form->control("joueurs.{$index}.date_naissance", [
                                        'label' => 'Date de naissance *',
                                        'type' => 'date',
                                        'required' => true,
                                        'value' => $joueur->date_naissance ? $joueur->date_naissance->format('Y-m-d') : ''
                                    ]) ?>
                                </div>
                                <div class="form-group">
                                    <?= $this->Form->control("joueurs.{$index}.identifiant", [
                                        'label' => 'Identifiant *',
                                        'required' => true,
                                        'value' => $joueur->identifiant ?? ''
                                    ]) ?>
                                </div>
                                <div class="form-group">
                                    <?= $this->Form->control("joueurs.{$index}.taille_vestimentaire", [
                                        'label' => 'Taille *',
                                        'options' => [
                                            'XS' => 'XS',
                                            'S' => 'S',
                                            'M' => 'M',
                                            'L' => 'L',
                                            'XL' => 'XL',
                                            'XXL' => 'XXL',
                                            'XXXL' => 'XXXL'
                                        ],
                                        'required' => true,
                                        'empty' => 'Choisir',
                                        'value' => $joueur->taille_vestimentaire ?? ''
                                    ]) ?>
                                </div>
                            </div>
                            <?= $this->Form->hidden("joueurs.{$index}.id", ['value' => $joueur->id ?? '']) ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
        <button type="submit" class="btn btn-primary" style="display: none;">Mettre à jour l'équipe</button>
    </div>
    
    <div class="actions mt-3">
        <?= $this->Html->link('Annuler', ['action' => 'handballTeamView', $team->id], ['class' => 'btn btn-secondary']) ?>
        <?= $this->Html->link('Retour à la liste', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<?= $this->Html->css('inscription-form') ?>
<?= $this->Html->script('handball-inscription-form') ?>

<style>
    .actions {
        text-align: center;
        margin-top: 2rem;
    }
    
    .actions .btn {
        margin: 0 0.5rem;
    }
    
    .joueur-form {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    
    .joueur-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .joueur-header h4 {
        margin: 0;
        color: #D2691E;
        font-size: 1.1rem;
    }
    
    .supprimerJoueur {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>