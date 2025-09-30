<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConcoursParticipant $participant
 * @var array $categories
 * @var array $concoursTypes
 */

// Ajouter le CSS du formulaire d'inscription
$this->Html->css('inscription-form', ['block' => true]);

// Get the pre-selected type from URL if available
$preselectedType = $this->request->getQuery('cat');
?>
<div class="teams form container">
    <div class="inscription-header">
        <h1>Inscription aux Concours<?= $preselectedType ? ' - ' . h($preselectedType) : '' ?></h1>
        <p class="subtitle">
            <?php if ($preselectedType): ?>
                Complétez le formulaire ci-dessous pour vous inscrire au concours de <?= h($preselectedType) ?>.
            <?php else: ?>
                Participez à nos différents concours créatifs et sportifs. Complétez le formulaire ci-dessous pour vous inscrire.
            <?php endif; ?>
        </p>
    </div>

    <?= $this->Form->create($participant, ['type' => 'file', 'id' => 'inscriptionForm']) ?>
    
    <!-- Wizard Container -->
    <div class="wizard-container">
        <section class="form-section">
            <div class="section-header">
                <h2>Type de concours et informations personnelles</h2>
            </div>
            
            <div class="form-group">
                <?php if ($preselectedType && array_key_exists($preselectedType, $concoursTypes)): ?>
                    <?= $this->Form->control('type_concours', [
                        'label' => 'Type de concours *',
                        'options' => $concoursTypes,
                        'default' => $preselectedType,
                        'required' => true,
                        'class' => 'form-control',
                        'readonly' => true
                    ]) ?>
                <?php else: ?>
                    <?= $this->Form->control('type_concours', [
                        'label' => 'Type de concours *',
                        'options' => $concoursTypes,
                        'empty' => 'Sélectionner un type de concours...',
                        'required' => true,
                        'class' => 'form-control'
                    ]) ?>
                <?php endif; ?>
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
                
                if (selectedGender === 'Homme' || selectedGender === 'Femme') {
                    const birthYear = birthDateObj.getFullYear();
                    const currentYear = new Date().getFullYear();
                    const age = currentYear - birthYear;
                    
                    if (age < 18) {
                        errorDiv.textContent = `Veuillez sélectionner une catégorie U18 ${selectedGender}`;
                    } else {
                        errorDiv.textContent = `Veuillez sélectionner une catégorie 18+ ${selectedGender}`;
                    }
                    birthDateInput.parentElement.appendChild(errorDiv);
                    birthDateInput.classList.add('error');
                }
            }
        }
    }
    
    // Add event listeners
    genderSelect.addEventListener('change', validateAndFilterCategories);
    birthDateInput.addEventListener('change', validateAndFilterCategories);
    
    // Initial validation
    validateAndFilterCategories();
});
</script>