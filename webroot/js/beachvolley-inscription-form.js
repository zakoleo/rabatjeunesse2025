/**
 * Beach Volleyball Team Registration Form Handler
 * Handles multi-step form wizard, validation, and player management
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing Beach Volleyball Registration Form...');
    
    // ===========================================
    // CONSTANTS AND CONFIGURATION
    // ===========================================
    
    const PLAYER_REQUIREMENTS = {
        '2x2': { min: 2, max: 4, description: '2x2: 2-4 joueurs' },
        '3x3': { min: 3, max: 6, description: '3x3: 3-6 joueurs' }
    };
    
    const AGE_CATEGORIES = {
        '-17 ans': { minYear: 2008, maxYear: 2025 },
        '-21 ans': { minYear: 2004, maxYear: 2025 },
        '+21 ans': { minYear: 1970, maxYear: 2003 }
    };
    
    const TOTAL_STEPS = 3;
    
    // ===========================================
    // DOM ELEMENTS
    // ===========================================
    
    const elements = {
        form: document.getElementById('inscriptionForm'),
        progressSteps: document.querySelectorAll('.progress-step'),
        wizardSteps: document.querySelectorAll('.wizard-step'),
        prevBtn: document.getElementById('prev-step'),
        nextBtn: document.getElementById('next-step'),
        submitBtn: document.getElementById('submit-form'),
        playersContainer: document.getElementById('players-list'),
        addPlayerBtn: document.getElementById('add-player'),
        playerCount: document.getElementById('player-count'),
        entraineurSameCheckbox: document.getElementById('entraineur_same_as_responsable'),
        entraineurFields: document.getElementById('entraineur-fields'),
        playerTemplate: document.querySelector('.player-template')
    };
    
    // ===========================================
    // STATE MANAGEMENT
    // ===========================================
    
    let state = {
        currentStep: 1,
        playerCount: 0,
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
            notification.style.background = '#cce7ff';
            notification.style.color = '#004085';
            notification.style.border = '2px solid #b8daff';
        }
        
        // Add to page
        document.body.appendChild(notification);
        
        // Add close functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => notification.remove());
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function validatePhone(phone) {
        return /^[\d\s\+\-\(\)]{10,}$/.test(phone);
    }
    
    function calculateAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }
    
    // ===========================================
    // STEP NAVIGATION
    // ===========================================
    
    function updateProgressBar() {
        elements.progressSteps.forEach((step, index) => {
            if (index + 1 <= state.currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
    }
    
    function showStep(stepNumber) {
        elements.wizardSteps.forEach((step, index) => {
            if (index + 1 === stepNumber) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        
        // Update navigation buttons
        elements.prevBtn.style.display = stepNumber > 1 ? 'block' : 'none';
        elements.nextBtn.style.display = stepNumber < TOTAL_STEPS ? 'block' : 'none';
        elements.submitBtn.style.display = stepNumber === TOTAL_STEPS ? 'block' : 'none';
        
        updateProgressBar();
        
        // Scroll to top of form
        elements.form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    function goToNextStep() {
        if (validateCurrentStep()) {
            state.currentStep++;
            showStep(state.currentStep);
        }
    }
    
    function goToPrevStep() {
        if (state.currentStep > 1) {
            state.currentStep--;
            showStep(state.currentStep);
        }
    }
    
    // ===========================================
    // VALIDATION FUNCTIONS
    // ===========================================
    
    function validateCurrentStep() {
        switch (state.currentStep) {
            case 1:
                return validateStep1();
            case 2:
                return validateStep2();
            case 3:
                return validateStep3();
            default:
                return true;
        }
    }
    
    function validateStep1() {
        const requiredFields = [
            'nom_equipe',
            'football_category_id',
            'genre',
            'type_beachvolley',
            'football_district_id',
            'football_organisation_id',
            'adresse'
        ];
        
        let isValid = true;
        let errorMessages = [];
        
        requiredFields.forEach(fieldName => {
            const field = getFieldByName(fieldName);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                errorMessages.push(`Le champ ${fieldName.replace(/_/g, ' ')} est requis`);
            } else if (field) {
                field.classList.remove('error');
            }
        });
        
        // Store selected type for player validation
        const typeField = getFieldByName('type_beachvolley');
        if (typeField && typeField.value) {
            state.selectedType = typeField.value;
        }
        
        if (!isValid) {
            showNotification('Veuillez remplir tous les champs obligatoires', 'error');
        }
        
        return isValid;
    }
    
    function validateStep2() {
        const requiredResponsableFields = [
            'responsable_nom_complet',
            'responsable_date_naissance',
            'responsable_tel'
        ];
        
        let isValid = true;
        let errorMessages = [];
        
        // Validate responsable fields
        requiredResponsableFields.forEach(fieldName => {
            const field = getFieldByName(fieldName);
            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                errorMessages.push(`Le champ ${fieldName.replace(/_/g, ' ')} est requis`);
            } else if (field) {
                field.classList.remove('error');
            }
        });
        
        // Validate entraineur fields if not same as responsable
        if (!elements.entraineurSameCheckbox.checked) {
            const requiredEntraineurFields = [
                'entraineur_nom_complet',
                'entraineur_date_naissance',
                'entraineur_tel'
            ];
            
            requiredEntraineurFields.forEach(fieldName => {
                const field = getFieldByName(fieldName);
                if (field && !field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    errorMessages.push(`Le champ ${fieldName.replace(/_/g, ' ')} est requis`);
                } else if (field) {
                    field.classList.remove('error');
                }
            });
        }
        
        // Validate phone numbers
        const responsableTel = getFieldByName('responsable_tel');
        if (responsableTel && responsableTel.value && !validatePhone(responsableTel.value)) {
            isValid = false;
            responsableTel.classList.add('error');
            errorMessages.push('Le numéro de téléphone du responsable n\'est pas valide');
        }
        
        const entraineurTel = getFieldByName('entraineur_tel');
        if (entraineurTel && entraineurTel.value && !validatePhone(entraineurTel.value)) {
            isValid = false;
            entraineurTel.classList.add('error');
            errorMessages.push('Le numéro de téléphone de l\'entraîneur n\'est pas valide');
        }
        
        if (!isValid) {
            showNotification('Veuillez corriger les erreurs dans les informations du responsable et de l\'entraîneur', 'error');
        }
        
        return isValid;
    }
    
    function validateStep3() {
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        if (!requirements) {
            showNotification('Veuillez sélectionner un type de beach volleyball valide', 'error');
            return false;
        }
        
        if (state.playerCount < requirements.min) {
            showNotification(`Vous devez avoir au minimum ${requirements.min} joueurs pour le ${state.selectedType}`, 'error');
            return false;
        }
        
        if (state.playerCount > requirements.max) {
            showNotification(`Vous ne pouvez pas avoir plus de ${requirements.max} joueurs pour le ${state.selectedType}`, 'error');
            return false;
        }
        
        // Validate each player
        const players = elements.playersContainer.querySelectorAll('.player-card');
        let isValid = true;
        
        players.forEach((playerCard, index) => {
            const inputs = playerCard.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.hasAttribute('required') && !input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });
        });
        
        // Check règlement acceptance
        const acceptReglement = document.getElementById('accepter_reglement');
        if (!acceptReglement || !acceptReglement.checked) {
            isValid = false;
            showNotification('Vous devez accepter le règlement pour continuer', 'error');
        }
        
        if (!isValid) {
            showNotification('Veuillez compléter les informations de tous les joueurs', 'error');
        }
        
        return isValid;
    }
    
    // ===========================================
    // PLAYER MANAGEMENT
    // ===========================================
    
    function addPlayer() {
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        if (requirements && state.playerCount >= requirements.max) {
            showNotification(`Vous ne pouvez pas ajouter plus de ${requirements.max} joueurs pour le ${state.selectedType}`, 'warning');
            return;
        }
        
        const playerCard = elements.playerTemplate.cloneNode(true);
        playerCard.style.display = 'block';
        playerCard.classList.remove('player-template');
        
        // Update player number
        const playerNumber = state.playerCount + 1;
        playerCard.querySelector('.player-number').textContent = playerNumber;
        
        // Update input names and IDs
        const inputs = playerCard.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('INDEX', state.playerCount));
            }
        });
        
        // Add remove functionality
        const removeBtn = playerCard.querySelector('.remove-player');
        removeBtn.addEventListener('click', () => removePlayer(playerCard));
        
        elements.playersContainer.appendChild(playerCard);
        state.playerCount++;
        updatePlayerCount();
        
        // Focus on first input
        const firstInput = playerCard.querySelector('input');
        if (firstInput) {
            firstInput.focus();
        }
    }
    
    function removePlayer(playerCard) {
        playerCard.remove();
        state.playerCount--;
        updatePlayerCount();
        renumberPlayers();
    }
    
    function renumberPlayers() {
        const players = elements.playersContainer.querySelectorAll('.player-card:not(.player-template)');
        players.forEach((playerCard, index) => {
            playerCard.querySelector('.player-number').textContent = index + 1;
            
            const inputs = playerCard.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
    }
    
    function updatePlayerCount() {
        elements.playerCount.textContent = state.playerCount;
        
        const requirements = PLAYER_REQUIREMENTS[state.selectedType];
        if (requirements) {
            const countElement = elements.playerCount.parentElement;
            if (state.playerCount < requirements.min) {
                countElement.classList.add('insufficient');
                countElement.classList.remove('sufficient');
            } else if (state.playerCount <= requirements.max) {
                countElement.classList.add('sufficient');
                countElement.classList.remove('insufficient');
            }
        }
    }
    
    // ===========================================
    // EVENT LISTENERS
    // ===========================================
    
    // Navigation buttons
    if (elements.prevBtn) {
        elements.prevBtn.addEventListener('click', goToPrevStep);
    }
    
    if (elements.nextBtn) {
        elements.nextBtn.addEventListener('click', goToNextStep);
    }
    
    // Add player button
    if (elements.addPlayerBtn) {
        elements.addPlayerBtn.addEventListener('click', addPlayer);
    }
    
    // Entraineur same as responsable checkbox
    if (elements.entraineurSameCheckbox) {
        elements.entraineurSameCheckbox.addEventListener('change', function() {
            if (this.checked) {
                elements.entraineurFields.style.display = 'none';
                // Clear entraineur fields
                const entraineurInputs = elements.entraineurFields.querySelectorAll('input, select');
                entraineurInputs.forEach(input => {
                    if (input.type !== 'file') {
                        input.value = '';
                    }
                    input.removeAttribute('required');
                });
            } else {
                elements.entraineurFields.style.display = 'block';
                // Make entraineur fields required
                const requiredFields = elements.entraineurFields.querySelectorAll('[data-required]');
                requiredFields.forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        });
    }
    
    // Beach volleyball type change
    const typeSelect = getFieldByName('type_beachvolley');
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            state.selectedType = this.value;
            const requirements = PLAYER_REQUIREMENTS[this.value];
            if (requirements) {
                const helpText = document.getElementById('type-beachvolley-help');
                if (helpText) {
                    helpText.textContent = requirements.description;
                }
                updatePlayerCount();
            }
        });
    }
    
    // Form submission
    if (elements.form) {
        elements.form.addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            elements.submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inscription en cours...';
            elements.submitBtn.disabled = true;
        });
    }
    
    // Add CSS animations
    const style = document.createElement('style');
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
        
        .error {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
        
        .insufficient {
            color: #dc3545 !important;
        }
        
        .sufficient {
            color: #28a745 !important;
        }
        
        .notification-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            opacity: 0.7;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    `;
    document.head.appendChild(style);
    
    // ===========================================
    // INITIALIZATION
    // ===========================================
    
    // Initialize form
    showStep(1);
    
    // Add initial players based on selected type
    const initialType = getFieldByName('type_beachvolley');
    if (initialType && initialType.value) {
        state.selectedType = initialType.value;
        const requirements = PLAYER_REQUIREMENTS[initialType.value];
        if (requirements) {
            // Add minimum required players
            for (let i = 0; i < requirements.min; i++) {
                addPlayer();
            }
        }
    }
    
    console.log('Beach Volleyball Registration Form initialized successfully');
});