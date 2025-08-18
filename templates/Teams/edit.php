<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 * @var array $footballCategories
 * @var array $footballDistricts
 * @var array $footballOrganisations
 */
?>
<div class="page-header">
    <div class="container">
        <h1>Modifier l'équipe</h1>
        <p>Mettez à jour les informations de votre équipe</p>
    </div>
</div>

<div class="content-section">
    <div class="container">
        <div class="form-wrapper" style="max-width: 100%;">
            <?= $this->Form->create($team, ['type' => 'file', 'class' => 'edit-form']) ?>
            
            <div class="grid grid-2">
                <!-- Section Équipe -->
                <div class="card">
                    <div class="card-header">
                        <h3>Informations sur l'équipe</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <?= $this->Form->control('nom_equipe', [
                                'label' => 'Nom de l\'équipe *',
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <?= $this->Form->control('football_category_id', [
                                    'label' => 'Catégorie d\'âge *',
                                    'options' => $footballCategories,
                                    'required' => true,
                                    'empty' => 'Sélectionner une catégorie'
                                ]) ?>
                            </div>
                            
                            <div class="form-group">
                                <?= $this->Form->control('genre', [
                                    'label' => 'Genre *',
                                    'options' => [
                                        'Homme' => 'Homme',
                                        'Femme' => 'Femme'
                                    ],
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <?= $this->Form->control('type_football', [
                                    'label' => 'Type de football *',
                                    'options' => [
                                        '5x5' => 'Football à 5 (5x5)',
                                        '6x6' => 'Football à 6 (6x6)',
                                        '11x11' => 'Football à 11 (11x11)'
                                    ],
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <?= $this->Form->control('football_district_id', [
                                    'label' => 'District (Quartier) *',
                                    'options' => $footballDistricts,
                                    'required' => true,
                                    'empty' => 'Sélectionner un district'
                                ]) ?>
                            </div>
                            
                            <div class="form-group">
                                <?= $this->Form->control('football_organisation_id', [
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
                    </div>
                </div>

                <!-- Section Responsable -->
                <div class="card">
                    <div class="card-header">
                        <h3>Responsable de l'équipe</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <?= $this->Form->control('responsable_nom_complet', [
                                'label' => 'Nom complet *',
                                'required' => true
                            ]) ?>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <?= $this->Form->control('responsable_date_naissance', [
                                    'label' => 'Date de naissance *',
                                    'type' => 'date',
                                    'required' => true
                                ]) ?>
                            </div>
                            
                            <div class="form-group">
                                <?= $this->Form->control('responsable_tel', [
                                    'label' => 'Téléphone *',
                                    'type' => 'tel',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <?= $this->Form->control('responsable_whatsapp', [
                                'label' => 'Numéro WhatsApp',
                                'type' => 'tel'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Entraîneur -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Entraîneur de l'équipe</h3>
                </div>
                <div class="card-body">
                    <div class="checkbox-group mb-3">
                        <?= $this->Form->control('entraineur_same_as_responsable', [
                            'type' => 'checkbox',
                            'label' => 'L\'entraîneur est la même personne que le responsable',
                            'id' => 'same-person-checkbox'
                        ]) ?>
                    </div>
                    
                    <div id="entraineur-fields">
                        <div class="form-row">
                            <div class="form-group">
                                <?= $this->Form->control('entraineur_nom_complet', [
                                    'label' => 'Nom complet *',
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
                                    'label' => 'Numéro WhatsApp',
                                    'type' => 'tel'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Joueurs -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Joueurs de l'équipe</h3>
                    <p class="text-muted mb-0">Gérez la liste des joueurs de votre équipe</p>
                </div>
                <div class="card-body">
                    <div id="joueurs-container">
                        <?php if (!empty($team->joueurs)): ?>
                            <?php foreach ($team->joueurs as $index => $joueur): ?>
                                <div class="joueur-item" data-index="<?= $index ?>">
                                    <div class="joueur-header">
                                        <h4>Joueur <?= $index + 1 ?></h4>
                                        <button type="button" class="btn btn-danger btn-sm remove-joueur">Supprimer</button>
                                    </div>
                                    <?= $this->Form->hidden("joueurs.{$index}.id", ['value' => $joueur->id]) ?>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <?= $this->Form->control("joueurs.{$index}.nom_complet", [
                                                'label' => 'Nom complet *',
                                                'value' => $joueur->nom_complet,
                                                'required' => true
                                            ]) ?>
                                        </div>
                                        <div class="form-group">
                                            <?= $this->Form->control("joueurs.{$index}.date_naissance", [
                                                'label' => 'Date de naissance *',
                                                'type' => 'date',
                                                'value' => $joueur->date_naissance,
                                                'required' => true
                                            ]) ?>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <?= $this->Form->control("joueurs.{$index}.identifiant", [
                                                'label' => 'CIN ou Code Massar *',
                                                'value' => $joueur->identifiant,
                                                'required' => true,
                                                'help' => 'CIN pour les adultes, Code Massar pour les mineurs'
                                            ]) ?>
                                        </div>
                                        <div class="form-group">
                                            <?= $this->Form->control("joueurs.{$index}.taille_vestimentaire", [
                                                'label' => 'Taille vestimentaire *',
                                                'options' => [
                                                    'XS' => 'XS',
                                                    'S' => 'S',
                                                    'M' => 'M',
                                                    'L' => 'L',
                                                    'XL' => 'XL',
                                                    'XXL' => 'XXL'
                                                ],
                                                'value' => $joueur->taille_vestimentaire,
                                                'required' => true,
                                                'empty' => 'Sélectionner une taille'
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" id="add-joueur" class="btn btn-success mt-3">
                        <i class="fas fa-plus"></i> Ajouter un joueur
                    </button>
                    
                    <div class="alert alert-info mt-3">
                        <p class="mb-0">
                            <strong>Nombre de joueurs requis selon le type de football :</strong><br>
                            • Football à 5 (5x5) : Minimum 5, Maximum 8 joueurs<br>
                            • Football à 6 (6x6) : Minimum 6, Maximum 10 joueurs<br>
                            • Football à 11 (11x11) : Minimum 11, Maximum 18 joueurs
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions mt-4">
                <?= $this->Form->button('Enregistrer les modifications', ['class' => 'btn btn-primary btn-large']) ?>
                <?= $this->Html->link('Annuler', ['action' => 'view', $team->id], ['class' => 'btn btn-secondary']) ?>
                <?= $this->Form->postLink(
                    'Supprimer l\'équipe',
                    ['action' => 'delete', $team->id],
                    [
                        'confirm' => 'Êtes-vous sûr de vouloir supprimer cette équipe ?',
                        'class' => 'btn btn-danger float-right'
                    ]
                ) ?>
            </div>
            
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .form-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }
    
    .float-right {
        margin-left: auto;
    }
    
    #entraineur-fields {
        transition: opacity 0.3s ease;
    }
    
    #entraineur-fields.hidden {
        opacity: 0.5;
        pointer-events: none;
    }
    
    .joueur-item {
        background: var(--background-light);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }
    
    .joueur-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .joueur-header h4 {
        margin: 0;
        color: var(--text-dark);
        font-size: 1.125rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const samePersonCheckbox = document.getElementById('same-person-checkbox');
    const entraineurFields = document.getElementById('entraineur-fields');
    const joueursContainer = document.getElementById('joueurs-container');
    const addJoueurBtn = document.getElementById('add-joueur');
    const typeFootball = document.querySelector('[name="type_football"]');
    
    // Limites de joueurs
    const limites = {
        '5x5': { min: 5, max: 8 },
        '6x6': { min: 6, max: 10 },
        '11x11': { min: 11, max: 18 }
    };
    
    // Gestion de l'entraîneur
    function toggleEntraineurFields() {
        if (samePersonCheckbox.checked) {
            entraineurFields.classList.add('hidden');
            // Disable required on entraineur fields
            entraineurFields.querySelectorAll('input[required]').forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            entraineurFields.classList.remove('hidden');
            // Re-enable required on entraineur fields
            document.getElementById('entraineur-nom-complet').setAttribute('required', 'required');
            document.getElementById('entraineur-date-naissance').setAttribute('required', 'required');
            document.getElementById('entraineur-tel').setAttribute('required', 'required');
        }
    }
    
    samePersonCheckbox.addEventListener('change', toggleEntraineurFields);
    toggleEntraineurFields(); // Initial state
    
    // Gestion des joueurs
    let joueurIndex = joueursContainer.querySelectorAll('.joueur-item').length;
    
    function updateJoueurNumbers() {
        const joueurs = joueursContainer.querySelectorAll('.joueur-item');
        joueurs.forEach((joueur, index) => {
            joueur.querySelector('h4').textContent = `Joueur ${index + 1}`;
            joueur.dataset.index = index;
            
            // Update field names
            joueur.querySelectorAll('[name*="joueurs"]').forEach(field => {
                const name = field.name;
                field.name = name.replace(/joueurs\[\d+\]/, `joueurs[${index}]`);
            });
        });
        joueurIndex = joueurs.length;
    }
    
    function addJoueur() {
        const currentType = typeFootball ? typeFootball.value : '';
        const limite = limites[currentType];
        
        if (limite && joueurIndex >= limite.max) {
            alert(`Nombre maximum de joueurs atteint pour le ${currentType} (${limite.max} joueurs)`);
            return;
        }
        
        const newJoueur = document.createElement('div');
        newJoueur.className = 'joueur-item';
        newJoueur.dataset.index = joueurIndex;
        newJoueur.innerHTML = `
            <div class="joueur-header">
                <h4>Joueur ${joueurIndex + 1}</h4>
                <button type="button" class="btn btn-danger btn-sm remove-joueur">Supprimer</button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="joueurs-${joueurIndex}-nom-complet">Nom complet *</label>
                    <input type="text" name="joueurs[${joueurIndex}][nom_complet]" required="required" id="joueurs-${joueurIndex}-nom-complet" class="form-control">
                </div>
                <div class="form-group">
                    <label for="joueurs-${joueurIndex}-date-naissance">Date de naissance *</label>
                    <input type="date" name="joueurs[${joueurIndex}][date_naissance]" required="required" id="joueurs-${joueurIndex}-date-naissance" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="joueurs-${joueurIndex}-identifiant">CIN ou Code Massar *</label>
                    <input type="text" name="joueurs[${joueurIndex}][identifiant]" required="required" id="joueurs-${joueurIndex}-identifiant" class="form-control">
                    <small class="form-text text-muted">CIN pour les adultes, Code Massar pour les mineurs</small>
                </div>
                <div class="form-group">
                    <label for="joueurs-${joueurIndex}-taille-vestimentaire">Taille vestimentaire *</label>
                    <select name="joueurs[${joueurIndex}][taille_vestimentaire]" required="required" id="joueurs-${joueurIndex}-taille-vestimentaire" class="form-control">
                        <option value="">Sélectionner une taille</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>
            </div>
        `;
        
        joueursContainer.appendChild(newJoueur);
        joueurIndex++;
    }
    
    // Event listeners
    addJoueurBtn.addEventListener('click', addJoueur);
    
    joueursContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-joueur')) {
            const currentType = typeFootball ? typeFootball.value : '';
            const limite = limites[currentType];
            const currentCount = joueursContainer.querySelectorAll('.joueur-item').length;
            
            if (limite && currentCount <= limite.min) {
                alert(`Nombre minimum de joueurs requis pour le ${currentType} : ${limite.min}`);
                return;
            }
            
            const joueurItem = e.target.closest('.joueur-item');
            joueurItem.remove();
            updateJoueurNumbers();
        }
    });
});
</script>