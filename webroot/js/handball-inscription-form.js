/**
 * Handball Team Registration Form Handler
 * Handles multi-step form wizard, validation, and player management
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing Handball Registration Form...');
    
    // ===========================================
    // CONSTANTS AND CONFIGURATION
    // ===========================================
    
    const PLAYER_REQUIREMENTS = {
        '7x7': { min: 7, max: 12, description: '7x7: 7-12 joueurs' },
        '5x5': { min: 5, max: 8, description: '5x5: 5-8 joueurs' }
    };
    
    const AGE_CATEGORIES = {
        '-15 ans': { minYear: 2010, maxYear: 2025 },
        '-17 ans': { minYear: 2008, maxYear: 2025 },
        '-19 ans': { minYear: 2006, maxYear: 2025 }
    };
    
    const TOTAL_STEPS = 3;
    
    // ===========================================
    // DOM ELEMENTS
    // ===========================================
    
    const elements = {
        form: document.getElementById('inscriptionForm'),
        progressSteps: document.querySelectorAll('.progress-step'),
        wizardSteps: document.querySelectorAll('.wizard-step'),
        prevBtn: document.getElementById('prevBtn'),
        nextBtn: document.getElementById('nextBtn'),
        submitBtn: document.querySelector('button[type="submit"]'),
        joueursContainer: document.getElementById('joueursContainer'),
        ajouterJoueurBtn: document.getElementById('ajouterJoueur'),
        nombreJoueursRequis: document.getElementById('nombreJoueursRequis'),
        entraineurSameCheckbox: document.getElementById('sameAsResponsable'),
        entraineurFields: document.getElementById('entraineurFields')
    };
    
    // ===========================================
    // STATE MANAGEMENT
    // ===========================================
    
    let state = {
        currentStep: 1,
        joueurCount: 0,
        selectedType: null
    };
    
    // ===========================================
    // UTILITY FUNCTIONS
    // ===========================================
    
    function getFieldByName(name) {
        return document.querySelector(`[name="${name}"]`);
    }
    
    function showNotification(message, type = 'info') {
        console.log(`${type.toUpperCase()}: ${message}`);
        
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button type="button" class="notification-close">&times;</button>
            </div>
        `;
        
        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 14px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            animation: slideInRight 0.3s ease-out;
        `;
        
        // Set colors based on type
        if (type === 'error') {
            notification.style.background = '#f8d7da';
            notification.style.color = '#721c24';
            notification.style.border = '2px solid #f5c6cb';
        } else if (type === 'success') {
            notification.style.background = '#d4edda';
            notification.style.color = '#155724';
            notification.style.border = '2px solid #c3e6cb';
        } else if (type === 'warning') {
            notification.style.background = '#fff3cd';
            notification.style.color = '#856404';
            notification.style.border = '2px solid #ffeaa7';
        } else {
            notification.style.background = '#d1ecf1';
            notification.style.color = '#0c5460';
            notification.style.border = '2px solid #bee5eb';
        }
        
        // Style the close button
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.style.cssText = `
                background: none;
                border: none;
                font-size: 18px;
                cursor: pointer;
                float: right;
                margin-left: 10px;
                padding: 0;
                line-height: 1;
                opacity: 0.7;
            `;
        }
        
        // Add CSS for animation if not exists
        if (!document.querySelector('#notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                .notification-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
            `;
            document.head.appendChild(style);
        }
        
        // Add to document
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        const timeout = setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
        
        // Close button functionality
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                clearTimeout(timeout);
                notification.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            });
        }
    }
    
    function clearValidationErrors(container) {
        if (!container) return;
        container.querySelectorAll('.error-message').forEach(msg => msg.remove());
        container.querySelectorAll('.error').forEach(field => field.classList.remove('error'));
    }
    
    function validateRequiredFields(container) {
        if (!container) return true;
        
        // Only validate visible required fields
        const requiredFields = container.querySelectorAll('[required]:not(:disabled)');
        let isValid = true;
        let firstErrorField = null;
        
        requiredFields.forEach(field => {
            // Skip validation if field is not visible
            if (field.offsetParent === null || getComputedStyle(field).display === 'none') {
                return;
            }
            
            if (!field.value || field.value.trim() === '') {
                field.classList.add('error');
                isValid = false;
                console.log('Required field empty:', field.name || field.id);
                
                if (!firstErrorField) {
                    firstErrorField = field;
                }
                
                // Add visual error indicator
                const formGroup = field.closest('.form-group');
                if (formGroup && !formGroup.querySelector('.error-message')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'Ce champ est requis';
                    errorMsg.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;';
                    formGroup.appendChild(errorMsg);
                }
            } else {
                field.classList.remove('error');
            }
        });
        
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return isValid;
    }
    
    // ===========================================
    // STEP MANAGEMENT
    // ===========================================
    
    function showStep(step) {
        console.log(`Showing step: ${step}`);
        
        // Validate step number
        if (step < 1 || step > TOTAL_STEPS) {
            console.error('Invalid step number:', step);
            return;
        }
        
        state.currentStep = step;
        
        // Hide all steps
        elements.wizardSteps.forEach((wizardStep, index) => {
            wizardStep.classList.remove('active');
            // Actually hide the step to prevent validation issues
            wizardStep.style.display = 'none';
            if (elements.progressSteps[index]) {
                elements.progressSteps[index].classList.remove('active', 'completed');
            }
        });
        
        // Show current step
        const currentWizardStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (currentWizardStep) {
            currentWizardStep.classList.add('active');
            currentWizardStep.style.display = 'block';
        } else {
            console.error('Wizard step not found:', step);
            return;
        }
        
        // Update progress indicators
        for (let i = 0; i < step; i++) {
            if (elements.progressSteps[i]) {
                if (i < step - 1) {
                    elements.progressSteps[i].classList.add('completed');
                } else {
                    elements.progressSteps[i].classList.add('active');
                }
            }
        }
        
        // Update navigation buttons
        if (elements.prevBtn) {
            elements.prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
        }
        if (elements.nextBtn) {
            elements.nextBtn.style.display = step < TOTAL_STEPS ? 'inline-block' : 'none';
        }
        if (elements.submitBtn) {
            elements.submitBtn.style.display = step === TOTAL_STEPS ? 'inline-block' : 'none';
        }
        
        // Update progress line
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.setAttribute('data-progress', step);
        }
    }
    
    function validateStep(step) {
        console.log(`Validating step: ${step}`);
        
        const currentWizardStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (!currentWizardStep) {
            console.error('Current wizard step not found');
            return false;
        }
        
        // Clear previous validation errors
        clearValidationErrors(currentWizardStep);
        
        // Validate required fields
        if (!validateRequiredFields(currentWizardStep)) {
            showNotification('Veuillez remplir tous les champs requis', 'error');
            return false;
        }
        
        // Step-specific validation
        switch (step) {
            case 1:
                return validateStep1(currentWizardStep);
            case 2:
                return validateStep2(currentWizardStep);
            case 3:
                return validateStep3(currentWizardStep);
            default:
                return true;
        }
    }
    
    function validateStep1(stepContainer) {
        console.log('Validating step 1 - Team Information');
        
        // Get form fields
        const categoryField = getFieldByName('handball_category_id');
        const typeField = getFieldByName('type_handball');
        const districtField = getFieldByName('handball_district_id');
        const organisationField = getFieldByName('handball_organisation_id');
        
        // Validate category selection
        if (!categoryField || !categoryField.value) {
            showNotification('Veuillez sélectionner une catégorie d\'âge', 'error');
            if (categoryField) categoryField.focus();
            return false;
        }
        
        // Validate type selection
        if (!typeField || !typeField.value) {
            showNotification('Veuillez sélectionner un type de handball', 'error');
            if (typeField) typeField.focus();
            return false;
        }
        
        // Validate district selection
        if (!districtField || !districtField.value) {
            showNotification('Veuillez sélectionner un district', 'error');
            if (districtField) districtField.focus();
            return false;
        }
        
        // Validate organisation selection
        if (!organisationField || !organisationField.value) {
            showNotification('Veuillez sélectionner un type d\'organisation', 'error');
            if (organisationField) organisationField.focus();
            return false;
        }
        
        // Update player requirements
        state.selectedType = typeField.value;
        updateJoueursRequirement(typeField.value);
        
        console.log('Step 1 validation passed');
        return true;
    }
    
    function validateStep2(stepContainer) {
        console.log('Validating step 2 - Team Officials');
        
        // Check if coach is same as manager
        const sameAsResponsable = elements.entraineurSameCheckbox && elements.entraineurSameCheckbox.checked;
        
        if (!sameAsResponsable && elements.entraineurFields) {
            // Validate coach fields if they are visible
            const coachFields = elements.entraineurFields.querySelectorAll('[required]:not(:disabled)');
            let coachValid = true;
            
            coachFields.forEach(field => {
                if (!field.value || field.value.trim() === '') {
                    field.classList.add('error');
                    coachValid = false;
                }
            });
            
            if (!coachValid) {
                showNotification('Veuillez remplir toutes les informations de l\'entraîneur', 'error');
                return false;
            }
        }
        
        console.log('Step 2 validation passed');
        return true;
    }
    
    function validateStep3(stepContainer) {
        console.log('Validating step 3 - Players');
        
        if (!state.selectedType) {
            const typeField = getFieldByName('type_handball');
            state.selectedType = typeField ? typeField.value : null;
        }
        
        const currentPlayerCount = elements.joueursContainer ? elements.joueursContainer.children.length : 0;
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        
        if (!requirements) {
            showNotification('Type de handball non valide', 'error');
            return false;
        }
        
        // Check minimum players
        if (currentPlayerCount < requirements.min) {
            showNotification(`Vous devez ajouter au minimum ${requirements.min} joueurs pour le ${state.selectedType}`, 'error');
            return false;
        }
        
        // Check maximum players
        if (currentPlayerCount > requirements.max) {
            showNotification(`Vous ne pouvez pas avoir plus de ${requirements.max} joueurs pour le ${state.selectedType}`, 'error');
            return false;
        }
        
        // Validate player information
        const playerItems = stepContainer.querySelectorAll('.joueur-item');
        for (let i = 0; i < playerItems.length; i++) {
            const playerItem = playerItems[i];
            const requiredFields = playerItem.querySelectorAll('[required]');
            
            for (let j = 0; j < requiredFields.length; j++) {
                const field = requiredFields[j];
                if (!field.value || field.value.trim() === '') {
                    field.classList.add('error');
                    showNotification(`Veuillez remplir toutes les informations du joueur ${i + 1}`, 'error');
                    field.focus();
                    return false;
                }
            }
        }
        
        // Validate birth dates against category
        if (!validatePlayerBirthDates()) {
            return false;
        }
        
        console.log('Step 3 validation passed');
        return true;
    }
    
    function validatePlayerBirthDates() {
        const categoryField = getFieldByName('handball_category_id');
        if (!categoryField || !categoryField.value) return true;
        
        const categoryText = categoryField.options[categoryField.selectedIndex].text;
        const ageCategory = AGE_CATEGORIES[categoryText];
        
        if (!ageCategory) return true;
        
        const playerItems = document.querySelectorAll('.joueur-item');
        for (let i = 0; i < playerItems.length; i++) {
            const dateField = playerItems[i].querySelector('input[type="date"]');
            if (dateField && dateField.value) {
                const birthYear = new Date(dateField.value).getFullYear();
                
                if (birthYear < ageCategory.minYear || birthYear > ageCategory.maxYear) {
                    dateField.classList.add('error');
                    showNotification(`Le joueur ${i + 1} ne correspond pas à la catégorie ${categoryText} (naissance entre ${ageCategory.minYear} et ${ageCategory.maxYear})`, 'error');
                    dateField.focus();
                    return false;
                }
            }
        }
        
        return true;
    }
    
    // ===========================================
    // PLAYER MANAGEMENT
    // ===========================================
    
    function updateJoueursRequirement(type) {
        if (!elements.nombreJoueursRequis || !PLAYER_REQUIREMENTS[type]) return;
        
        const req = PLAYER_REQUIREMENTS[type];
        elements.nombreJoueursRequis.innerHTML = `
            <div class="requirement-info">
                <h4>Composition pour ${type}</h4>
                <p><strong>Minimum:</strong> ${req.min} joueurs</p>
                <p><strong>Maximum:</strong> ${req.max} joueurs</p>
                <p class="note">${req.description}</p>
            </div>
        `;
        elements.nombreJoueursRequis.className = 'info-alert success';
    }
    
    function ajouterJoueur() {
        console.log('Adding new player...');
        
        if (!state.selectedType) {
            const typeField = getFieldByName('type_handball');
            state.selectedType = typeField ? typeField.value : null;
        }
        
        if (!state.selectedType) {
            showNotification('Veuillez d\'abord sélectionner le type de handball', 'error');
            return;
        }
        
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        if (state.joueurCount >= requirements.max) {
            showNotification(`Vous ne pouvez pas ajouter plus de ${requirements.max} joueurs pour le ${state.selectedType}`, 'error');
            return;
        }
        
        const joueurDiv = document.createElement('div');
        joueurDiv.className = 'joueur-item';
        joueurDiv.innerHTML = `
            <div class="joueur-header">
                <h4>Joueur ${state.joueurCount + 1}</h4>
                <button type="button" class="btn-remove" onclick="supprimerJoueur(this)">
                    Supprimer
                </button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" name="joueurs[${state.joueurCount}][nom_complet]" required 
                           placeholder="Nom et prénom du joueur">
                </div>
                <div class="form-group">
                    <label>Date de naissance *</label>
                    <input type="date" name="joueurs[${state.joueurCount}][date_naissance]" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Identifiant (CIN/Passeport) *</label>
                    <input type="text" name="joueurs[${state.joueurCount}][identifiant]" required 
                           placeholder="Ex: AB123456">
                </div>
                <div class="form-group">
                    <label>Taille vestimentaire *</label>
                    <select name="joueurs[${state.joueurCount}][taille_vestimentaire]" required>
                        <option value="">Sélectionnez</option>
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
        
        if (elements.joueursContainer) {
            elements.joueursContainer.appendChild(joueurDiv);
            state.joueurCount++;
            updateRemoveButtons();
            console.log(`Player ${state.joueurCount} added successfully`);
        }
    }
    
    function updateRemoveButtons() {
        if (!state.selectedType) return;
        
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        const removeButtons = document.querySelectorAll('.btn-remove');
        
        removeButtons.forEach((btn, index) => {
            if (index < requirements.min) {
                btn.style.display = 'none'; // Hide remove button for minimum required players
            } else {
                btn.style.display = 'flex';
            }
        });
    }
    
    function clearAllPlayers() {
        if (!elements.joueursContainer) return;
        
        while (elements.joueursContainer.firstChild) {
            elements.joueursContainer.removeChild(elements.joueursContainer.firstChild);
        }
        state.joueurCount = 0;
        console.log('All players cleared');
    }
    
    function addMinimumPlayers() {
        if (!state.selectedType) return;
        
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        console.log(`Adding ${requirements.min} minimum players for ${state.selectedType}`);
        
        for (let i = 0; i < requirements.min; i++) {
            ajouterJoueur();
        }
    }
    
    // Global function for removing players
    window.supprimerJoueur = function(button) {
        const joueurForm = button.closest('.joueur-item');
        if (!joueurForm) return;
        
        console.log('Removing player...');
        joueurForm.remove();
        state.joueurCount--;
        
        // Re-index remaining players
        const remainingPlayers = document.querySelectorAll('.joueur-item');
        remainingPlayers.forEach((player, index) => {
            const header = player.querySelector('.joueur-header h4');
            if (header) {
                header.textContent = `Joueur ${index + 1}`;
            }
            
            // Update input names
            const inputs = player.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name && input.name.includes('joueurs[')) {
                    input.name = input.name.replace(/joueurs\[\d+\]/, `joueurs[${index}]`);
                }
            });
        });
        
        updateRemoveButtons();
        console.log(`Player removed, ${state.joueurCount} players remaining`);
    };
    
    // ===========================================
    // EVENT LISTENERS
    // ===========================================
    
    function setupEventListeners() {
        console.log('Setting up event listeners...');
        
        // Navigation buttons
        if (elements.nextBtn) {
            elements.nextBtn.addEventListener('click', function() {
                console.log(`Next button clicked, current step: ${state.currentStep}`);
                if (validateStep(state.currentStep)) {
                    if (state.currentStep < TOTAL_STEPS) {
                        showStep(state.currentStep + 1);
                    }
                }
            });
        }
        
        if (elements.prevBtn) {
            elements.prevBtn.addEventListener('click', function() {
                console.log(`Previous button clicked, current step: ${state.currentStep}`);
                if (state.currentStep > 1) {
                    showStep(state.currentStep - 1);
                }
            });
        }
        
        // Handball type change
        const typeField = getFieldByName('type_handball');
        if (typeField) {
            typeField.addEventListener('change', function() {
                const selectedType = this.value;
                console.log(`Type changed to: ${selectedType}`);
                
                state.selectedType = selectedType;
                updateJoueursRequirement(selectedType);
                
                // Clear and reset players
                clearAllPlayers();
                if (selectedType) {
                    addMinimumPlayers();
                }
            });
        }
        
        // Add player button
        if (elements.ajouterJoueurBtn) {
            elements.ajouterJoueurBtn.addEventListener('click', ajouterJoueur);
        }
        
        // Coach same as manager checkbox
        if (elements.entraineurSameCheckbox && elements.entraineurFields) {
            elements.entraineurSameCheckbox.addEventListener('change', function() {
                console.log('Coach same as manager:', this.checked);
                
                if (this.checked) {
                    elements.entraineurFields.style.display = 'none';
                    // Remove required attribute from coach fields
                    const coachFields = elements.entraineurFields.querySelectorAll('input, select, textarea');
                    coachFields.forEach(field => {
                        field.removeAttribute('required');
                    });
                } else {
                    elements.entraineurFields.style.display = 'block';
                    // Add required attribute back to coach fields
                    const coachFields = elements.entraineurFields.querySelectorAll('input, select, textarea');
                    coachFields.forEach(field => {
                        if (field.type !== 'file') {
                            field.setAttribute('required', 'required');
                        }
                    });
                }
            });
        }
        
        // Form submission
        if (elements.form) {
            elements.form.addEventListener('submit', function(e) {
                console.log('Form submission attempted');
                
                // Final validation
                if (!validateStep(3)) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Inscription en cours...';
                }
                
                console.log('Form submitted successfully');
            });
        }
    }
    
    // ===========================================
    // INITIALIZATION
    // ===========================================
    
    function initialize() {
        console.log('Initializing handball registration form...');
        
        // Setup event listeners
        setupEventListeners();
        
        // Initialize first step
        showStep(1);
        
        // Initialize player requirements display
        const initialTypeField = getFieldByName('type_handball');
        if (initialTypeField && initialTypeField.value) {
            state.selectedType = initialTypeField.value;
            updateJoueursRequirement(initialTypeField.value);
        }
        
        // Check for existing form data and restore state if needed
        const existingPlayers = document.querySelectorAll('.joueur-item');
        if (existingPlayers.length > 0) {
            state.joueurCount = existingPlayers.length;
            updateRemoveButtons();
        }
        
        console.log('Handball registration form initialized successfully');
        console.log('Current state:', state);
    }
    
    // Start the application
    initialize();
    
    // ===========================================
    // PUBLIC API (for debugging)
    // ===========================================
    
    // Expose some functions for debugging
    window.handballForm = {
        showStep: showStep,
        validateStep: validateStep,
        getState: () => state,
        addPlayer: ajouterJoueur,
        clearPlayers: clearAllPlayers
    };
});