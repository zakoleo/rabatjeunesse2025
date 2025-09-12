/**
 * Volleyball Team Registration Wizard Validation
 * Exact copy of football validation pattern for consistent behavior
 */

document.addEventListener('DOMContentLoaded', function() {
    // Wizard state
    let currentStep = 1;
    const maxSteps = 3;
    let playerIndex = 0;
    
    // Volleyball-specific player limits (will be populated from server)
    let playerLimits = {};
    let playerLimitsLoaded = false;
    
    console.log('Volleyball wizard validation initialized');
    
    // Age categories data (will be populated from server)
    let volleyballCategories = {};
    let categoriesLoaded = false;
    
    // Parse date string to local Date object avoiding timezone issues
    function parseLocalDate(dateStr) {
        if (!dateStr) return null;
        
        // If it's already a Date object, return it
        if (dateStr instanceof Date) {
            return dateStr;
        }
        
        // Handle YYYY-MM-DD format specifically to avoid timezone issues
        if (typeof dateStr === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
            const [year, month, day] = dateStr.split('-').map(Number);
            const localDate = new Date(year, month - 1, day); // month is 0-indexed
            return localDate;
        }
        
        // Handle other date formats or datetime strings
        const parsedDate = new Date(dateStr);
        return parsedDate;
    }
    
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
            /^0[5-7][0-9]{8}$/,
            /^[5-7][0-9]{8}$/,
            /^\+[1-9][0-9]{8,14}$/,
            /^[0-9]{10,15}$/
        ];
        return patterns.some(pattern => pattern.test(cleanWhatsApp));
    }
    
    function isValidName(name) {
        return /^[a-zA-ZÀ-ÿ\s\-'\.]+$/.test(name);
    }
    
    function isValidBirthDate(dateStr, field = null) {
        const birthDate = parseLocalDate(dateStr);
        const today = new Date();
        
        // More accurate age calculation
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        // For player fields, validate against selected age category
        if (field && field.name && field.name.includes('joueurs[')) {
            if (!categoriesLoaded) {
                return age >= 6 && age <= 35; // Basic fallback
            }
            
            const category = getSelectedAgeCategory();
            if (category) {
                // Use database date range validation if available
                if (category.minDate && category.maxDate) {
                    const isAfterMin = birthDate >= category.minDate;
                    const isBeforeMax = birthDate <= category.maxDate;
                    return isAfterMin && isBeforeMax;
                } else if (category.minAge !== null && category.maxAge !== null) {
                    return age >= category.minAge && age <= category.maxAge;
                } else {
                    return age >= 6 && age <= 35; // General fallback
                }
            } else {
                return age >= 6 && age <= 35;
            }
        }
        
        // For non-player fields (manager/coach), use general age range
        return age >= 16 && age <= 80;
    }
    
    // Error display functions
    function showFieldError(field, message) {
        clearFieldError(field);
        
        field.classList.add('error', 'form-control');
        field.classList.remove('valid');
        
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }
    
    function showContainerError(container, message) {
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
    
    // Validate individual field
    function validateField(field) {
        const value = field.value ? field.value.trim() : '';
        
        // Required field check
        if (!value && field.required) {
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
    
    // Validate field format only (for optional fields with values)
    function validateOptionalField(field) {
        const value = field.value.trim();
        
        // Skip validation for WhatsApp if empty (it's optional)
        if (field.name.includes('whatsapp') && !value) {
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
        
        // Clear any existing errors
        clearFieldError(field);
        field.classList.add('valid');
        return true;
    }
    
    // Get selected age category
    function getSelectedAgeCategory() {
        const categorySelect = document.querySelector('[name="volleyball_category_id"]');
        
        if (categorySelect && categorySelect.value) {
            const selectedId = categorySelect.value;
            const category = volleyballCategories[selectedId];
            return category;
        }
        
        return null;
    }
    
    // Get selected volleyball type
    function getSelectedVolleyballType() {
        const typeField = document.querySelector('[name="type_volleyball"]');
        const selectedValue = typeField ? typeField.value : null;
        
        if (selectedValue) {
            return selectedValue;
        }
        
        return null;
    }
    
    // Validate all player ages against selected category
    function validateAllPlayerAges() {
        const selectedCategory = getSelectedAgeCategory();
        if (!selectedCategory) {
            return;
        }
        
        const playerForms = document.querySelectorAll('.joueur-form');
        playerForms.forEach((playerForm, index) => {
            const birthDateField = playerForm.querySelector('[name*="date_naissance"]');
            if (birthDateField && birthDateField.value) {
                validatePlayerAge(birthDateField, selectedCategory);
            }
        });
    }
    
    // Validate individual player age
    function validatePlayerAge(birthDateField, category = null) {
        if (!category) {
            category = getSelectedAgeCategory();
        }
        
        if (!category) {
            return true;
        }
        
        const birthDate = new Date(birthDateField.value);
        const today = new Date();
        
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        const isValidAge = age >= category.minAge && age <= category.maxAge;
        
        clearFieldError(birthDateField);
        
        if (!isValidAge) {
            let message;
            if (category.minDate && category.maxDate) {
                const startDate = category.minDate.toLocaleDateString('fr-FR');
                const endDate = category.maxDate.toLocaleDateString('fr-FR');
                const playerDate = new Date(birthDateField.value).toLocaleDateString('fr-FR');
                message = `Date de naissance invalide pour ${category.name}. Période acceptée: du ${startDate} au ${endDate} (joueur: ${playerDate})`;
            } else if (category.minBirthYear && category.maxBirthYear) {
                const birthYear = new Date(birthDateField.value).getFullYear();
                message = `Année de naissance invalide pour la catégorie ${category.name}. Années requises: ${category.minBirthYear}-${category.maxBirthYear} (joueur: ${birthYear})`;
            } else if (category.minAge !== undefined && category.maxAge !== undefined) {
                message = `Âge invalide pour la catégorie ${category.name}. Âge requis: ${category.minAge}-${category.maxAge} ans (joueur: ${age} ans)`;
            } else {
                message = `Âge invalide pour la catégorie ${category.name} (joueur: ${age} ans)`;
            }
            showFieldError(birthDateField, message);
            return false;
        } else {
            birthDateField.classList.add('valid', 'form-control');
            birthDateField.classList.remove('error');
            return true;
        }
    }
    
    // Player count validation
    function validatePlayerCount() {
        if (!playerLimitsLoaded) {
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, 'Erreur: Les données de validation ne sont pas chargées. Veuillez rafraîchir la page.');
            }
            return false;
        }
        
        const playersCount = document.querySelectorAll('.joueur-form').length;
        const volleyballType = getSelectedVolleyballType();
        const limits = playerLimits[volleyballType];
        
        if (!limits) {
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, `Erreur: Type de volleyball "${volleyballType}" non reconnu. Veuillez sélectionner un autre type.`);
            }
            return false;
        }
        
        if (playersCount < limits.min) {
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, `Vous devez ajouter au moins ${limits.min} joueurs pour le ${volleyballType}`);
            }
            return false;
        }
        
        return true;
    }
    
    // Validate current step
    function validateCurrentStep() {
        const stepElement = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        if (!stepElement) {
            return true;
        }
        
        // Clear previous errors
        clearStepErrors(stepElement);
        
        let isValid = true;
        
        // Get all required fields in current step  
        const requiredFields = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
        const allFields = stepElement.querySelectorAll('input, select, textarea');
        const optionalFields = Array.from(allFields).filter(f => !f.required && (f.value.trim() || f.name.includes('whatsapp')));
        
        requiredFields.forEach(field => {
            // Handle file fields during wizard navigation
            if (field.type === 'file') {
                // Skip coach file fields if same as manager
                if (currentStep === 2 && field.name.includes('entraineur_')) {
                    const sameAsManager = document.getElementById('sameAsResponsable');
                    if (sameAsManager && sameAsManager.checked) {
                        return;
                    }
                }
                
                if (field.required && (!field.files || field.files.length === 0)) {
                    showFieldError(field, 'Veuillez sélectionner un fichier');
                    isValid = false;
                }
                return;
            }
            
            // Skip disabled fields
            if (field.disabled) {
                return;
            }
            
            // Skip hidden fields
            if (field.offsetParent === null) {
                return;
            }
            
            // Skip coach fields if same as manager
            if (currentStep === 2 && field.name.includes('entraineur_')) {
                const sameAsManager = document.getElementById('sameAsResponsable');
                if (sameAsManager && sameAsManager.checked) {
                    return;
                }
            }
            
            // Validate field
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        // Validate optional fields that have values
        optionalFields.forEach(field => {
            if (field.type === 'file') {
                return;
            }
            
            if (field.disabled) {
                return;
            }
            
            if (field.offsetParent === null) {
                return;
            }
            
            if (currentStep === 2 && field.name.includes('entraineur_')) {
                const sameAsManager = document.getElementById('sameAsResponsable');
                if (sameAsManager && sameAsManager.checked) {
                    return;
                }
            }
            
            if (!validateOptionalField(field)) {
                isValid = false;
            }
        });
        
        // Step-specific validation
        if (currentStep === 3) {
            if (!validatePlayerCount()) {
                isValid = false;
            }
        }
        
        return isValid;
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
    
    // Function to load age categories from server
    function loadAgeCategories() {
        const categorySelect = document.querySelector('[name="volleyball_category_id"]');
        if (categorySelect) {
            fetchDetailedCategoryData();
            
            categorySelect.addEventListener('change', function() {
                updatePlayerRequirements();
                setTimeout(validateAllPlayerAges, 100);
            });
        }
    }
    
    // Fetch detailed category data from server
    function fetchDetailedCategoryData() {
        const categoriesUrl = (window.API_URLS && window.API_URLS.getVolleyballCategories) 
            ? window.API_URLS.getVolleyballCategories
            : (window.APP_BASE_URL || '') + 'teams/getVolleyballCategories';
        
        fetch(categoriesUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.categories && Array.isArray(data.categories)) {
                    volleyballCategories = {};
                    
                    data.categories.forEach(category => {
                        let minDate = null;
                        let maxDate = null;
                        
                        if (category.min_date && category.max_date) {
                            minDate = parseLocalDate(category.min_date);
                            maxDate = parseLocalDate(category.max_date);
                        }
                        else if (category.min_birth_date && category.max_birth_date) {
                            minDate = parseLocalDate(category.min_birth_date);
                            maxDate = parseLocalDate(category.max_birth_date);
                        }
                        else if (category.min_birth_year && category.max_birth_year) {
                            minDate = new Date(category.min_birth_year, 0, 1);
                            maxDate = new Date(category.max_birth_year, 11, 31);
                        }
                        
                        volleyballCategories[category.id] = {
                            id: category.id,
                            name: category.name,
                            minAge: category.min_age || null,
                            maxAge: category.max_age || null,
                            minBirthYear: category.min_birth_year,
                            maxBirthYear: category.max_birth_year,
                            minDate: minDate,
                            maxDate: maxDate,
                            allowedVolleyballTypes: category.allowed_volleyball_types || [],
                            format: 'database'
                        };
                    });
                    
                    categoriesLoaded = true;
                    updateVolleyballTypesDropdown();
                } else {
                    throw new Error('Invalid data format received');
                }
            })
            .catch(error => {
                console.error('Failed to load category data:', error);
                categoriesLoaded = false;
                showGlobalError('Erreur de connexion: Impossible de charger les catégories d\'âge. Veuillez rafraîchir la page ou contacter l\'administrateur.');
                disableFormSubmission('Les données des catégories ne peuvent pas être chargées. Veuillez rafraîchir la page.');
            });
    }

    // Filter volleyball types based on selected category
    function updateVolleyballTypesDropdown() {
        const categorySelect = document.querySelector('[name="volleyball_category_id"]');
        const typeSelect = document.querySelector('[name="type_volleyball"]');
        
        if (!categorySelect || !typeSelect || !categorySelect.value) {
            return;
        }
        
        const selectedCategory = volleyballCategories[categorySelect.value];
        if (!selectedCategory) {
            return;
        }
        
        // Store currently selected value
        const currentValue = typeSelect.value;
        
        // Clear all options except the placeholder
        const placeholder = typeSelect.querySelector('option[value=""]');
        typeSelect.innerHTML = '';
        if (placeholder) {
            typeSelect.appendChild(placeholder);
        } else {
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un type de volleyball';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            typeSelect.appendChild(defaultOption);
        }
        
        // Add allowed volleyball types for this category
        if (selectedCategory.allowedVolleyballTypes && Array.isArray(selectedCategory.allowedVolleyballTypes)) {
            selectedCategory.allowedVolleyballTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = `${type.name} (${type.min_players}-${type.max_players} joueurs)`;
                option.dataset.minPlayers = type.min_players;
                option.dataset.maxPlayers = type.max_players;
                option.dataset.code = type.code;
                typeSelect.appendChild(option);
            });
            
            // Restore previous selection if it's still valid
            if (currentValue && typeSelect.querySelector(`option[value="${currentValue}"]`)) {
                typeSelect.value = currentValue;
            }
        }
        
        // Update player requirements display
        updatePlayerRequirements();
    }

    // Load volleyball types from server
    function loadVolleyballTypes() {
        const volleyballTypesUrl = (window.API_URLS && window.API_URLS.getVolleyballTypes) 
            ? window.API_URLS.getVolleyballTypes
            : (window.APP_BASE_URL || '') + 'teams/getVolleyballTypes';
        
        fetch(volleyballTypesUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.volleyball_types && Array.isArray(data.volleyball_types)) {
                    playerLimits = {};
                    data.volleyball_types.forEach(type => {
                        playerLimits[type.code] = {
                            min: type.min_players,
                            max: type.max_players,
                            name: type.name,
                            id: type.id
                        };
                    });
                    
                    playerLimitsLoaded = true;
                    updatePlayerRequirements();
                    if (currentStep === 3) {
                        generateMinimumPlayers();
                    }
                } else {
                    throw new Error('Invalid data format received');
                }
            })
            .catch(error => {
                console.error('Failed to load volleyball types data:', error);
                playerLimitsLoaded = false;
                showGlobalError('Erreur de connexion: Impossible de charger les types de volleyball. Veuillez rafraîchir la page ou contacter l\'administrateur.');
                disableFormSubmission('Les données du formulaire ne peuvent pas être chargées. Veuillez rafraîchir la page.');
            });
    }
    
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
        const form = document.getElementById('inscriptionForm');
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next button clicked - current step:', currentStep);
                
                const validationResult = validateCurrentStep();
                console.log('Validation result for step', currentStep, ':', validationResult);
                
                if (validationResult) {
                    if (currentStep < maxSteps) {
                        console.log('Moving from step', currentStep, 'to', currentStep + 1);
                        currentStep++;
                        updateStepDisplay();
                        console.log('Successfully moved to step:', currentStep);
                    }
                } else {
                    console.log('Validation failed for step:', currentStep, '- staying on current step');
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
                if (!validateAllSteps()) {
                    e.preventDefault();
                }
            });
        }
    }
    
    // Update step display
    function updateStepDisplay() {
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
        
        // Generate minimum players when reaching step 3
        if (currentStep === 3) {
            setTimeout(() => {
                generateMinimumPlayers();
            }, 100);
        }
    }
    
    // Coach toggle functionality
    function setupCoachToggle() {
        const checkbox = document.getElementById('sameAsResponsable');
        if (checkbox) {
            checkbox.addEventListener('change', toggleCoachFields);
            toggleCoachFields();
        }
    }
    
    function toggleCoachFields() {
        const checkbox = document.getElementById('sameAsResponsable');
        const coachFields = document.getElementById('entraineurFields');
        
        if (!checkbox || !coachFields) {
            return;
        }
        
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
                if (field.type !== 'file') {
                    field.value = '';
                }
            });
        }
    }
    
    // Player management
    function setupPlayerManagement() {
        const addBtn = document.getElementById('ajouterJoueur');
        if (addBtn) {
            addBtn.addEventListener('click', addPlayer);
        }
        
        // Update requirements when volleyball type changes
        const typeField = document.querySelector('[name="type_volleyball"]');
        if (typeField) {
            typeField.addEventListener('change', function() {
                updatePlayerRequirements();
                generateMinimumPlayers();
            });
        }
        
        // Re-validate player ages when age category changes
        const categoryField = document.querySelector('[name="volleyball_category_id"]');
        if (categoryField) {
            categoryField.addEventListener('change', function() {
                setTimeout(validateAllPlayerAges, 100);
            });
        }
        
        generateMinimumPlayers();
    }
    
    function addPlayer() {
        const container = document.getElementById('joueursContainer');
        if (!container) return;
        
        // Clear existing errors
        container.querySelectorAll('.error-message').forEach(error => error.remove());
        
        const volleyballType = getSelectedVolleyballType();
        const limits = playerLimits[volleyballType];
        const currentPlayers = container.querySelectorAll('.joueur-form').length;
        
        if (limits && currentPlayers >= limits.max) {
            showContainerError(container, `Maximum ${limits.max} joueurs pour le ${volleyballType}`);
            return;
        }
        
        playerIndex++;
        
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
                    <input type="text" name="joueurs[${playerIndex}][nom_complet]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date de naissance *</label>
                    <input type="date" name="joueurs[${playerIndex}][date_naissance]" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>N° CIN ou Passeport *</label>
                    <input type="text" name="joueurs[${playerIndex}][identifiant]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Taille vestimentaire *</label>
                    <select name="joueurs[${playerIndex}][taille_vestimentaire]" class="form-control" required>
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
        
        // Add real-time validation to new player fields
        const newPlayerFields = playerDiv.querySelectorAll('input, select');
        newPlayerFields.forEach(field => {
            field.addEventListener('input', function() {
                if (field.value.trim()) {
                    field.classList.add('has-content');
                } else {
                    field.classList.remove('has-content');
                }
            });
            
            field.addEventListener('focus', function() {
                field.classList.add('focused');
            });
            
            field.addEventListener('blur', function() {
                field.classList.remove('focused');
            });
        });
        
        updateAddButtonText();
        setupPlayerFormValidation(playerDiv);
    }
    
    // Set up validation status tracking for individual player forms
    function setupPlayerFormValidation(playerDiv) {
        const inputs = playerDiv.querySelectorAll('input, select');
        
        function checkPlayerFormValidation() {
            let hasErrors = false;
            let allFilled = true;
            
            inputs.forEach(input => {
                if (input.classList.contains('error')) {
                    hasErrors = true;
                }
                if (input.required && !input.value.trim()) {
                    allFilled = false;
                }
            });
            
            playerDiv.classList.remove('error', 'valid');
            if (hasErrors) {
                playerDiv.classList.add('error');
            } else if (allFilled) {
                playerDiv.classList.add('valid');
            }
        }
        
        inputs.forEach(input => {
            input.addEventListener('input', checkPlayerFormValidation);
            input.addEventListener('change', checkPlayerFormValidation);
            input.addEventListener('blur', checkPlayerFormValidation);
        });
    }
    
    // Generate minimum required player forms
    function generateMinimumPlayers() {
        const container = document.getElementById('joueursContainer');
        if (!container) {
            return;
        }
        
        const volleyballType = getSelectedVolleyballType();
        const limits = playerLimits[volleyballType];
        const currentPlayerCount = container.querySelectorAll('.joueur-form').length;
        
        if (!limits) {
            return;
        }
        
        // Add players up to minimum required
        const playersToAdd = limits.min - currentPlayerCount;
        if (playersToAdd > 0) {
            for (let i = 0; i < playersToAdd; i++) {
                addPlayer();
            }
        }
        
        updateAddButtonText();
    }
    
    function updateAddButtonText() {
        const addBtn = document.getElementById('ajouterJoueur');
        const container = document.getElementById('joueursContainer');
        
        if (!addBtn || !container) return;
        
        const volleyballType = getSelectedVolleyballType();
        const limits = playerLimits[volleyballType];
        const currentCount = container.querySelectorAll('.joueur-form').length;
        
        if (limits) {
            const remaining = limits.max - currentCount;
            if (remaining > 0) {
                addBtn.textContent = `Ajouter un joueur (${remaining} restants)`;
                addBtn.disabled = false;
            } else {
                addBtn.textContent = `Maximum atteint (${limits.max} joueurs)`;
                addBtn.disabled = true;
            }
        }
    }
    
    function updatePlayerRequirements() {
        const requirementsElement = document.getElementById('nombreJoueursRequis');
        
        if (!playerLimitsLoaded) {
            if (requirementsElement) {
                requirementsElement.innerHTML = `
                    <strong>⚠️ Chargement des données...</strong><br>
                    Les informations sur les équipes sont en cours de chargement.
                `;
            }
            return;
        }
        
        const volleyballType = getSelectedVolleyballType();
        if (!volleyballType) {
            if (requirementsElement) {
                requirementsElement.innerHTML = `
                    <strong>⚠️ Sélectionnez un type de volleyball</strong><br>
                    Veuillez choisir le type d'équipe pour voir les exigences.
                `;
            }
            return;
        }
        
        const limits = playerLimits[volleyballType];
        const selectedCategory = getSelectedAgeCategory();
        
        if (limits && requirementsElement) {
            let categoryInfo = '';
            if (selectedCategory && categoriesLoaded) {
                if (selectedCategory.minDate && selectedCategory.maxDate) {
                    const startDate = selectedCategory.minDate.toLocaleDateString('fr-FR');
                    const endDate = selectedCategory.maxDate.toLocaleDateString('fr-FR');
                    categoryInfo = `
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background: #e3f2fd; border-radius: 4px; font-size: 0.9em;">
                            <strong>📅 Catégorie sélectionnée: ${selectedCategory.name}</strong><br>
                            Années de naissance acceptées: ${startDate} - ${endDate}
                        </div>
                    `;
                } else if (selectedCategory.minBirthYear && selectedCategory.maxBirthYear) {
                    categoryInfo = `
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background: #e3f2fd; border-radius: 4px; font-size: 0.9em;">
                            <strong>📅 Catégorie sélectionnée: ${selectedCategory.name}</strong><br>
                            Années de naissance acceptées: ${selectedCategory.minBirthYear} - ${selectedCategory.maxBirthYear}
                        </div>
                    `;
                } else if (selectedCategory.minAge !== null && selectedCategory.maxAge !== null) {
                    categoryInfo = `
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background: #e3f2fd; border-radius: 4px; font-size: 0.9em;">
                            <strong>📅 Catégorie sélectionnée: ${selectedCategory.name}</strong><br>
                            Âges acceptés: ${selectedCategory.minAge} - ${selectedCategory.maxAge} ans
                        </div>
                    `;
                }
            } else if (!categoriesLoaded) {
                categoryInfo = `
                    <div style="margin-top: 0.5rem; padding: 0.5rem; background: #fff3cd; border-radius: 4px; font-size: 0.9em;">
                        ⏳ Chargement des catégories d'âge...
                    </div>
                `;
            }
            
            requirementsElement.innerHTML = `
                <strong>Composition de l'équipe ${volleyballType}</strong><br>
                Minimum: ${limits.min} joueurs<br>
                Maximum: ${limits.max} joueurs
                ${categoryInfo}
            `;
        }
        
        updateAddButtonText();
    }
    
    // Real-time validation
    function setupRealTimeValidation() {
        document.addEventListener('input', function(e) {
            const field = e.target;
            
            if (field.matches('input[required], select[required], textarea[required]')) {
                clearFieldError(field);
                
                const value = field.value.trim();
                
                if (value) {
                    let isValid = true;
                    
                    // Date validation for player birth dates
                    if (field.type === 'date' && field.name.includes('date_naissance')) {
                        if (!isValidBirthDate(value, field)) {
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
                                    if (category.minDate && category.maxDate) {
                                        const startDate = category.minDate.toLocaleDateString('fr-FR');
                                        const endDate = category.maxDate.toLocaleDateString('fr-FR');
                                        errorMessage = `Date de naissance invalide pour ${category.name}. Période acceptée: du ${startDate} au ${endDate} (joueur: ${new Date(value).toLocaleDateString('fr-FR')})`;
                                    } else if (category.minBirthYear && category.maxBirthYear) {
                                        const birthYear = new Date(value).getFullYear();
                                        errorMessage = `Année de naissance invalide pour ${category.name}: requis ${category.minBirthYear}-${category.maxBirthYear} (joueur: ${birthYear})`;
                                    } else if (category.minAge !== undefined && category.maxAge !== undefined) {
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
                            isValid = false;
                        }
                    }
                    
                    // Phone validation
                    else if (field.type === 'tel') {
                        if (field.name.includes('whatsapp')) {
                            if (!isValidWhatsApp(value)) {
                                showFieldError(field, 'Format WhatsApp invalide (ex: +212612345678 ou 0612345678)');
                                isValid = false;
                            }
                        } else {
                            if (!isValidPhone(value)) {
                                showFieldError(field, 'Format de téléphone invalide (ex: 0612345678)');
                                isValid = false;
                            }
                        }
                    }
                    
                    // Email validation
                    else if (field.type === 'email') {
                        if (!isValidEmail(value)) {
                            showFieldError(field, 'Format d\'email invalide');
                            isValid = false;
                        }
                    }
                    
                    // Name validation for player names
                    else if (field.name.includes('nom_complet')) {
                        if (!isValidName(value)) {
                            showFieldError(field, 'Le nom ne peut contenir que des lettres et espaces');
                            isValid = false;
                        }
                    }
                    
                    if (isValid) {
                        field.classList.add('valid', 'form-control');
                        field.classList.remove('error');
                    }
                } else {
                    field.classList.remove('valid', 'error');
                }
            }
            
            // Handle optional WhatsApp fields
            else if (field.name && field.name.includes('whatsapp')) {
                clearFieldError(field);
                
                const value = field.value.trim();
                if (!value) {
                    field.classList.remove('error', 'valid');
                } else {
                    if (field.type === 'tel' && !isValidWhatsApp(value)) {
                        showFieldError(field, 'Format WhatsApp invalide (ex: +212612345678 ou 0612345678)');
                    } else {
                        field.classList.add('valid', 'form-control');
                        field.classList.remove('error');
                    }
                }
            }
            
            // Handle other optional fields that might need format validation
            else if (field.value.trim()) {
                clearFieldError(field);
                
                const value = field.value.trim();
                let isValid = true;
                
                if (field.type === 'email' && !isValidEmail(value)) {
                    showFieldError(field, 'Format d\'email invalide');
                    isValid = false;
                } else if (field.name.includes('nom_complet') && !isValidName(value)) {
                    showFieldError(field, 'Le nom ne peut contenir que des lettres et espaces');
                    isValid = false;
                }
                
                if (isValid) {
                    field.classList.add('valid', 'form-control');
                    field.classList.remove('error');
                }
            }
        });
        
        // Also handle select field changes
        document.addEventListener('change', function(e) {
            const field = e.target;
            
            if (field.matches('select[required]')) {
                clearFieldError(field);
                
                if (field.value && field.value !== '') {
                    field.classList.add('valid', 'form-control');
                    field.classList.remove('error');
                } else {
                    field.classList.remove('valid', 'error');
                }
            }
        });
    }
    
    // Global error display function
    function showGlobalError(message) {
        document.querySelectorAll('.global-error').forEach(error => error.remove());
        
        const errorElement = document.createElement('div');
        errorElement.className = 'global-error alert alert-danger';
        errorElement.style.cssText = `
            margin: 1rem 0;
            padding: 1rem;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 0.375rem;
            font-weight: 600;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
            max-width: 90%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        errorElement.innerHTML = `
            <strong>⚠️ Erreur de chargement</strong><br>
            ${message}
            <button type="button" onclick="this.parentElement.remove()" style="float: right; background: none; border: none; font-size: 1.2em; color: #721c24; cursor: pointer;">×</button>
        `;
        
        document.body.insertBefore(errorElement, document.body.firstChild);
        
        setTimeout(() => {
            if (errorElement.parentNode) {
                errorElement.remove();
            }
        }, 10000);
    }
    
    // Function to disable form submission
    function disableFormSubmission(reason) {
        const form = document.getElementById('inscriptionForm');
        const submitBtn = document.querySelector('button[type="submit"]');
        const nextBtn = document.getElementById('nextBtn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showGlobalError(reason);
                return false;
            });
        }
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.title = reason;
            submitBtn.style.opacity = '0.6';
            submitBtn.style.cursor = 'not-allowed';
        }
        
        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.title = reason;
            nextBtn.style.opacity = '0.6';
            nextBtn.style.cursor = 'not-allowed';
        }
    }
    
    // Initialize wizard
    initializeWizard();
    
    // Load dynamic data
    loadAgeCategories();
    loadVolleyballTypes();
    
    // Global remove player function
    window.removePlayer = function(button) {
        const container = document.getElementById('joueursContainer');
        const playerDiv = button.closest('.joueur-form');
        
        if (!container || !playerDiv) return;
        
        const volleyballType = getSelectedVolleyballType();
        const limits = playerLimits[volleyballType];
        const currentCount = container.querySelectorAll('.joueur-form').length;
        
        // Prevent removing below minimum required
        if (limits && currentCount <= limits.min) {
            const message = document.createElement('div');
            message.className = 'alert alert-warning';
            message.style.marginTop = '10px';
            message.textContent = `Impossible de supprimer: minimum ${limits.min} joueurs requis pour le ${volleyballType}`;
            
            container.appendChild(message);
            setTimeout(() => message.remove(), 3000);
            
            return;
        }
        
        playerDiv.remove();
        updateAddButtonText();
    };
    
    console.log('Volleyball wizard validation setup complete');
});