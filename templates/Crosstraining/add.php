<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CrosstrainingParticipant $participant
 * @var array $categories
 */

// Ajouter le CSS du formulaire d'inscription
$this->Html->css('inscription-form', ['block' => true]);
?>
<div class="teams form container">
    <div class="inscription-header">
        <h1>Inscription Cross Training</h1>
        <p class="subtitle">Le Cross Training est un sport individuel. Complétez le formulaire ci-dessous pour vous inscrire.</p>
    </div>

    <?= $this->Form->create($participant, ['type' => 'file', 'id' => 'inscriptionForm']) ?>
    
    <!-- Wizard Container -->
    <div class="wizard-container">
        <section class="form-section">
            <div class="section-header">
                <h2>Informations personnelles</h2>
            </div>
            
            <div class="form-group">
                <?= $this->Form->control('nom_complet', [
                    'label' => 'Nom complet *',
                    'required' => true
                ]) ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('date_naissance', [
                        'label' => 'Date de naissance *',
                        'type' => 'date',
                        'required' => true,
                        'id' => 'date-naissance'
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('gender', [
                        'label' => 'Genre *',
                        'options' => ['Homme' => 'Homme', 'Femme' => 'Femme'],
                        'empty' => 'Sélectionner...',
                        'required' => true,
                        'id' => 'gender-select'
                    ]) ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('category_id', [
                        'label' => 'Catégorie *',
                        'options' => $categories,
                        'empty' => 'Sélectionner une catégorie...',
                        'required' => true,
                        'id' => 'category-select'
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('taille_tshirt', [
                        'label' => 'Taille *',
                        'options' => [
                            'XS' => 'XS',
                            'S' => 'S', 
                            'M' => 'M', 
                            'L' => 'L', 
                            'XL' => 'XL', 
                            'XXL' => 'XXL'
                        ],
                        'empty' => 'Sélectionner...',
                        'required' => true
                    ]) ?>
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="section-header">
                <h2>Coordonnées</h2>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('cin', [
                        'label' => 'CIN *',
                        'required' => true
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('telephone', [
                        'label' => 'Téléphone *',
                        'type' => 'tel',
                        'required' => true
                    ]) ?>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('whatsapp', [
                        'label' => 'Téléphone WhatsApp',
                        'type' => 'tel'
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('email', [
                        'label' => 'Email',
                        'type' => 'email'
                    ]) ?>
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="section-header">
                <h2>Documents</h2>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('cin_recto', [
                        'label' => 'CIN Recto (optionnel)',
                        'type' => 'file',
                        'accept' => 'image/*'
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('cin_verso', [
                        'label' => 'CIN Verso (optionnel)',
                        'type' => 'file',
                        'accept' => 'image/*'
                    ]) ?>
                </div>
            </div>
        </section>

        <div class="form-actions">
            <?= $this->Form->button(__('S\'inscrire'), [
                'class' => 'btn btn-primary btn-lg'
            ]) ?>
            <?= $this->Html->link(__('Annuler'), 
                ['action' => 'index'], 
                ['class' => 'btn btn-secondary btn-lg']
            ) ?>
        </div>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<style>
.error {
    border-color: #dc3545 !important;
}
.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const genderSelect = document.getElementById('gender-select');
    const categorySelect = document.getElementById('category-select');
    const birthDateInput = document.getElementById('date-naissance');
    
    // Categories data with date ranges
    const categoriesData = <?= json_encode($categoriesData) ?>;
    
    function validateAndFilterCategories() {
        const selectedGender = genderSelect.value;
        const birthDate = birthDateInput.value;
        
        // Reset category selection
        categorySelect.value = '';
        
        // Clear any previous error messages
        const existingError = birthDateInput.parentElement.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        birthDateInput.classList.remove('error');
        
        // Show/hide categories based on gender
        Array.from(categorySelect.options).forEach(option => {
            if (option.value) {
                const categoryText = option.text;
                
                if (!selectedGender) {
                    // If no gender selected, show all
                    option.style.display = '';
                } else {
                    // Show only categories matching the selected gender
                    if (categoryText.includes(selectedGender)) {
                        option.style.display = '';
                        
                        // If birth date is also selected, validate and auto-select
                        if (birthDate) {
                            const birthDateObj = new Date(birthDate);
                            
                            // Find matching category data
                            const matchingCategory = categoriesData.find(cat => 
                                cat.id == option.value && 
                                cat.gender === selectedGender
                            );
                            
                            if (matchingCategory) {
                                const startDate = new Date(matchingCategory.date_range_start);
                                const endDate = new Date(matchingCategory.date_range_end);
                                
                                // Check if birth date is within the range
                                if (birthDateObj >= startDate && birthDateObj <= endDate) {
                                    option.selected = true;
                                } else {
                                    // If not in range, disable this option
                                    option.disabled = true;
                                    option.style.color = '#999';
                                }
                            }
                        }
                    } else {
                        option.style.display = 'none';
                    }
                }
            }
        });
        
        // Validate birth date if gender is selected
        if (selectedGender && birthDate) {
            const birthDateObj = new Date(birthDate);
            const validCategories = categoriesData.filter(cat => 
                cat.gender === selectedGender &&
                birthDateObj >= new Date(cat.date_range_start) && 
                birthDateObj <= new Date(cat.date_range_end)
            );
            
            if (validCategories.length === 0) {
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.style.color = '#dc3545';
                errorDiv.style.fontSize = '0.875rem';
                errorDiv.style.marginTop = '0.25rem';
                
                if (selectedGender === 'Homme') {
                    errorDiv.textContent = 'Pour les hommes, vous devez être né entre 2007 et aujourd\'hui (U18) ou avant 2007 (18+).';
                } else {
                    errorDiv.textContent = 'Pour les femmes, vous devez être née entre 2007 et aujourd\'hui (U18) ou avant 2007 (18+).';
                }
                
                birthDateInput.parentElement.appendChild(errorDiv);
                birthDateInput.classList.add('error');
                
                // Disable submit button
                document.querySelector('button[type="submit"]').disabled = true;
            } else {
                // Enable submit button
                document.querySelector('button[type="submit"]').disabled = false;
            }
        }
    }
    
    genderSelect.addEventListener('change', validateAndFilterCategories);
    birthDateInput.addEventListener('change', validateAndFilterCategories);
    
    // Form submission validation
    document.getElementById('inscriptionForm').addEventListener('submit', function(e) {
        const selectedGender = genderSelect.value;
        const birthDate = birthDateInput.value;
        const selectedCategory = categorySelect.value;
        
        if (selectedGender && birthDate && selectedCategory) {
            const birthDateObj = new Date(birthDate);
            const category = categoriesData.find(cat => cat.id == selectedCategory);
            
            if (category) {
                const startDate = new Date(category.date_range_start);
                const endDate = new Date(category.date_range_end);
                
                if (birthDateObj < startDate || birthDateObj > endDate) {
                    e.preventDefault();
                    alert('La date de naissance n\'est pas valide pour la catégorie sélectionnée.');
                    return false;
                }
            }
        }
    });
    
    // Initial filter on page load
    validateAndFilterCategories();
});
</script>