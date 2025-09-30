<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConcoursParticipant $participant
 * @var array $categories
 * @var array $concoursTypes
 */
?>
<div class="concours-participants edit">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Modifier l'inscription au Concours</h3>
                    </div>
                    <div class="card-body">
                        <?= $this->Form->create($participant, ['type' => 'file']) ?>
                        
                        <?php if ($participant->status === 'verified'): ?>
                        <div class="alert alert-warning">
                            <strong>Attention :</strong> Cette inscription a déjà été vérifiée. Vous ne pouvez pas la modifier.
                        </div>
                        <?php else: ?>
                        
                        <h5 class="mb-3">Type de concours et informations personnelles</h5>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->control('type_concours', [
                                    'label' => 'Type de concours',
                                    'options' => $concoursTypes,
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('nom_complet', [
                                    'label' => 'Nom complet',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('date_naissance', [
                                    'label' => 'Date de naissance',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'required' => true,
                                    'id' => 'date-naissance'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('gender', [
                                    'label' => 'Genre',
                                    'options' => ['Homme' => 'Homme', 'Femme' => 'Femme'],
                                    'class' => 'form-control',
                                    'required' => true,
                                    'id' => 'gender-select'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('category_id', [
                                    'label' => 'Catégorie',
                                    'options' => $categories,
                                    'class' => 'form-control',
                                    'required' => true,
                                    'id' => 'category-select'
                                ]) ?>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Coordonnées</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('cin', [
                                    'label' => 'CIN',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('telephone', [
                                    'label' => 'Téléphone',
                                    'type' => 'tel',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('whatsapp', [
                                    'label' => 'Téléphone WhatsApp',
                                    'type' => 'tel',
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('email', [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Documents</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= $this->Form->control('cin_recto', [
                                        'label' => 'CIN Recto',
                                        'type' => 'file',
                                        'class' => 'form-control',
                                        'accept' => 'image/*'
                                    ]) ?>
                                    <?php if ($participant->cin_recto): ?>
                                    <small class="text-muted">Fichier actuel : <?= h($participant->cin_recto) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= $this->Form->control('cin_verso', [
                                        'label' => 'CIN Verso',
                                        'type' => 'file',
                                        'class' => 'form-control',
                                        'accept' => 'image/*'
                                    ]) ?>
                                    <?php if ($participant->cin_verso): ?>
                                    <small class="text-muted">Fichier actuel : <?= h($participant->cin_verso) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <?php if ($participant->status !== 'verified'): ?>
                                <?= $this->Form->button(__('Enregistrer les modifications'), [
                                    'class' => 'btn btn-primary'
                                ]) ?>
                            <?php endif; ?>
                            <?= $this->Html->link(__('Annuler'), 
                                ['action' => 'view', $participant->id], 
                                ['class' => 'btn btn-secondary']
                            ) ?>
                        </div>
                        
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    option.disabled = false;
                } else {
                    // Show only categories matching the selected gender
                    if (categoryText.includes(selectedGender)) {
                        option.style.display = '';
                        option.disabled = false;
                        
                        // If birth date is also selected, validate
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
                                if (birthDateObj < startDate || birthDateObj > endDate) {
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
    }
    
    // Add event listeners
    genderSelect.addEventListener('change', validateAndFilterCategories);
    birthDateInput.addEventListener('change', validateAndFilterCategories);
    
    // Initial validation
    validateAndFilterCategories();
});
</script>