/**
 * Football Team Registration Wizard Validation
 * Clean, focused validation for football team registration form
 */

document.addEventListener('DOMContentLoaded', function() {
    // Wizard state
    let currentStep = 1;
    const maxSteps = 3;
    let playerIndex = 0;
    
    // Football-specific player limits (will be populated from server)
    let playerLimits = {
        '5x5': { min: 5, max: 10 },
        '6x6': { min: 6, max: 12 },
        '11x11': { min: 11, max: 18 }
    };
    
    console.log('Football wizard validation initialized');
    
    // Age categories data (will be populated from server)
    let footballCategories = {};
    
    // Initialize wizard
    initializeWizard();
    
    // Load dynamic data
    loadAgeCategories();
    loadFootballTypes();
    
    // Function to load age categories from server
    function loadAgeCategories() {
        const categorySelect = document.querySelector('[name="football_category_id"]');
        if (categorySelect) {
            // First, load basic info from select options
            const options = categorySelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value && option.textContent.trim()) {
                    footballCategories[option.value] = {
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
        fetch('/teams/getCategories?sport_id=1')
            .then(response => response.json())
            .then(data => {
                console.log('Received detailed category data:', data);
                
                // Update categories with database info
                data.categories.forEach(category => {
                    if (footballCategories[category.id]) {
                        footballCategories[category.id] = {
                            ...footballCategories[category.id],
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
                
                console.log('Updated football categories with database data:', footballCategories);
            })
            .catch(error => {
                console.warn('Could not load detailed category data:', error);
                console.log('Using fallback parsing from select options');
                fallbackCategoryParsing();
            });
    }

    // Load football types from server
    function loadFootballTypes() {
        fetch('/teams/getFootballTypes')
            .then(response => response.json())
            .then(data => {
                console.log('Received football types data:', data);
                
                // Update player limits with server data
                const newPlayerLimits = {};
                data.football_types.forEach(type => {
                    newPlayerLimits[type.code] = {
                        min: type.min_players,
                        max: type.max_players,
                        name: type.name,
                        id: type.id
                    };
                });
                
                // Merge with existing limits (fallback)
                playerLimits = { ...playerLimits, ...newPlayerLimits };
                console.log('Updated player limits:', playerLimits);
            })
            .catch(error => {
                console.warn('Could not load football types data, using defaults:', error);
            });
    }
    
    // Fallback to parsing from select option text
    function fallbackCategoryParsing() {
        const categorySelect = document.querySelector('[name="football_category_id"]');
        if (categorySelect) {
            const options = categorySelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.value && option.textContent.trim()) {
                    const text = option.textContent.trim();
                    const ageRange = extractAgeRange(text);
                    if (ageRange && footballCategories[option.value]) {
                        footballCategories[option.value] = {
                            ...footballCategories[option.value],
                            ...ageRange
                        };
                    }
                }
            });
        }
    }
    
    // Extract age range from database category text 
    // Format examples: "-12 (-12 ans, 2014, 2015, 2014-01-01, 2015-12-31)"
    function extractAgeRange(text) {
        console.log('Parsing category text:', text);
        
        // Try to extract age directly from category name (e.g., "-12", "U17", etc.)
        const agePatterns = [
            // Database format: "-12 (-12 ans, 2014, 2015, 2014-01-01, 2015-12-31)"
            /^-(\d+)\s*\(-(\d+)\s*ans?,\s*(\d{4}),\s*(\d{4}),\s*(\d{4}-\d{2}-\d{2}),\s*(\d{4}-\d{2}-\d{2})\)/,
            
            // Other possible formats
            /(\d+)-(\d+)\s*ans?/i,           // "15-17 ans"
            /(\d+)\s*à\s*(\d+)\s*ans?/i,     // "15 à 17 ans"
            /U(\d+)/i,                       // "U17" (under 17)
            /-(\d+)/,                        // "-12" format
            /(\d+)\s*ans?/i,                 // "12 ans"
        ];
        
        for (const pattern of agePatterns) {
            const match = text.match(pattern);
            if (match) {
                console.log('Pattern matched:', pattern, 'Result:', match);
                
                if (pattern === agePatterns[0]) {
                    // Database format: "-12 (-12 ans, 2014, 2015, 2014-01-01, 2015-12-31)"
                    const age = parseInt(match[2]); // -12 ans
                    const birthYearStart = parseInt(match[3]); // 2014
                    const birthYearEnd = parseInt(match[4]); // 2015
                    const birthDateStart = match[5]; // 2014-01-01
                    const birthDateEnd = match[6]; // 2015-12-31
                    
                    return {
                        age: age,
                        minAge: age,
                        maxAge: age,
                        birthYearStart: birthYearStart,
                        birthYearEnd: birthYearEnd,
                        birthDateStart: new Date(birthDateStart),
                        birthDateEnd: new Date(birthDateEnd),
                        format: 'database'
                    };
                } else if (pattern === agePatterns[2]) {
                    // "15 à 17 ans"
                    const minAge = parseInt(match[1]);
                    const maxAge = parseInt(match[2]);
                    return { minAge: minAge, maxAge: maxAge, format: 'range' };
                } else if (pattern === agePatterns[1]) {
                    // "15-17 ans"
                    const minAge = parseInt(match[1]);
                    const maxAge = parseInt(match[2]);
                    return { minAge: minAge, maxAge: maxAge, format: 'range' };
                } else if (pattern === agePatterns[3]) {
                    // "U17"
                    const maxAge = parseInt(match[1]);
                    const minAge = Math.max(0, maxAge - 2);
                    return { minAge: minAge, maxAge: maxAge, format: 'under' };
                } else if (pattern === agePatterns[4]) {
                    // "-12"
                    const age = parseInt(match[1]);
                    return { minAge: age, maxAge: age, age: age, format: 'single' };
                } else if (pattern === agePatterns[5]) {
                    // "12 ans"
                    const age = parseInt(match[1]);
                    return { minAge: age, maxAge: age, age: age, format: 'single' };
                }
            }
        }
        
        console.warn('Could not extract age range from:', text);
        return null;
    }
    
    // Get selected age category
    function getSelectedAgeCategory() {
        const categorySelect = document.querySelector('[name="football_category_id"]');
        if (categorySelect && categorySelect.value) {
            return footballCategories[categorySelect.value];
        }
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
            if (category.format === 'database' && category.minBirthYear && category.maxBirthYear) {
                const birthYear = new Date(birthDateField.value).getFullYear();
                message = `Année de naissance invalide pour la catégorie ${category.name}. Années requises: ${category.minBirthYear}-${category.maxBirthYear} (joueur: ${birthYear})`;
            } else {
                message = `Âge invalide pour la catégorie ${category.name}. Âge requis: ${category.minAge}-${category.maxAge} ans (joueur: ${age} ans)`;
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
        
        // Mark as valid - using existing CSS classes
        field.classList.remove('error');
        field.classList.add('valid', 'form-control');
        console.log('Optional field format validated successfully:', field.name);
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
        const birthDate = new Date(dateStr);
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
            const category = getSelectedAgeCategory();
            if (category) {
                console.log(`Player validation against category: ${category.name}`, category);
                
                // Use database date range validation if available
                if (category.format === 'database' && category.minDate && category.maxDate) {
                    console.log(`Using database date range validation: ${category.minDate} to ${category.maxDate}`);
                    const isValidDate = birthDate >= category.minDate && birthDate <= category.maxDate;
                    console.log(`Date range validation result: ${isValidDate} for birth date: ${birthDate}`);
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
                // No category selected - allow general football age range
                console.log(`Player age validation without category - general range: 6-35 years`);
                return age >= 6 && age <= 35;
            }
        }
        
        // For non-player fields (manager/coach), use general age range
        return age >= 16 && age <= 80;
    }
    
    // Player count validation
    function validatePlayerCount() {
        const playersCount = document.querySelectorAll('.joueur-form').length;
        const footballType = getSelectedFootballType();
        const limits = playerLimits[footballType];
        
        if (!limits) {
            console.log('Unknown football type:', footballType);
            return true;
        }
        
        console.log(`Players: ${playersCount}, Required: ${limits.min}-${limits.max} for ${footballType}`);
        
        if (playersCount < limits.min) {
            const container = document.getElementById('joueursContainer');
            if (container) {
                showContainerError(container, `Vous devez ajouter au moins ${limits.min} joueurs pour le ${footballType}`);
            }
            return false;
        }
        
        return true;
    }
    
    // Get selected football type
    function getSelectedFootballType() {
        const typeField = document.querySelector('[name="type_football"]');
        return typeField ? typeField.value : '5x5';
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
        
        // Update requirements when football type changes
        const typeField = document.querySelector('[name="type_football"]');
        if (typeField) {
            typeField.addEventListener('change', function() {
                updatePlayerRequirements();
                generateMinimumPlayers();
            });
        }
        
        // Re-validate player ages when age category changes
        const categoryField = document.querySelector('[name="football_category_id"]');
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
        
        const footballType = getSelectedFootballType();
        const limits = playerLimits[footballType];
        const currentPlayers = container.querySelectorAll('.joueur-form').length;
        
        if (limits && currentPlayers >= limits.max) {
            showContainerError(container, `Maximum ${limits.max} joueurs pour le ${footballType}`);
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
        
        const footballType = getSelectedFootballType();
        const limits = playerLimits[footballType];
        const currentCount = container.querySelectorAll('.joueur-form').length;
        
        // Prevent removing below minimum required
        if (limits && currentCount <= limits.min) {
            console.log(`Cannot remove player: minimum ${limits.min} required for ${footballType}`);
            
            // Show temporary message
            const message = document.createElement('div');
            message.className = 'alert alert-warning';
            message.style.marginTop = '10px';
            message.textContent = `Impossible de supprimer: minimum ${limits.min} joueurs requis pour le ${footballType}`;
            
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
        
        const footballType = getSelectedFootballType();
        const limits = playerLimits[footballType];
        const currentPlayerCount = container.querySelectorAll('.joueur-form').length;
        
        if (!limits) {
            console.log('No limits found for football type:', footballType);
            return;
        }
        
        console.log(`Generating minimum players for ${footballType}: need ${limits.min}, have ${currentPlayerCount}`);
        
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
        
        const footballType = getSelectedFootballType();
        const limits = playerLimits[footballType];
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
        const footballType = getSelectedFootballType();
        const limits = playerLimits[footballType];
        const requirementsElement = document.getElementById('nombreJoueursRequis');
        
        if (limits && requirementsElement) {
            requirementsElement.innerHTML = `
                <strong>Composition de l'équipe ${footballType}</strong><br>
                Minimum: ${limits.min} joueurs<br>
                Maximum: ${limits.max} joueurs
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
    
    console.log('Football wizard validation setup complete');
});