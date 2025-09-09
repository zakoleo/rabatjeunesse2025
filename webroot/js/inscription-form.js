/**
 * Generic Sports Team Registration Wizard Validation
 * Works for all sports: Football, Basketball, Handball, Volleyball, Beach Volleyball
 */

document.addEventListener('DOMContentLoaded', function() {
    // Wizard state
    let currentStep = 1;
    const maxSteps = 3;
    let playerIndex = 0;
    
    // Player limits for different sports and types
    const playerLimits = {
        // Football
        '5x5': { min: 5, max: 10 },
        '6x6': { min: 6, max: 12 },
        '11x11': { min: 11, max: 18 },
        // Basketball
        '3x3': { min: 3, max: 6 },
        '5v5': { min: 5, max: 10 },
        // Handball
        '7v7': { min: 7, max: 10 },
        // Volleyball
        '6v6': { min: 6, max: 10 },
        // Beach Volleyball
        '2x2': { min: 2, max: 4 }
    };
    
    console.log('Generic sports wizard validation initialized');
    
    // Initialize wizard
    initializeWizard();
    
    function initializeWizard() {
        updateStepDisplay();
        setupNavigation();
        setupCoachToggle();
        setupPlayerManagement();
        setupRealTimeValidation();
        updatePlayerRequirements();
    }
    
    // Navigation setup
    function setupNavigation() {
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const form = document.querySelector('form');
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next button clicked - validating step:', currentStep);
                
                if (validateCurrentStep()) {
                    if (currentStep < maxSteps) {
                        currentStep++;
                        updateStepDisplay();
                        console.log('Moved to step:', currentStep);
                    }
                } else {
                    console.log('Validation failed for step:', currentStep);
                }
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                }
            });
        }
        
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submitted - validating all steps');
                if (!validateAllSteps()) {
                    e.preventDefault();
                    console.log('Form submission blocked - validation failed');
                }
            });
        }
    }
    
    // Update step display
    function updateStepDisplay() {
        console.log('Updating display for step:', currentStep);
        
        // Update progress indicators
        document.querySelectorAll('.progress-step').forEach((step, index) => {
            const stepNum = index + 1;
            step.classList.remove('active', 'completed');
            if (stepNum === currentStep) {
                step.classList.add('active');
            } else if (stepNum < currentStep) {
                step.classList.add('completed');
            }
        });
        
        // Show/hide wizard steps
        document.querySelectorAll('.wizard-step').forEach((step, index) => {
            const stepNum = index + 1;
            if (stepNum === currentStep) {
                step.classList.add('active');
                step.style.display = 'block';
            } else {
                step.classList.remove('active');
                step.style.display = 'none';
            }
        });
        
        // Update navigation buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (prevBtn) {
            prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
        }
        
        if (nextBtn && submitBtn) {
            if (currentStep < maxSteps) {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            } else {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            }
        }
    }
    
    // Validate current step
    function validateCurrentStep() {
        const stepElement = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        if (!stepElement) {
            console.log('Step element not found for step:', currentStep);
            return true;
        }
        
        console.log(`Validating step ${currentStep}`);
        
        // Clear previous errors
        clearStepErrors(stepElement);
        
        let isValid = true;
        
        // Get all required fields in current step
        const requiredFields = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
        
        requiredFields.forEach(field => {
            // Skip file fields during wizard navigation
            if (field.type === 'file') {
                console.log('Skipping file field:', field.name);
                return;
            }
            
            // Skip disabled fields
            if (field.disabled) {
                console.log('Skipping disabled field:', field.name);
                return;
            }
            
            // Skip hidden fields
            if (field.offsetParent === null) {
                console.log('Skipping hidden field:', field.name);
                return;
            }
            
            // Skip coach fields if same as manager
            if (currentStep === 2 && field.name.includes('entraineur_')) {
                const sameAsManager = document.getElementById('sameAsResponsable');
                if (sameAsManager && sameAsManager.checked) {
                    console.log('Skipping coach field (same as manager):', field.name);
                    return;
                }
            }
            
            // Validate field
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        // Step-specific validation
        if (currentStep === 3) {
            if (!validatePlayerCount()) {
                isValid = false;
            }
            
            // Check accept terms checkbox
            const acceptTerms = stepElement.querySelector('[name="accepter_reglement"]');
            if (acceptTerms && !acceptTerms.checked) {
                showFieldError(acceptTerms, 'Vous devez accepter le règlement');
                isValid = false;
            }
        }
        
        console.log(`Step ${currentStep} validation result:`, isValid);
        return isValid;
    }
    
    // Validate individual field
    function validateField(field) {
        const value = field.value ? field.value.trim() : '';
        
        // Required field check
        if (!value && field.required) {
            showFieldError(field, 'Ce champ est requis');
            return false;
        }
        
        // Type-specific validation
        if (value) {
            switch (field.type) {
                case 'email':
                    if (!isValidEmail(value)) {
                        showFieldError(field, 'Format d\'email invalide');
                        return false;
                    }
                    break;
                case 'tel':
                    if (!isValidPhone(value)) {
                        showFieldError(field, 'Format de téléphone invalide (ex: 0612345678)');
                        return false;
                    }
                    break;
                case 'date':
                    if (field.name.includes('date_naissance')) {
                        if (!isValidBirthDate(value)) {
                            showFieldError(field, 'L\'âge doit être entre 18 et 70 ans');
                            return false;
                        }
                    }
                    break;
            }
            
            // Name validation
            if (field.name.includes('nom_complet')) {
                if (!isValidName(value)) {
                    showFieldError(field, 'Le nom ne peut contenir que des lettres et espaces');
                    return false;
                }
            }
        }
        
        // Select field validation
        if (field.tagName === 'SELECT' && (!value || value === '')) {
            showFieldError(field, 'Veuillez faire une sélection');
            return false;
        }
        
        // Mark as valid
        field.classList.remove('error');
        field.classList.add('valid');
        console.log('Field validated successfully:', field.name);
        return true;
    }
    
    // Validation helper functions
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function isValidPhone(phone) {
        const cleanPhone = phone.replace(/[\s\-()]/g, '');
        return /^(\+212|0)[5-7][0-9]{8}$/.test(cleanPhone) || /^[0-9]{10}$/.test(cleanPhone);
    }
    
    function isValidName(name) {
        return /^[a-zA-ZÀ-ÿ\s]+$/.test(name);
    }
    
    function isValidBirthDate(dateStr) {
        const birthDate = new Date(dateStr);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        return age >= 18 && age <= 70;
    }
    
    // Player count validation
    function validatePlayerCount() {
        const playersCount = document.querySelectorAll('.joueur-form').length;
        const sportType = getSelectedSportType();
        const limits = playerLimits[sportType];
        
        if (!limits) {
            console.log('Unknown sport type:', sportType, 'using default validation');
            return playersCount >= 2; // Minimum fallback
        }
        
        console.log(`Players: ${playersCount}, Required: ${limits.min}-${limits.max} for ${sportType}`);
        
        if (playersCount < limits.min) {
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, `Vous devez ajouter au moins ${limits.min} joueurs pour cette catégorie`);
            }
            return false;
        }
        
        return true;
    }
    
    // Get selected sport type (works for all sports)
    function getSelectedSportType() {
        // Try different sport type field names
        const typeSelectors = [
            '[name="type_football"]',      // Football
            '[name="type_basketball"]',    // Basketball  
            '[name="type_handball"]',      // Handball
            '[name="type_volleyball"]',    // Volleyball
            '[name="type_beachvolley"]'    // Beach volleyball
        ];
        
        for (const selector of typeSelectors) {
            const field = document.querySelector(selector);
            if (field && field.value) {
                return field.value;
            }
        }
        
        // Fallback - try to detect sport from URL or form
        const url = window.location.href;
        if (url.includes('basketball')) return '3x3';
        if (url.includes('handball')) return '7v7';
        if (url.includes('volleyball')) return '6v6';
        if (url.includes('beachvolley')) return '2x2';
        
        return '5x5'; // Default fallback
    }
    
    // Error display functions
    function showFieldError(field, message) {
        // Clear existing errors
        clearFieldError(field);
        
        // Add error class
        field.classList.add('error');
        field.classList.remove('valid');
        
        // Create error message
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        errorElement.style.color = '#dc3545';
        errorElement.style.fontSize = '0.875rem';
        errorElement.style.marginTop = '0.25rem';
        
        // Insert after field
        field.parentNode.appendChild(errorElement);
        
        console.log('Error shown for field:', field.name, '-', message);
    }
    
    function showContainerError(container, message) {
        // Clear existing errors
        container.querySelectorAll('.error-message').forEach(error => error.remove());
        
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message alert alert-danger';
        errorElement.textContent = message;
        errorElement.style.marginTop = '1rem';
        errorElement.style.padding = '0.75rem';
        errorElement.style.borderRadius = '4px';
        container.appendChild(errorElement);
    }
    
    function clearFieldError(field) {
        field.classList.remove('error');
        const errorElements = field.parentNode.querySelectorAll('.error-message');
        errorElements.forEach(error => error.remove());
    }
    
    function clearStepErrors(stepElement) {
        stepElement.querySelectorAll('.error').forEach(field => {
            field.classList.remove('error');
        });
        stepElement.querySelectorAll('.error-message').forEach(error => {
            error.remove();
        });
    }
    
    // Validate all steps (for form submission)
    function validateAllSteps() {
        let allValid = true;
        
        for (let step = 1; step <= maxSteps; step++) {
            const originalStep = currentStep;
            currentStep = step;
            
            if (!validateCurrentStep()) {
                allValid = false;
                // Stay on first invalid step
                if (originalStep > step) {
                    updateStepDisplay();
                    break;
                }
            }
            
            currentStep = originalStep;
        }
        
        return allValid;
    }
    
    // Coach toggle functionality
    function setupCoachToggle() {
        const checkbox = document.getElementById('sameAsResponsable');
        if (checkbox) {
            checkbox.addEventListener('change', toggleCoachFields);
            // Initialize on load
            toggleCoachFields();
        }
    }
    
    function toggleCoachFields() {
        const checkbox = document.getElementById('sameAsResponsable');
        const coachFields = document.getElementById('entraineurFields');
        
        if (!checkbox || !coachFields) return;
        
        const coachInputs = coachFields.querySelectorAll('input, select');
        
        if (checkbox.checked) {
            // Hide coach fields
            coachFields.style.display = 'none';
            
            // Copy manager data to coach fields
            const fieldMapping = {
                'responsable_nom_complet': 'entraineur_nom_complet',
                'responsable_date_naissance': 'entraineur_date_naissance',
                'responsable_tel': 'entraineur_tel',
                'responsable_whatsapp': 'entraineur_whatsapp'
            };
            
            Object.entries(fieldMapping).forEach(([managerName, coachName]) => {
                const managerField = document.querySelector(`[name="${managerName}"]`);
                const coachField = document.querySelector(`[name="${coachName}"]`);
                
                if (managerField && coachField && managerField.type !== 'file') {
                    coachField.value = managerField.value;
                }
            });
            
            // Disable coach fields
            coachInputs.forEach(field => {
                field.disabled = true;
                if (field.required) {
                    field.setAttribute('data-was-required', 'true');
                    field.required = false;
                }
            });
            
            console.log('Coach fields hidden and data copied');
        } else {
            // Show coach fields
            coachFields.style.display = 'block';
            
            // Enable coach fields
            coachInputs.forEach(field => {
                field.disabled = false;
                if (field.hasAttribute('data-was-required')) {
                    field.required = true;
                    field.removeAttribute('data-was-required');
                }
                // Clear copied values (except files)
                if (field.type !== 'file') {
                    field.value = '';
                }
            });
            
            console.log('Coach fields shown and enabled');
        }
    }
    
    // Player management
    function setupPlayerManagement() {
        const addBtn = document.getElementById('ajouterJoueur');
        if (addBtn) {
            addBtn.addEventListener('click', addPlayer);
        }
        
        // Update requirements when sport type changes
        const typeSelectors = [
            '[name="type_football"]',
            '[name="type_basketball"]',
            '[name="type_handball"]',
            '[name="type_volleyball"]',
            '[name="type_beachvolley"]'
        ];
        
        typeSelectors.forEach(selector => {
            const field = document.querySelector(selector);
            if (field) {
                field.addEventListener('change', updatePlayerRequirements);
            }
        });
    }
    
    function addPlayer() {
        playerIndex++;
        const container = document.getElementById('joueursContainer');
        if (!container) return;
        
        // Clear existing errors
        container.querySelectorAll('.error-message').forEach(error => error.remove());
        
        const sportType = getSelectedSportType();
        const limits = playerLimits[sportType];
        const currentPlayers = container.querySelectorAll('.joueur-form').length;
        
        if (limits && currentPlayers >= limits.max) {
            showContainerError(container, `Maximum ${limits.max} joueurs pour cette catégorie`);
            return;
        }
        
        const playerDiv = document.createElement('div');
        playerDiv.className = 'joueur-form';
        playerDiv.innerHTML = `
            <div class="joueur-header">
                <h4>Joueur ${playerIndex}</h4>
                <button type="button" class="btn-remove-joueur" onclick="removePlayer(this)">×</button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" name="joueurs[${playerIndex}][nom_complet]" required>
                </div>
                <div class="form-group">
                    <label>Date de naissance *</label>
                    <input type="date" name="joueurs[${playerIndex}][date_naissance]" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>N° CIN ou Passeport *</label>
                    <input type="text" name="joueurs[${playerIndex}][identifiant]" required>
                </div>
                <div class="form-group">
                    <label>Taille vestimentaire *</label>
                    <select name="joueurs[${playerIndex}][taille_vestimentaire]" required>
                        <option value="">Sélectionner une taille</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="XXXL">XXXL</option>
                    </select>
                </div>
            </div>
        `;
        
        container.appendChild(playerDiv);
        console.log('Player added:', playerIndex);
    }
    
    // Global remove player function
    window.removePlayer = function(button) {
        const playerDiv = button.closest('.joueur-form');
        if (playerDiv) {
            playerDiv.remove();
            console.log('Player removed');
        }
    };
    
    function updatePlayerRequirements() {
        const sportType = getSelectedSportType();
        const limits = playerLimits[sportType];
        const requirementsElement = document.getElementById('nombreJoueursRequis');
        
        if (limits && requirementsElement) {
            requirementsElement.innerHTML = `
                <strong>Composition de l'équipe</strong><br>
                Minimum: ${limits.min} joueurs<br>
                Maximum: ${limits.max} joueurs
            `;
        }
    }
    
    // Real-time validation
    function setupRealTimeValidation() {
        document.addEventListener('input', function(e) {
            const field = e.target;
            if (field.matches('input[required], select[required], textarea[required]')) {
                // Clear error when user starts typing
                clearFieldError(field);
                
                // Add valid class if field has value
                if (field.value.trim()) {
                    field.classList.add('valid');
                } else {
                    field.classList.remove('valid');
                }
            }
        });
    }
    
    console.log('Generic sports wizard validation setup complete');
});