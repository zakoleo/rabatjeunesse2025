/**
 * Basketball Team Registration Wizard Validation
 * Clean, focused validation for basketball team registration form
 * Based on football-wizard-validation.js structure
 */

document.addEventListener('DOMContentLoaded', function() {
    // Wizard state
    let currentStep = 1;
    const maxSteps = 3;
    let playerIndex = 0;
    
    // Basketball-specific player limits (will be populated from server)
    let playerLimits = {
        '3x3': { min: 3, max: 4 },
        '5x5': { min: 5, max: 8 }
    };
    
    console.log('Basketball wizard validation initialized');
    
    // Age categories data (will be populated from server)
    let basketballCategories = {};
    
    // Initialize wizard
    initializeWizard();
    
    // Load dynamic data
    loadAgeCategories();
    loadBasketballTypes();
    
    // Function to load age categories from server
    function loadAgeCategories() {
        const categorySelect = document.querySelector('[name="basketball_category_id"]');
        if (categorySelect) {
            // First, load basic info from select options
            const options = categorySelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value && option.textContent.trim()) {
                    basketballCategories[option.value] = {
                        id: option.value,
                        name: option.textContent.trim()
                    };
                }
            });
            
            // Load detailed age ranges via AJAX
            fetchDetailedCategoryData();
            
            // Set up category change listener
            categorySelect.addEventListener('change', function() {
                console.log('Category changed, will validate player ages');
                setTimeout(validateAllPlayerAges, 100);
            });
        }
    }
    
    // Fetch detailed category data from server
    function fetchDetailedCategoryData() {
        fetch('/teams/getCategories?sport_id=2')
            .then(response => response.json())
            .then(data => {
                console.log('Received detailed category data:', data);
                
                // Update categories with database info
                data.categories.forEach(category => {
                    if (basketballCategories[category.id]) {
                        basketballCategories[category.id] = {
                            ...basketballCategories[category.id],
                            minAge: category.min_age || null,
                            maxAge: category.max_age || null,
                            minBirthYear: category.min_birth_year,
                            maxBirthYear: category.max_birth_year,
                            minDate: category.min_birth_date ? new Date(category.min_birth_date) : null,
                            maxDate: category.max_birth_date ? new Date(category.max_birth_date) : null,
                            format: 'database'
                        };
                    }
                });
                
                console.log('Updated basketball categories with database data:', basketballCategories);
            })
            .catch(error => {
                console.warn('Could not load detailed category data:', error);
                console.log('Using fallback parsing from select options');
                fallbackCategoryParsing();
            });
    }

    // Load basketball types from server
    function loadBasketballTypes() {
        // Basketball types are simpler than football, use static configuration
        console.log('Using basketball player limits:', playerLimits);
    }
    
    // Fallback to parsing from select option text
    function fallbackCategoryParsing() {
        const categorySelect = document.querySelector('[name="basketball_category_id"]');
        if (categorySelect) {
            const currentYear = new Date().getFullYear();
            const options = categorySelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value && option.textContent.trim()) {
                    const text = option.textContent.trim();
                    const ageRange = extractAgeRange(text, currentYear);
                    if (ageRange && basketballCategories[option.value]) {
                        basketballCategories[option.value] = {
                            ...basketballCategories[option.value],
                            ...ageRange
                        };
                    }
                }
            });
        }
    }
    
    // Extract age range from category text 
    function extractAgeRange(text, currentYear) {
        console.log('Parsing category text:', text);
        
        const agePatterns = [
            /(\d{4})\s*-\s*(\d{4})/,           // 2010-2012 format
            /(\d{4})\/(\d{4})/,                // 2010/2012 format
            /-(\d+)\s*ans?/i,                  // -12 ans, -15 ans format
            /\+(\d+)\s*ans?/i,                 // +21 ans format
            /U(\d+)/i,                         // U17 format
            /Senior/i                          // Senior format
        ];
        
        for (let pattern of agePatterns) {
            const match = text.match(pattern);
            if (match) {
                if (pattern.source.includes('Senior')) {
                    return { 
                        minAge: 18, 
                        maxAge: 35,
                        minBirthYear: currentYear - 35,
                        maxBirthYear: currentYear - 18,
                        format: 'fallback' 
                    };
                } else if (pattern.source.includes('U')) {
                    const maxAge = parseInt(match[1]);
                    const minAge = Math.max(6, maxAge - 5);
                    return { 
                        minAge: minAge, 
                        maxAge: maxAge - 1,
                        minBirthYear: currentYear - (maxAge - 1),
                        maxBirthYear: currentYear - minAge,
                        format: 'fallback' 
                    };
                } else if (pattern.source.includes('\\+.*ans')) {
                    const minAge = parseInt(match[1]);
                    return { 
                        minAge: minAge, 
                        maxAge: 35,
                        minBirthYear: currentYear - 35,
                        maxBirthYear: currentYear - minAge,
                        format: 'fallback' 
                    };
                } else if (pattern.source.includes('-.*ans')) {
                    const maxAge = parseInt(match[1]);
                    const minAge = Math.max(6, maxAge - 3);
                    return { 
                        minAge: minAge, 
                        maxAge: maxAge,
                        minBirthYear: currentYear - maxAge,
                        maxBirthYear: currentYear - minAge,
                        format: 'fallback' 
                    };
                } else {
                    return { 
                        minBirthYear: parseInt(match[1]),
                        maxBirthYear: parseInt(match[2]),
                        format: 'fallback'
                    };
                }
            }
        }
        
        return { minAge: 6, maxAge: 35, format: 'fallback' };
    }
    
    function initializeWizard() {
        console.log('Initializing basketball wizard...');
        
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (nextBtn) nextBtn.addEventListener('click', nextStep);
        if (prevBtn) prevBtn.addEventListener('click', prevStep);
        
        showStep(currentStep);
        
        // Set up real-time validation for all form fields
        setupRealTimeValidation();
        
        // Set up basketball type change handler for player limits
        const basketballTypeSelect = document.querySelector('[name="type_basketball"]');
        if (basketballTypeSelect) {
            basketballTypeSelect.addEventListener('change', updatePlayerRequirements);
            // Initialize on load
            setTimeout(updatePlayerRequirements, 100);
        }
        
        // Set up "same as manager" checkbox functionality
        setupSameAsManagerCheckbox();
    }
    
    function setupSameAsManagerCheckbox() {
        const checkbox = document.getElementById('sameAsResponsable');
        if (!checkbox) {
            console.warn('sameAsResponsable checkbox not found');
            return;
        }

        const entraineurFields = document.getElementById('entraineurFields');
        
        function toggleTrainerFields() {
            const isChecked = checkbox.checked;
            console.log('Same as manager checkbox changed:', isChecked);
            
            if (entraineurFields) {
                entraineurFields.style.display = isChecked ? 'none' : 'block';
                
                // Clear validation errors and requirements when hiding fields
                if (isChecked) {
                    const trainerFields = entraineurFields.querySelectorAll('input, select');
                    trainerFields.forEach(field => {
                        field.removeAttribute('required');
                        clearFieldError(field);
                        field.classList.remove('error', 'valid');
                    });
                } else {
                    // Restore required attributes when showing fields
                    const requiredFields = [
                        'entraineur_nom_complet',
                        'entraineur_date_naissance', 
                        'entraineur_tel',
                        'entraineur_cin_recto',
                        'entraineur_cin_verso'
                    ];
                    
                    requiredFields.forEach(fieldName => {
                        const field = document.querySelector(`[name="${fieldName}"]`);
                        if (field) {
                            field.setAttribute('required', 'true');
                        }
                    });
                }
            }
        }
        
        // Set up event listener
        checkbox.addEventListener('change', toggleTrainerFields);
        
        // Initialize state
        setTimeout(toggleTrainerFields, 100);
    }
    
    function updatePlayerRequirements() {
        const basketballTypeSelect = document.querySelector('[name="type_basketball"]');
        if (!basketballTypeSelect) return;
        
        const selectedType = basketballTypeSelect.value;
        console.log('Basketball type changed to:', selectedType);
        
        if (!selectedType) {
            console.log('No basketball type selected yet');
            updatePlayerCountDisplay('', null);
            return;
        }
        
        if (playerLimits[selectedType]) {
            const limits = playerLimits[selectedType];
            console.log('Player limits for', selectedType, ':', limits);
            
            // Update player sections if we're on step 3
            if (currentStep === 3) {
                generatePlayerForms(limits.min);
            }
            
            // Update any info displays
            updatePlayerCountDisplay(selectedType, limits);
        }
    }
    
    function updatePlayerCountDisplay(type, limits) {
        const nombreJoueursInfo = document.getElementById('nombreJoueursRequis');
        if (nombreJoueursInfo) {
            if (!type || !limits) {
                nombreJoueursInfo.textContent = 'Sélectionnez d\'abord le type de basketball';
            } else {
                nombreJoueursInfo.textContent = `${type}: Minimum ${limits.min} joueurs, Maximum ${limits.max} joueurs`;
            }
        }
    }
    
    function generatePlayerForms(minPlayers) {
        const playersContainer = document.getElementById('joueursContainer');
        if (!playersContainer) return;
        
        // Clear existing player forms
        playersContainer.innerHTML = '';
        
        // Generate minimum required player forms
        for (let i = 0; i < minPlayers; i++) {
            const playerForm = createPlayerForm(i);
            playersContainer.appendChild(playerForm);
        }
        
        playerIndex = minPlayers;
        
        // Set up the existing "Add Player" button
        const existingAddBtn = document.getElementById('ajouterJoueur');
        if (existingAddBtn) {
            existingAddBtn.onclick = addPlayerForm;
        }
    }
    
    function createPlayerForm(index) {
        const playerDiv = document.createElement('div');
        playerDiv.className = 'player-form card mb-3';
        playerDiv.innerHTML = `
            <div class="card-header">
                <h5>Joueur ${index + 1}</h5>
                ${index >= getMinPlayers() ? `<button type="button" class="btn btn-sm btn-danger remove-player">Supprimer</button>` : ''}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Nom complet *</label>
                        <input type="text" name="joueurs[${index}][nom_complet]" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Date de naissance *</label>
                        <input type="date" name="joueurs[${index}][date_naissance]" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Numéro CIN/Passeport *</label>
                        <input type="text" name="joueurs[${index}][identifiant]" class="form-control" required>
                        <div class="error-message"></div>
                    </div>
                    <div class="col-md-6">
                        <label>Taille vestimentaire *</label>
                        <select name="joueurs[${index}][taille_vestimentaire]" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="XXXL">XXXL</option>
                        </select>
                        <div class="error-message"></div>
                    </div>
                </div>
            </div>
        `;
        
        // Add remove functionality for optional players
        const removeBtn = playerDiv.querySelector('.remove-player');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => removePlayerForm(playerDiv));
        }
        
        return playerDiv;
    }
    
    function getMinPlayers() {
        const basketballTypeSelect = document.querySelector('[name="type_basketball"]');
        const selectedType = basketballTypeSelect ? basketballTypeSelect.value : '5x5';
        return playerLimits[selectedType] ? playerLimits[selectedType].min : 5;
    }
    
    function addPlayerForm() {
        const basketballTypeSelect = document.querySelector('[name="type_basketball"]');
        const selectedType = basketballTypeSelect ? basketballTypeSelect.value : '5x5';
        const maxPlayers = playerLimits[selectedType] ? playerLimits[selectedType].max : 8;
        
        const currentPlayerCount = document.querySelectorAll('.player-form').length;
        
        if (currentPlayerCount >= maxPlayers) {
            alert(`Maximum ${maxPlayers} joueurs autorisés pour le ${selectedType}`);
            return;
        }
        
        const playersContainer = document.getElementById('joueursContainer');
        if (!playersContainer) return;
        
        const playerForm = createPlayerForm(playerIndex);
        playersContainer.appendChild(playerForm);
        
        playerIndex++;
        setupFieldValidation(playerForm);
    }
    
    function removePlayerForm(playerDiv) {
        const minPlayers = getMinPlayers();
        const currentPlayerCount = document.querySelectorAll('.player-form').length;
        
        if (currentPlayerCount <= minPlayers) {
            alert(`Minimum ${minPlayers} joueurs requis`);
            return;
        }
        
        playerDiv.remove();
        
        // Renumber remaining players
        const playerForms = document.querySelectorAll('.player-form');
        playerForms.forEach((form, index) => {
            const header = form.querySelector('.card-header h5');
            if (header) header.textContent = `Joueur ${index + 1}`;
            
            // Update input names
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.name.includes('joueurs[')) {
                    input.name = input.name.replace(/joueurs\[\d+\]/, `joueurs[${index}]`);
                }
            });
        });
        
        playerIndex = playerForms.length;
    }
    
    function setupRealTimeValidation() {
        // Set up validation for all current form fields
        const formFields = document.querySelectorAll('input, select, textarea');
        formFields.forEach(field => setupFieldValidation(field));
    }
    
    function setupFieldValidation(container = document) {
        const fields = container.querySelectorAll ? container.querySelectorAll('input, select, textarea') : [container];
        
        fields.forEach(field => {
            // Skip if already has event listeners
            if (field.hasAttribute('data-validation-setup')) return;
            field.setAttribute('data-validation-setup', 'true');
            
            field.addEventListener('input', function() {
                validateField(this);
            });
            
            field.addEventListener('change', function() {
                validateField(this);
            });
            
            field.addEventListener('blur', function() {
                validateField(this);
            });
        });
    }
    
    function validateField(field) {
        clearFieldError(field);
        
        // Check required fields
        if (field.hasAttribute('required') && !field.value.trim()) {
            if (field.type !== 'file') {
                showFieldError(field, 'Ce champ est obligatoire');
                return false;
            }
        }
        
        // Check optional fields format
        if (field.value.trim()) {
            return validateOptionalField(field);
        }
        
        // Mark as valid if empty and not required
        field.classList.remove('error');
        field.classList.add('valid');
        return true;
    }
    
    function validateOptionalField(field) {
        const value = field.value.trim();
        
        // Skip validation for WhatsApp if empty (it's optional)
        if (field.name.includes('whatsapp') && !value) {
            console.log('WhatsApp field is empty - considered valid:', field.name);
            field.classList.remove('error');
            return true;
        }
        
        if (!value) return true;
        
        // Type-specific format validation
        switch (field.type) {
            case 'email':
                if (!isValidEmail(value)) {
                    showFieldError(field, 'Format d\'email invalide');
                    return false;
                }
                break;
            case 'tel':
                if (field.name.includes('whatsapp')) {
                    if (!isValidWhatsApp(value)) {
                        showFieldError(field, 'Format WhatsApp invalide (ex: +212612345678 ou 0612345678)');
                        return false;
                    }
                } else {
                    if (!isValidPhone(value)) {
                        showFieldError(field, 'Format de téléphone invalide (ex: 0612345678)');
                        return false;
                    }
                }
                break;
            case 'date':
                if (field.name.includes('date_naissance')) {
                    if (!isValidBirthDate(value, field)) {
                        // Dynamic error message based on field type
                        let errorMessage;
                        if (field.name.includes('joueurs[')) {
                            const category = getSelectedAgeCategory();
                            if (category) {
                                const birthDate = new Date(value);
                                const today = new Date();
                                let age = today.getFullYear() - birthDate.getFullYear();
                                const monthDiff = today.getMonth() - birthDate.getMonth();
                                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                    age--;
                                }
                                if (category.format === 'database' && category.minDate && category.maxDate) {
                                    const startDate = category.minDate.toLocaleDateString('fr-FR');
                                    const endDate = category.maxDate.toLocaleDateString('fr-FR');
                                    errorMessage = `Date de naissance invalide pour ${category.name}. Période acceptée: du ${startDate} au ${endDate} (joueur: ${new Date(value).toLocaleDateString('fr-FR')})`;
                                } else if (category.minBirthYear && category.maxBirthYear) {
                                    const birthYear = new Date(value).getFullYear();
                                    errorMessage = `Année de naissance invalide pour ${category.name}: requis ${category.minBirthYear}-${category.maxBirthYear} (joueur: ${birthYear})`;
                                } else if (category.minAge !== null && category.maxAge !== null) {
                                    errorMessage = `Âge invalide pour ${category.name}: requis ${category.minAge}-${category.maxAge} ans (joueur: ${age} ans)`;
                                } else {
                                    errorMessage = `Catégorie ${category.name}: âge accepté 6-35 ans (joueur: ${age} ans)`;
                                }
                            } else {
                                errorMessage = 'Âge accepté: 6-35 ans. Sélectionnez une catégorie d\'âge pour validation précise par dates de naissance';
                            }
                        } else {
                            errorMessage = 'L\'âge doit être entre 16 et 80 ans';
                        }
                        showFieldError(field, errorMessage);
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
        
        // Mark as valid
        field.classList.remove('error');
        field.classList.add('valid');
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
    
    function isValidWhatsApp(whatsapp) {
        const cleanWhatsApp = whatsapp.replace(/[\s\-()]/g, '');
        const patterns = [
            /^\+212[5-7][0-9]{8}$/,
            /^212[5-7][0-9]{8}$/,
            /^0[5-7][0-9]{8}$/
        ];
        return patterns.some(pattern => pattern.test(cleanWhatsApp));
    }
    
    function isValidName(name) {
        return /^[a-zA-ZÀ-ÿ\s\-'\.]+$/.test(name);
    }
    
    function isValidBirthDate(dateValue, field) {
        const birthDate = new Date(dateValue);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear() - 
                   ((today.getMonth() < birthDate.getMonth() || 
                     (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) ? 1 : 0);
        
        if (field.name.includes('joueurs[')) {
            // Player age validation
            const category = getSelectedAgeCategory();
            if (category) {
                return isValidAgeForCategory(birthDate, category);
            }
            return age >= 6 && age <= 35;
        } else {
            // Manager/Coach age validation
            return age >= 16 && age <= 80;
        }
    }
    
    function getSelectedAgeCategory() {
        const categorySelect = document.querySelector('[name="basketball_category_id"]');
        if (!categorySelect || !categorySelect.value) return null;
        
        return basketballCategories[categorySelect.value] || null;
    }
    
    function isValidAgeForCategory(birthDate, category) {
        // Priority 1: Database date ranges (most accurate)
        if (category.format === 'database' && category.minDate && category.maxDate) {
            return birthDate >= category.minDate && birthDate <= category.maxDate;
        }
        
        // Priority 2: Birth year validation 
        if (category.minBirthYear && category.maxBirthYear) {
            const birthYear = birthDate.getFullYear();
            return birthYear >= category.minBirthYear && birthYear <= category.maxBirthYear;
        }
        
        // Priority 3: Calculated age ranges (fallback)
        if (category.minAge !== null && category.maxAge !== null) {
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear() - 
                       ((today.getMonth() < birthDate.getMonth() || 
                         (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) ? 1 : 0);
            return age >= category.minAge && age <= category.maxAge;
        }
        
        return true;
    }
    
    function validateAllPlayerAges() {
        const playerBirthInputs = document.querySelectorAll('input[name*="joueurs"][name*="date_naissance"]');
        playerBirthInputs.forEach(validateField);
    }
    
    function showFieldError(field, message) {
        field.classList.add('error');
        field.classList.remove('valid');
        
        let errorDiv = field.parentNode.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
    
    function clearFieldError(field) {
        field.classList.remove('error');
        const errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }
    
    function validateStep(step) {
        console.log('Validating step:', step);
        let isValid = true;
        const stepContainer = document.querySelector(`[data-step="${step}"]`);
        
        if (!stepContainer) {
            console.error('Step container not found for step:', step);
            return false;
        }
        
        // Clear previous errors
        stepContainer.querySelectorAll('.error-message').forEach(error => {
            error.style.display = 'none';
        });
        stepContainer.querySelectorAll('.error').forEach(field => {
            field.classList.remove('error');
        });
        
        // Validate all fields in current step
        const fields = stepContainer.querySelectorAll('input[required], select[required], textarea[required]');
        fields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        // Step-specific validation
        if (step === 3) {
            isValid = isValid && validatePlayerCount();
        }
        
        console.log('Step', step, 'validation result:', isValid);
        return isValid;
    }
    
    function validatePlayerCount() {
        const basketballTypeSelect = document.querySelector('[name="type_basketball"]');
        const selectedType = basketballTypeSelect ? basketballTypeSelect.value : '5x5';
        const limits = playerLimits[selectedType] || { min: 5, max: 8 };
        
        const playerCount = document.querySelectorAll('.player-form').length;
        
        if (playerCount < limits.min) {
            alert(`Minimum ${limits.min} joueurs requis pour le ${selectedType}`);
            return false;
        }
        
        if (playerCount > limits.max) {
            alert(`Maximum ${limits.max} joueurs autorisés pour le ${selectedType}`);
            return false;
        }
        
        return true;
    }
    
    function nextStep() {
        if (validateStep(currentStep)) {
            if (currentStep < maxSteps) {
                currentStep++;
                showStep(currentStep);
                
                // Generate player forms when reaching step 3
                if (currentStep === 3) {
                    const basketballTypeSelect = document.querySelector('[name="type_basketball"]');
                    const selectedType = basketballTypeSelect ? basketballTypeSelect.value : '';
                    
                    if (!selectedType) {
                        alert('Veuillez sélectionner le type de basketball avant de continuer');
                        currentStep--; // Go back to step 1
                        showStep(currentStep);
                        return;
                    }
                    
                    const minPlayers = getMinPlayers();
                    generatePlayerForms(minPlayers);
                    setupRealTimeValidation();
                }
            }
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.wizard-step').forEach(stepDiv => {
            stepDiv.classList.remove('active');
        });
        
        // Show current step
        const currentStepDiv = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (currentStepDiv) {
            currentStepDiv.classList.add('active');
        }
        
        // Update progress bar
        document.querySelectorAll('.progress-step').forEach((progressStep, index) => {
            if (index + 1 <= step) {
                progressStep.classList.add('active');
            } else {
                progressStep.classList.remove('active');
            }
        });
        
        // Update navigation buttons
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (prevBtn) prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
        
        if (step === maxSteps) {
            if (nextBtn) nextBtn.style.display = 'none';
            if (submitBtn) submitBtn.style.display = 'inline-block';
        } else {
            if (nextBtn) nextBtn.style.display = 'inline-block';
            if (submitBtn) submitBtn.style.display = 'none';
        }
        
        // Scroll to top of form
        const formContainer = document.querySelector('.wizard-container');
        if (formContainer) {
            formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    console.log('Basketball wizard validation script loaded successfully');
});