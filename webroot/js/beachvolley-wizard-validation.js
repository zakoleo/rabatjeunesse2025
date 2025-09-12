/**
 * Beach Volleyball Team Registration Wizard Validation
 * Clean, focused validation for beach volleyball team registration form
 */

document.addEventListener('DOMContentLoaded', function() {
    // Wizard state
    let currentStep = 1;
    const maxSteps = 3;
    let playerIndex = 0;
    
    // Beach Volleyball-specific player limits (will be populated from server)
    let playerLimits = {};
    let playerLimitsLoaded = false;
    
    console.log('Beach volleyball wizard validation initialized');
    
    // Age categories data (will be populated from server)
    let beachvolleyCategories = {};
    let categoriesLoaded = false;
    
    // Initialize wizard
    initializeWizard();
    
    // Load dynamic data
    loadAgeCategories();
    loadBeachVolleyTypes();
    
    // Function to load age categories from server
    function loadAgeCategories() {
        const categorySelect = document.querySelector('[name="football_category_id"]');
        if (categorySelect) {
            // Load categories directly from database API
            fetchDetailedCategoryData();
            
            // Set up category change listener
            categorySelect.addEventListener('change', function() {
                console.log('Category changed, will validate player ages and update display');
                // Filter beachvolley types based on selected category
                filterBeachVolleyTypesByCategory();
                // Update the player requirements display to show new category
                updatePlayerRequirements();
                // Validate all existing player ages against new category
                setTimeout(validateAllPlayerAges, 100);
            });
        }
    }
    
    // Fetch detailed category data from server - uses only database data
    function fetchDetailedCategoryData() {
        // Try beachvolley-specific endpoint first, fallback to generic
        const categoriesUrl = (window.API_URLS && window.API_URLS.getBeachvolleyCategories) 
            ? window.API_URLS.getBeachvolleyCategories
            : (window.APP_BASE_URL || '') + 'teams/getBeachvolleyCategories';
        
        console.log('🔍 Fetching beachvolley categories from URL:', categoriesUrl);
        console.log('🔍 Current categoriesLoaded state:', categoriesLoaded);
        console.log('🔍 Current beachvolleyCategories:', beachvolleyCategories);
        
        fetch(categoriesUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received category data from database:', data);
                
                if (data.categories && Array.isArray(data.categories)) {
                    // Clear existing categories and populate only from database
                    beachvolleyCategories = {};
                    
                    data.categories.forEach(category => {
                        // Parse dates from API response - try multiple field names
                        let minDate = null;
                        let maxDate = null;
                        
                        // Try min_date/max_date first
                        if (category.min_date && category.max_date) {
                            minDate = parseLocalDate(category.min_date);
                            maxDate = parseLocalDate(category.max_date);
                        }
                        // Fallback to min_birth_date/max_birth_date  
                        else if (category.min_birth_date && category.max_birth_date) {
                            minDate = parseLocalDate(category.min_birth_date);
                            maxDate = parseLocalDate(category.max_birth_date);
                        }
                        // Last resort: construct dates from birth years
                        else if (category.min_birth_year && category.max_birth_year) {
                            minDate = new Date(category.min_birth_year, 0, 1); // January 1st
                            maxDate = new Date(category.max_birth_year, 11, 31); // December 31st
                        }
                        
                        beachvolleyCategories[category.id] = {
                            id: category.id,
                            name: category.name,
                            minAge: category.min_age || null,
                            maxAge: category.max_age || null,
                            minBirthYear: category.min_birth_year,
                            maxBirthYear: category.max_birth_year,
                            minDate: minDate,
                            maxDate: maxDate,
                            allowedTypes: category.allowed_beachvolley_types || [],
                            format: 'database'
                        };
                        
                        console.log(`📅 Processed category ${category.name}:`, {
                            raw_min_date: category.min_date,
                            raw_max_date: category.max_date,
                            raw_min_birth_date: category.min_birth_date,
                            raw_max_birth_date: category.max_birth_date,
                            parsed_minDate: minDate,
                            parsed_maxDate: maxDate,
                            minBirthYear: category.min_birth_year,
                            maxBirthYear: category.max_birth_year
                        });
                    });
                    
                    console.log('Beach volleyball categories loaded from database:', beachvolleyCategories);
                    categoriesLoaded = true;
                } else {
                    console.error('Invalid category data format:', data);
                    throw new Error('Invalid data format received');
                }
            })
            .catch(error => {
                console.error('Failed to load category data:', error);
                categoriesLoaded = false;
                
                // Show error message to user
                showGlobalError('Erreur de connexion: Impossible de charger les catégories d\'âge. Veuillez rafraîchir la page ou contacter l\'administrateur.');
                
                // Disable form submission until data is loaded
                disableFormSubmission('Les données des catégories ne peuvent pas être chargées. Veuillez rafraîchir la page.');
            });
    }

    // Load beachvolley types from server
    function loadBeachVolleyTypes() {
        const beachvolleyTypesUrl = (window.API_URLS && window.API_URLS.getBeachvolleyTypes) 
            ? window.API_URLS.getBeachvolleyTypes
            : (window.APP_BASE_URL || '') + 'teams/getBeachvolleyTypes';
        
        console.log('Fetching beachvolley types from URL:', beachvolleyTypesUrl);
        fetch(beachvolleyTypesUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received beachvolley types data:', data);
                
                if (data.beachvolley_types && Array.isArray(data.beachvolley_types)) {
                    // Clear any existing limits and populate from database
                    playerLimits = {};
                    data.beachvolley_types.forEach(type => {
                        playerLimits[type.code] = {
                            min: type.min_players,
                            max: type.max_players,
                            name: type.name,
                            id: type.id
                        };
                    });
                    
                    console.log('Updated player limits from database:', playerLimits);
                    playerLimitsLoaded = true;
                    
                    // Update UI elements that depend on player limits
                    updatePlayerRequirements();
                    if (currentStep === 3) {
                        generateMinimumPlayers();
                    }
                } else {
                    console.error('Invalid beachvolley types data format:', data);
                    throw new Error('Invalid data format received');
                }
            })
            .catch(error => {
                console.error('Failed to load beachvolley types data:', error);
                playerLimitsLoaded = false;
                
                // Show error message to user
                showGlobalError('Erreur de connexion: Impossible de charger les types de beach volleyball. Veuillez rafraîchir la page ou contacter l\'administrateur.');
                
                // Disable form submission until data is loaded
                disableFormSubmission('Les données du formulaire ne peuvent pas être chargées. Veuillez rafraîchir la page.');
            });
    }
    
    
    // Filter beachvolley types dropdown based on selected category
    function filterBeachVolleyTypesByCategory() {
        const categorySelect = document.querySelector('[name="football_category_id"]');
        const typeSelect = document.querySelector('[name="type_beachvolley"]');
        
        if (!categorySelect || !typeSelect || !categorySelect.value) {
            console.log('Category select, type select, or category value not found');
            return;
        }
        
        const selectedCategory = beachvolleyCategories[categorySelect.value];
        if (!selectedCategory) {
            console.error('Selected category not found in beachvolleyCategories:', categorySelect.value);
            return;
        }
        
        console.log('🎯 Filtering beachvolley types for category:', selectedCategory.name);
        console.log('   Allowed types:', selectedCategory.allowedTypes);
        
        // Store currently selected value
        const currentValue = typeSelect.value;
        
        // Clear all options except the placeholder
        const placeholder = typeSelect.querySelector('option[value=""]');
        typeSelect.innerHTML = '';
        if (placeholder) {
            typeSelect.appendChild(placeholder);
        } else {
            // Add default placeholder if it doesn't exist
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionner un type de beach volleyball';
            typeSelect.appendChild(defaultOption);
        }
        
        // Add allowed types for this category
        if (selectedCategory.allowedTypes && selectedCategory.allowedTypes.length > 0) {
            selectedCategory.allowedTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.code || type.id;
                option.textContent = `${type.name} (${type.min_players}-${type.max_players} joueurs)`;
                option.dataset.typeId = type.id;
                option.dataset.minPlayers = type.min_players;
                option.dataset.maxPlayers = type.max_players;
                typeSelect.appendChild(option);
                
                console.log(`   Added type option: ${type.name} (${type.code})`);
            });
            
            // Restore previous selection if it's still valid
            if (currentValue && typeSelect.querySelector(`option[value="${currentValue}"]`)) {
                typeSelect.value = currentValue;
                console.log('✅ Restored previous selection:', currentValue);
            } else if (selectedCategory.allowedTypes.length === 1) {
                // Auto-select if only one option is available
                typeSelect.value = selectedCategory.allowedTypes[0].code || selectedCategory.allowedTypes[0].id;
                console.log('✅ Auto-selected only available option:', typeSelect.value);
            }
        } else {
            console.warn('❌ No allowed types found for category:', selectedCategory.name);
            
            // Add a message option
            const messageOption = document.createElement('option');
            messageOption.value = '';
            messageOption.textContent = 'Aucun type disponible pour cette catégorie';
            messageOption.disabled = true;
            typeSelect.appendChild(messageOption);
        }
        
        // Trigger change event to update player requirements
        typeSelect.dispatchEvent(new Event('change'));
    }
    
    // Get selected age category
    function getSelectedAgeCategory() {
        const categorySelect = document.querySelector('[name="football_category_id"]');
        
        console.log('🔍 getSelectedAgeCategory() debugging:');
        console.log('  categorySelect found:', !!categorySelect);
        console.log('  categorySelect.value:', categorySelect?.value);
        console.log('  categoriesLoaded:', categoriesLoaded);
        console.log('  beachvolleyCategories keys:', Object.keys(beachvolleyCategories));
        console.log('  beachvolleyCategories object:', beachvolleyCategories);
        
        if (categorySelect && categorySelect.value) {
            const selectedId = categorySelect.value;
            const category = beachvolleyCategories[selectedId];
            
            console.log(`  Looking for category ID: ${selectedId}`);
            console.log(`  Found category:`, category);
            
            if (!category) {
                console.error(`❌ Category ID ${selectedId} not found in beachvolleyCategories object!`);
                console.error(`Available category IDs:`, Object.keys(beachvolleyCategories));
            }
            
            return category;
        }
        
        console.warn('❌ No category selected or categorySelect not found');
        return null;
    }
    
    // Validate all player ages against selected category
    function validateAllPlayerAges() {
        const selectedCategory = getSelectedAgeCategory();
        if (!selectedCategory) {
            console.log('No age category selected, skipping age validation');
            return;
        }
        
        console.log('Validating player ages against category:', selectedCategory.name);
        
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
            return true; // No category selected, can't validate
        }
        
        const birthDate = new Date(birthDateField.value);
        const today = new Date();
        
        // Calculate age more accurately
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        console.log(`Player age validation: ${age} years, Category: ${category.name} (${category.minAge}-${category.maxAge})`);
        
        const isValidAge = age >= category.minAge && age <= category.maxAge;
        
        // Clear any existing age validation errors
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
            // Mark as valid for age
            birthDateField.classList.add('valid', 'form-control');
            birthDateField.classList.remove('error');
            return true;
        }
    }
    
    // Add validation CSS styles
    addValidationStyles();
    
    // Disable HTML validation for WhatsApp fields
    disableHtmlValidationForWhatsApp();
    
    // Function to disable HTML validation for WhatsApp fields
    function disableHtmlValidationForWhatsApp() {
        // Find all WhatsApp fields
        const whatsappFields = document.querySelectorAll('input[name*="whatsapp"]');
        
        whatsappFields.forEach(field => {
            console.log('Disabling HTML validation for WhatsApp field:', field.name);
            console.log('Original attributes:', {
                pattern: field.getAttribute('pattern'),
                title: field.getAttribute('title'),
                type: field.type
            });
            
            // Remove HTML5 validation attributes that might be set by CakePHP
            field.removeAttribute('pattern');
            field.removeAttribute('title');
            field.removeAttribute('minlength');
            field.removeAttribute('maxlength');
            
            // Change type from tel to text to avoid browser validation
            if (field.type === 'tel') {
                field.type = 'text';
                console.log('Changed field type from tel to text for:', field.name);
            }
            
            // Remove any existing validation constraints
            field.setCustomValidity('');
            
            // Override HTML5 validation methods
            field.addEventListener('invalid', function(e) {
                e.preventDefault();
                console.log('HTML validation prevented for:', field.name);
                return false;
            });
            
            // Clear browser validation on input
            field.addEventListener('input', function(e) {
                field.setCustomValidity('');
            });
            
            // Prevent form validation on this field
            field.addEventListener('beforevalidate', function(e) {
                e.preventDefault();
                return false;
            });
        });
        
        console.log(`Disabled HTML validation for ${whatsappFields.length} WhatsApp fields`);
        
        // Also disable form-level HTML validation for the entire form
        const form = document.getElementById('inscriptionForm');
        if (form) {
            form.setAttribute('novalidate', 'true');
            console.log('Disabled HTML validation for entire form');
        }
    }
    
    // Add CSS styles for validation feedback - matching existing form-validation.css
    function addValidationStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Player form specific styles matching existing validation */
            .joueur-form {
                border: 1px solid #e0e0e0;
                border-radius: 0.375rem;
                padding: 1rem;
                margin-bottom: 1rem;
                background-color: #fff;
                transition: all 0.2s ease;
            }
            
            .joueur-form:hover {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .joueur-form.error {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.1rem rgba(220, 53, 69, 0.25);
            }
            
            .joueur-form.valid {
                border-color: #28a745;
                box-shadow: 0 0 0 0.1rem rgba(40, 167, 69, 0.25);
            }
            
            .joueur-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e0e0e0;
                background-color: #f8f9fa;
                margin: -1rem -1rem 1rem -1rem;
                padding: 0.75rem 1rem;
            }
            
            .joueur-header h4 {
                margin: 0;
                color: #333;
                font-weight: 600;
            }
            
            .btn-remove-joueur {
                background: #dc3545;
                color: white;
                border: none;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                font-size: 18px;
                line-height: 1;
                cursor: pointer;
                transition: background-color 0.2s ease;
                font-weight: bold;
            }
            
            .btn-remove-joueur:hover {
                background: #c82333;
                transform: scale(1.05);
            }
            
            .btn-remove-joueur:disabled {
                background: #6c757d;
                cursor: not-allowed;
                opacity: 0.6;
            }
            
            /* Make sure player form inputs use form-control class styling */
            .joueur-form input,
            .joueur-form select {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #ced4da;
                border-radius: 0.375rem;
                background-color: #fff;
                font-size: 1rem;
                transition: all 0.15s ease-in-out;
            }
            
            .joueur-form input:focus,
            .joueur-form select:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
                outline: none;
            }
            
            .joueur-form .form-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -0.5rem;
            }
            
            .joueur-form .form-group {
                flex: 1;
                min-width: 0;
                padding: 0 0.5rem;
                margin-bottom: 1rem;
            }
            
            .joueur-form .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: #333;
                font-size: 0.9rem;
            }
        `;
        document.head.appendChild(style);
        console.log('Player form validation styles added');
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
                console.log('Next button clicked - current step before validation:', currentStep);
                
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
                console.log('Form submitted - validating all steps');
                if (!validateAllSteps()) {
                    e.preventDefault();
                    console.log('Form submission blocked - validation failed');
                } else {
                    console.log('All validation passed - form will submit normally');
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
        
        // Generate minimum players when reaching step 3
        if (currentStep === 3) {
            setTimeout(() => {
                generateMinimumPlayers();
                console.log('Generated minimum players for step 3');
            }, 100); // Small delay to ensure DOM is ready
        }
    }
    
    // Validate current step
    function validateCurrentStep() {
        const stepElement = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        if (!stepElement) {
            console.error(`Step element not found for step ${currentStep}`);
            return true;
        }
        
        console.log(`Validating step ${currentStep}`, stepElement);
        
        // Clear previous errors
        clearStepErrors(stepElement);
        
        let isValid = true;
        
        // Get all required fields in current step  
        const requiredFields = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
        
        // Also get optional fields that might need validation (like WhatsApp)
        const allFields = stepElement.querySelectorAll('input, select, textarea');
        const optionalFields = Array.from(allFields).filter(f => !f.required && (f.value.trim() || f.name.includes('whatsapp')));
        
        console.log(`Step ${currentStep} - Required fields (${requiredFields.length}):`, 
            Array.from(requiredFields).map(f => f.name || f.id));
        console.log(`Step ${currentStep} - Optional fields for validation (${optionalFields.length}):`, 
            optionalFields.map(f => `${f.name || f.id} (${f.value.trim() ? 'has value' : 'empty WhatsApp'})`));
        
        requiredFields.forEach(field => {
            // Handle file fields during wizard navigation
            if (field.type === 'file') {
                console.log('Checking file field:', field.name, 'Files:', field.files?.length || 0);
                
                // Skip coach file fields if same as manager
                if (currentStep === 2 && field.name.includes('entraineur_')) {
                    const sameAsManager = document.getElementById('sameAsResponsable');
                    if (sameAsManager && sameAsManager.checked) {
                        console.log('Skipping coach file field (same as manager):', field.name);
                        return;
                    }
                }
                
                // For wizard navigation, only validate file fields if they are required and empty
                if (field.required && (!field.files || field.files.length === 0)) {
                    console.log('File field validation FAILED - no file selected:', field.name);
                    showFieldError(field, 'Veuillez sélectionner un fichier');
                    isValid = false;
                } else {
                    console.log('File field validation PASSED:', field.name);
                }
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
                console.log('Coach field found:', field.name, 'Same as manager checked:', sameAsManager?.checked);
                if (sameAsManager && sameAsManager.checked) {
                    console.log('Skipping coach field (same as manager):', field.name);
                    return;
                }
            }
            
            // Validate field
            console.log('Validating field:', field.name, 'Value:', field.value, 'Required:', field.required);
            if (!validateField(field)) {
                console.log('Field validation FAILED:', field.name);
                isValid = false;
            } else {
                console.log('Field validation PASSED:', field.name);
            }
        });
        
        // Validate optional fields that have values (like WhatsApp)
        optionalFields.forEach(field => {
            // Skip file fields
            if (field.type === 'file') {
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
            
            // Validate format if field has value
            console.log('Validating optional field:', field.name, 'Value:', field.value);
            if (!validateFieldFormat(field)) {
                isValid = false;
            }
        });
        
        // Step-specific validation
        if (currentStep === 3) {
            if (!validatePlayerCount()) {
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
        
        // Type-specific validation (only if field has value)
        if (value) {
            switch (field.type) {
                case 'email':
                    if (!isValidEmail(value)) {
                        showFieldError(field, 'Format d\'email invalide');
                        return false;
                    }
                    break;
                case 'tel':
                    console.log('=== TEL Field Debug (Required) ===');
                    console.log('Field name:', field.name);
                    console.log('Contains whatsapp:', field.name.includes('whatsapp'));
                    console.log('Field value:', value);
                    console.log('================================');
                    
                    if (field.name.includes('whatsapp')) {
                        console.log('Processing as WhatsApp field (required):', field.name);
                        if (!isValidWhatsApp(value)) {
                            showFieldError(field, 'Format WhatsApp invalide (ex: +212612345678 ou 0612345678)');
                            return false;
                        }
                    } else {
                        console.log('Processing as regular phone field (required):', field.name);
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
        
        // Mark as valid - using existing CSS classes
        field.classList.remove('error');
        field.classList.add('valid', 'form-control');
        console.log('Field validated successfully:', field.name);
        return true;
    }
    
    // Validate field format only (for optional fields with values)
    function validateFieldFormat(field) {
        const value = field.value ? field.value.trim() : '';
        
        // WhatsApp fields are always valid when empty (not required)
        if (!value && field.name.includes('whatsapp')) {
            console.log('WhatsApp field is empty - considered valid:', field.name);
            field.classList.remove('error');
            return true;
        }
        
        if (!value) return true; // Skip other empty optional fields
        
        // Type-specific format validation
        switch (field.type) {
            case 'email':
                if (!isValidEmail(value)) {
                    showFieldError(field, 'Format d\'email invalide');
                    return false;
                }
                break;
            case 'tel':
                console.log('=== TEL Field Debug (Optional) ===');
                console.log('Field name:', field.name);
                console.log('Contains whatsapp:', field.name.includes('whatsapp'));
                console.log('Field value:', value);
                console.log('=================================');
                
                if (field.name.includes('whatsapp')) {
                    console.log('Processing as WhatsApp field (optional):', field.name);
                    if (!isValidWhatsApp(value)) {
                        showFieldError(field, 'Format WhatsApp invalide (ex: +212612345678 ou 0612345678)');
                        return false;
                    }
                } else {
                    console.log('Processing as regular phone field (optional):', field.name);
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
        
        // Mark as valid - using existing CSS classes
        field.classList.remove('error');
        field.classList.add('valid', 'form-control');
        console.log('Optional field format validated successfully:', field.name);
        return true;
    }
    
    // Validation helper functions
    
    // Parse date string to local Date object avoiding timezone issues
    function parseLocalDate(dateStr) {
        if (!dateStr) return null;
        
        console.log('🔍 Parsing date string:', dateStr);
        
        // If it's already a Date object, return it
        if (dateStr instanceof Date) {
            console.log('Already a Date object:', dateStr);
            return dateStr;
        }
        
        // Handle YYYY-MM-DD format specifically to avoid timezone issues
        if (typeof dateStr === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
            const [year, month, day] = dateStr.split('-').map(Number);
            const localDate = new Date(year, month - 1, day); // month is 0-indexed
            console.log(`Parsed ${dateStr} as local date:`, localDate, 'ISO:', localDate.toISOString());
            return localDate;
        }
        
        // Handle other date formats or datetime strings
        const parsedDate = new Date(dateStr);
        console.log(`Parsed ${dateStr} with new Date():`, parsedDate, 'ISO:', parsedDate.toISOString());
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
        console.log('=== WhatsApp Validation Debug ===');
        console.log('Original:', whatsapp);
        console.log('Cleaned:', cleanWhatsApp);
        console.log('Length:', cleanWhatsApp.length);
        
        // WhatsApp format validation - more flexible than regular phone
        // Accepts: +212612345678, 0612345678, 212612345678, or international formats
        const patterns = [
            { regex: /^\+212[5-7][0-9]{8}$/, desc: '+212612345678 (Morocco with +)' },
            { regex: /^212[5-7][0-9]{8}$/, desc: '212612345678 (Morocco without +)' },
            { regex: /^0[5-7][0-9]{8}$/, desc: '0612345678 (Morocco local)' },
            { regex: /^[5-7][0-9]{8}$/, desc: '612345678 (Morocco minimal)' },
            { regex: /^\+[1-9][0-9]{8,14}$/, desc: 'International (+country + 9-15 digits)' },
            { regex: /^[0-9]{10,15}$/, desc: 'Any 10-15 digit number' }
        ];
        
        let isValid = false;
        let matchedPattern = '';
        
        for (const pattern of patterns) {
            if (pattern.regex.test(cleanWhatsApp)) {
                isValid = true;
                matchedPattern = pattern.desc;
                console.log('✓ Matched pattern:', pattern.desc);
                break;
            } else {
                console.log('✗ Failed pattern:', pattern.desc);
            }
        }
        
        console.log('Final WhatsApp validation result:', isValid);
        console.log('================================');
        
        return isValid;
    }
    
    function isValidName(name) {
        return /^[a-zA-ZÀ-ÿ\s]+$/.test(name);
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
        
        console.log('Birth date validation:', dateStr, 'Age:', age, 'Birth date:', birthDate);
        
        // For player fields, validate against selected age category
        if (field && field.name && field.name.includes('joueurs[')) {
            console.log('🔍 Player birth date validation started');
            
            if (!categoriesLoaded) {
                console.warn('❌ Categories not loaded yet - cannot validate');
                return age >= 6 && age <= 35; // Basic fallback
            }
            
            const category = getSelectedAgeCategory();
            if (category) {
                console.log(`✅ Using category: ${category.name}`, category);
                
                // Use database date range validation if available
                if (category.minDate && category.maxDate) {
                    console.log(`🔍 DEBUGGING DATE VALIDATION for ${dateStr}:`);
                    console.log(`  Category: ${category.name}`);
                    console.log(`  Expected range: ${category.minDate.toLocaleDateString('fr-FR')} to ${category.maxDate.toLocaleDateString('fr-FR')}`);
                    console.log(`  Input date: ${dateStr} -> parsed as: ${birthDate.toLocaleDateString('fr-FR')}`);
                    
                    console.log(`  Detailed comparison:`);
                    console.log(`    Min date: ${category.minDate.toLocaleDateString('fr-FR')} (${category.minDate.getTime()})`);
                    console.log(`    Birth date: ${birthDate.toLocaleDateString('fr-FR')} (${birthDate.getTime()})`);
                    console.log(`    Max date: ${category.maxDate.toLocaleDateString('fr-FR')} (${category.maxDate.getTime()})`);
                    
                    const isAfterMin = birthDate >= category.minDate;
                    const isBeforeMax = birthDate <= category.maxDate;
                    const isValidDate = isAfterMin && isBeforeMax;
                    
                    console.log(`  Results:`);
                    console.log(`    ✅ Birth date >= Min date: ${isAfterMin}`);
                    console.log(`    ✅ Birth date <= Max date: ${isBeforeMax}`);
                    console.log(`    🎯 Final result: ${isValidDate ? 'VALID' : 'INVALID'}`);
                    
                    if (!isValidDate) {
                        console.log(`  ❌ VALIDATION FAILED:`);
                        console.log(`     Input: ${birthDate.toLocaleDateString('fr-FR')}`);
                        console.log(`     Expected: Between ${category.minDate.toLocaleDateString('fr-FR')} and ${category.maxDate.toLocaleDateString('fr-FR')}`);
                    }
                    
                    return isValidDate;
                } else if (category.minAge !== null && category.maxAge !== null) {
                    // Fallback to age range validation
                    console.log(`Using age range validation: ${category.minAge}-${category.maxAge} years`);
                    return age >= category.minAge && age <= category.maxAge;
                } else {
                    console.log('No validation criteria available for category');
                    return age >= 6 && age <= 35; // General fallback
                }
            } else {
                // No category selected - allow general beachvolley age range
                console.log(`Player age validation without category - general range: 6-35 years`);
                return age >= 6 && age <= 35;
            }
        }
        
        // For non-player fields (manager/coach), use general age range
        return age >= 16 && age <= 80;
    }
    
    // Player count validation
    function validatePlayerCount() {
        // Check if player limits data is loaded
        if (!playerLimitsLoaded) {
            console.error('Player limits not loaded - cannot validate player count');
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, 'Erreur: Les données de validation ne sont pas chargées. Veuillez rafraîchir la page.');
            }
            return false;
        }
        
        const playersCount = document.querySelectorAll('.joueur-form').length;
        const beachvolleyType = getSelectedBeachVolleyType();
        const limits = playerLimits[beachvolleyType];
        
        if (!limits) {
            console.error('No limits found for beachvolley type:', beachvolleyType, 'Available:', Object.keys(playerLimits));
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, `Erreur: Type de beach volleyball "${beachvolleyType}" non reconnu. Veuillez sélectionner un autre type.`);
            }
            return false;
        }
        
        console.log(`Players: ${playersCount}, Required: ${limits.min}-${limits.max} for ${beachvolleyType}`);
        
        if (playersCount < limits.min) {
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, `Vous devez ajouter au moins ${limits.min} joueurs pour le ${beachvolleyType}`);
            }
            return false;
        }
        
        return true;
    }
    
    // Get selected beachvolley type
    function getSelectedBeachVolleyType() {
        const typeField = document.querySelector('[name="type_beachvolley"]');
        const selectedValue = typeField ? typeField.value : null;
        
        // If we have a selected value, return it
        if (selectedValue) {
            return selectedValue;
        }
        
        // If no value is selected, return null - do not use fallbacks
        console.warn('No beachvolley type selected');
        return null;
    }
    
    // Error display functions
    function showFieldError(field, message) {
        // Clear existing errors
        clearFieldError(field);
        
        // Add error class - using existing CSS classes
        field.classList.add('error', 'form-control');
        field.classList.remove('valid');
        
        // Create error message using existing CSS class
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        
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
        console.log('setupCoachToggle - checkbox found:', !!checkbox, 'checked:', checkbox?.checked);
        if (checkbox) {
            checkbox.addEventListener('change', toggleCoachFields);
            // Initialize on load
            toggleCoachFields();
        } else {
            console.warn('sameAsResponsable checkbox not found');
        }
    }
    
    function toggleCoachFields() {
        const checkbox = document.getElementById('sameAsResponsable');
        const coachFields = document.getElementById('entraineurFields');
        
        console.log('toggleCoachFields called - checkbox checked:', checkbox?.checked);
        
        if (!checkbox || !coachFields) {
            console.log('Missing elements:', { checkbox: !!checkbox, coachFields: !!coachFields });
            return;
        }
        
        const coachInputs = coachFields.querySelectorAll('input, select');
        console.log('Found', coachInputs.length, 'coach inputs');
        
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
        
        // Update requirements when beachvolley type changes
        const typeField = document.querySelector('[name="type_beachvolley"]');
        if (typeField) {
            typeField.addEventListener('change', function() {
                updatePlayerRequirements();
                generateMinimumPlayers();
            });
        }
        
        // Re-validate player ages when age category changes
        const categoryField = document.querySelector('[name="beachvolley_category_id"]');
        if (categoryField) {
            categoryField.addEventListener('change', function() {
                console.log('Age category changed, re-validating all player ages');
                setTimeout(validateAllPlayerAges, 100);
            });
        }
        
        // Generate minimum players on wizard initialization
        generateMinimumPlayers();
    }
    
    function addPlayer() {
        const container = document.getElementById('joueursContainer');
        if (!container) return;
        
        // Clear existing errors
        container.querySelectorAll('.error-message').forEach(error => error.remove());
        
        const beachvolleyType = getSelectedBeachVolleyType();
        const limits = playerLimits[beachvolleyType];
        const currentPlayers = container.querySelectorAll('.joueur-form').length;
        
        if (limits && currentPlayers >= limits.max) {
            showContainerError(container, `Maximum ${limits.max} joueurs pour le ${beachvolleyType}`);
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
        console.log('Player added:', playerIndex);
        
        // Add real-time validation to new player fields
        const newPlayerFields = playerDiv.querySelectorAll('input, select');
        newPlayerFields.forEach(field => {
            console.log('Setting up validation for new player field:', field.name);
            
            // Add visual feedback classes
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
        
        // Update add button text after adding player
        updateAddButtonText();
        
        // Set up player form validation status tracking
        setupPlayerFormValidation(playerDiv);
    }
    
    // Global remove player function
    window.removePlayer = function(button) {
        const container = document.getElementById('joueursContainer');
        const playerDiv = button.closest('.joueur-form');
        
        if (!container || !playerDiv) return;
        
        const beachvolleyType = getSelectedBeachVolleyType();
        const limits = playerLimits[beachvolleyType];
        const currentCount = container.querySelectorAll('.joueur-form').length;
        
        // Prevent removing below minimum required
        if (limits && currentCount <= limits.min) {
            console.log(`Cannot remove player: minimum ${limits.min} required for ${beachvolleyType}`);
            
            // Show temporary message
            const message = document.createElement('div');
            message.className = 'alert alert-warning';
            message.style.marginTop = '10px';
            message.textContent = `Impossible de supprimer: minimum ${limits.min} joueurs requis pour le ${beachvolleyType}`;
            
            container.appendChild(message);
            setTimeout(() => message.remove(), 3000);
            
            return;
        }
        
        playerDiv.remove();
        console.log('Player removed');
        
        // Update add button text after removal
        updateAddButtonText();
    };
    
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
            
            // Update player form visual state
            playerDiv.classList.remove('error', 'valid');
            if (hasErrors) {
                playerDiv.classList.add('error');
                console.log('Player form marked as error');
            } else if (allFilled) {
                playerDiv.classList.add('valid');
                console.log('Player form marked as valid');
            }
        }
        
        // Monitor changes to player form inputs
        inputs.forEach(input => {
            input.addEventListener('input', checkPlayerFormValidation);
            input.addEventListener('change', checkPlayerFormValidation);
            input.addEventListener('blur', checkPlayerFormValidation);
        });
        
        console.log('Player form validation tracking set up');
    }
    
    // Generate minimum required player forms
    function generateMinimumPlayers() {
        const container = document.getElementById('joueursContainer');
        if (!container) {
            console.log('Player container not found');
            return;
        }
        
        const beachvolleyType = getSelectedBeachVolleyType();
        const limits = playerLimits[beachvolleyType];
        const currentPlayerCount = container.querySelectorAll('.joueur-form').length;
        
        if (!limits) {
            console.log('No limits found for beachvolley type:', beachvolleyType);
            return;
        }
        
        console.log(`Generating minimum players for ${beachvolleyType}: need ${limits.min}, have ${currentPlayerCount}`);
        
        // Add players up to minimum required
        const playersToAdd = limits.min - currentPlayerCount;
        if (playersToAdd > 0) {
            console.log(`Adding ${playersToAdd} minimum required players`);
            for (let i = 0; i < playersToAdd; i++) {
                addPlayer();
            }
        }
        
        // Update the add button text to show remaining slots
        updateAddButtonText();
    }
    
    function updateAddButtonText() {
        const addBtn = document.getElementById('ajouterJoueur');
        const container = document.getElementById('joueursContainer');
        
        if (!addBtn || !container) return;
        
        const beachvolleyType = getSelectedBeachVolleyType();
        const limits = playerLimits[beachvolleyType];
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
        
        const beachvolleyType = getSelectedBeachVolleyType();
        if (!beachvolleyType) {
            if (requirementsElement) {
                requirementsElement.innerHTML = `
                    <strong>⚠️ Sélectionnez un type de beach volleyball</strong><br>
                    Veuillez choisir le type d'équipe pour voir les exigences.
                `;
            }
            return;
        }
        
        const limits = playerLimits[beachvolleyType];
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
                } else {
                    categoryInfo = `
                        <div style="margin-top: 0.5rem; padding: 0.5rem; background: #e3f2fd; border-radius: 4px; font-size: 0.9em;">
                            <strong>📅 Catégorie sélectionnée: ${selectedCategory.name}</strong>
                        </div>
                    `;
                }
            } else if (!categoriesLoaded) {
                categoryInfo = `
                    <div style="margin-top: 0.5rem; padding: 0.5rem; background: #fff3cd; border-radius: 4px; font-size: 0.9em;">
                        ⏳ Chargement des catégories d'âge...
                    </div>
                `;
            } else {
                categoryInfo = `
                    <div style="margin-top: 0.5rem; padding: 0.5rem; background: #f8d7da; border-radius: 4px; font-size: 0.9em;">
                        ⚠️ Aucune catégorie d'âge sélectionnée
                    </div>
                `;
            }
            
            requirementsElement.innerHTML = `
                <strong>Composition de l'équipe ${beachvolleyType}</strong><br>
                Minimum: ${limits.min} joueurs<br>
                Maximum: ${limits.max} joueurs
                ${categoryInfo}
            `;
        } else if (requirementsElement) {
            requirementsElement.innerHTML = `
                <strong>⚠️ Type non reconnu</strong><br>
                Le type "${beachvolleyType}" n'est pas reconnu. Veuillez rafraîchir la page.
            `;
        }
        
        // Update add button text
        updateAddButtonText();
    }
    
    // Real-time validation
    function setupRealTimeValidation() {
        document.addEventListener('input', function(e) {
            const field = e.target;
            
            // Handle required fields (including player fields)
            if (field.matches('input[required], select[required], textarea[required]')) {
                console.log('Real-time validation for required field:', field.name, 'Value:', field.value);
                
                // Clear error when user starts typing
                clearFieldError(field);
                
                const value = field.value.trim();
                
                // Validate based on field type and name
                if (value) {
                    let isValid = true;
                    
                    // Date validation for player birth dates
                    if (field.type === 'date' && field.name.includes('date_naissance')) {
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
                    
                    // Add valid class if validation passed - using existing CSS classes
                    if (isValid) {
                        field.classList.add('valid', 'form-control');
                        field.classList.remove('error');
                    }
                } else {
                    // Empty required field - remove valid class but don't show error yet
                    field.classList.remove('valid', 'error');
                }
            }
            
            // Handle optional WhatsApp fields
            else if (field.name && field.name.includes('whatsapp')) {
                console.log('Real-time WhatsApp validation:', field.name, 'Value:', field.value);
                clearFieldError(field);
                
                const value = field.value.trim();
                if (!value) {
                    // Empty WhatsApp field is valid
                    field.classList.remove('error', 'valid');
                    console.log('Empty WhatsApp field - no validation needed');
                } else {
                    // Validate format if has value
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
                console.log('Real-time validation for optional field:', field.name, 'Value:', field.value);
                clearFieldError(field);
                
                const value = field.value.trim();
                let isValid = true;
                
                // Validate based on field type
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
                console.log('Real-time validation for select field:', field.name, 'Value:', field.value);
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
        // Remove any existing global error messages
        document.querySelectorAll('.global-error').forEach(error => error.remove());
        
        // Create error element
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
        
        // Add to page
        document.body.insertBefore(errorElement, document.body.firstChild);
        
        // Auto-hide after 10 seconds
        setTimeout(() => {
            if (errorElement.parentNode) {
                errorElement.remove();
            }
        }, 10000);
        
        console.error('Global error shown:', message);
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
        
        console.error('Form submission disabled:', reason);
    }
    
    console.log('Beach volleyball wizard validation setup complete');
});