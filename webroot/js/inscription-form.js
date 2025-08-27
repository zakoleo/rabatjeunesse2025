document.addEventListener('DOMContentLoaded', function() {
    const typeFootball = document.getElementById('type-football');
    const nombreJoueursRequis = document.getElementById('nombreJoueursRequis');
    const joueursContainer = document.getElementById('joueursContainer');
    const ajouterJoueurBtn = document.getElementById('ajouterJoueur');
    const inscriptionForm = document.getElementById('inscriptionForm');
    
    let joueurCount = 0;
    const joueursMin = {
        '5x5': 5,
        '6x6': 6,
        '11x11': 11
    };
    
    const joueursMax = {
        '5x5': 8,
        '6x6': 10,
        '11x11': 18
    };
    
    // Règles de validation des dates de naissance par catégorie
    const categoriesDateRanges = {
        '-12 ans': { min: '2013-01-01', max: '2025-12-31' },
        '-15 ans': { min: '2010-01-01', max: '2025-12-31' },
        '-18 ans': { min: '2007-01-01', max: '2025-12-31' },
        '+18 ans': { min: '1970-01-01', max: '2006-12-31' }
    };
    
    // Wizard functionality
    let currentStep = 1;
    const totalSteps = 3;
    const progressSteps = document.querySelectorAll('.progress-step');
    const wizardSteps = document.querySelectorAll('.wizard-step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    function showStep(step) {
        // Hide all steps
        wizardSteps.forEach((wizardStep, index) => {
            wizardStep.classList.remove('active');
            progressSteps[index]?.classList.remove('active', 'completed');
        });
        
        // Show current step
        const currentWizardStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (currentWizardStep) {
            currentWizardStep.classList.add('active');
        }
        
        // Update progress bar
        progressSteps.forEach((progressStep, index) => {
            if (index < step - 1) {
                progressStep.classList.add('completed');
            } else if (index === step - 1) {
                progressStep.classList.add('active');
            }
        });
        
        // Update navigation buttons
        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        
        if (step === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            submitBtn.style.display = 'none';
        }
    }
    
    function validateStep(step) {
        const currentWizardStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        const requiredFields = currentWizardStep.querySelectorAll('[required]:not(:disabled)');
        let isValid = true;
        let firstErrorField = null;
        
        // Remove all existing error messages
        currentWizardStep.querySelectorAll('.error-message').forEach(msg => msg.remove());
        
        requiredFields.forEach(field => {
            const formGroup = field.closest('.form-group');
            
            if (!field.value || field.value.trim() === '') {
                field.classList.add('error');
                isValid = false;
                
                // Add error message
                if (formGroup) {
                    const errorMsg = document.createElement('span');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = getErrorMessage(field);
                    errorMsg.style.color = '#dc3545';
                    errorMsg.style.fontSize = '0.875rem';
                    errorMsg.style.marginTop = '0.25rem';
                    errorMsg.style.display = 'block';
                    formGroup.appendChild(errorMsg);
                }
                
                if (!firstErrorField) {
                    firstErrorField = field;
                }
            } else {
                field.classList.remove('error');
            }
            
            // Validate pattern if exists and field has value
            if (field.value && field.value.trim() !== '' && field.hasAttribute('pattern')) {
                const pattern = new RegExp(field.getAttribute('pattern'));
                if (!pattern.test(field.value)) {
                    field.classList.add('error');
                    isValid = false;
                    
                    if (formGroup) {
                        const errorMsg = document.createElement('span');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = getPatternErrorMessage(field);
                        errorMsg.style.color = '#dc3545';
                        errorMsg.style.fontSize = '0.875rem';
                        errorMsg.style.marginTop = '0.25rem';
                        errorMsg.style.display = 'block';
                        formGroup.appendChild(errorMsg);
                    }
                    
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                }
            }
        });
        
        // Special validation for step 1
        if (step === 1) {
            const categorieField = document.getElementById('football-category-id');
            const typeField = document.getElementById('type-football');
            
            if (!categorieField || !categorieField.value) {
                showNotification('Veuillez sélectionner une catégorie d\'âge', 'error');
                return false;
            }
            
            if (!typeField || !typeField.value) {
                showNotification('Veuillez sélectionner un type de football', 'error');
                return false;
            }
        }
        
        // Special validation for step 3
        if (step === 3) {
            const type = typeFootball.value;
            if (type && joueurCount < joueursMin[type]) {
                showNotification(`Vous devez ajouter au minimum ${joueursMin[type]} joueurs`, 'error');
                return false;
            }
            
            // Valider les dates de naissance des joueurs
            const categorieField = document.getElementById('football-category-id');
            const categorieName = categorieField?.options[categorieField.selectedIndex]?.text || '';
            const joueurItems = currentWizardStep.querySelectorAll('.joueur-item');
            let datesValid = true;
            
            joueurItems.forEach((joueurItem, index) => {
                const dateField = joueurItem.querySelector('input[type="date"]');
                if (dateField && dateField.value) {
                    const validation = validateJoueurDateNaissance(dateField.value, categorieName);
                    if (!validation.valid) {
                        datesValid = false;
                        dateField.classList.add('error');
                        
                        // Ajouter le message d'erreur
                        const formGroup = dateField.closest('.form-group');
                        if (formGroup && !formGroup.querySelector('.error-message')) {
                            const errorMsg = document.createElement('span');
                            errorMsg.className = 'error-message';
                            errorMsg.textContent = validation.message;
                            errorMsg.style.color = '#dc3545';
                            errorMsg.style.fontSize = '0.875rem';
                            errorMsg.style.marginTop = '0.25rem';
                            errorMsg.style.display = 'block';
                            formGroup.appendChild(errorMsg);
                        }
                        
                        if (!firstErrorField) {
                            firstErrorField = dateField;
                        }
                    }
                }
            });
            
            if (!datesValid) {
                showNotification('Les dates de naissance des joueurs ne correspondent pas à la catégorie sélectionnée', 'error');
                if (firstErrorField) {
                    firstErrorField.focus();
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            if (!document.getElementById('accepter-reglement').checked) {
                showNotification('Vous devez accepter le règlement', 'error');
                return false;
            }
        }
        
        if (!isValid) {
            showNotification('Veuillez corriger les erreurs avant de continuer', 'error');
            if (firstErrorField) {
                firstErrorField.focus();
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        return isValid;
    }
    
    function getErrorMessage(field) {
        const label = field.closest('.form-group')?.querySelector('label')?.textContent || 'Ce champ';
        return `${label.replace('*', '').trim()} est obligatoire`;
    }
    
    function getPatternErrorMessage(field) {
        if (field.type === 'tel') {
            return 'Le numéro de téléphone doit contenir 10 chiffres';
        }
        return 'Format invalide';
    }
    
    function validateJoueurDateNaissance(dateNaissance, categorie) {
        if (!dateNaissance || !categorie) return { valid: true };
        
        const dateRange = categoriesDateRanges[categorie];
        if (!dateRange) return { valid: true };
        
        const birthDate = new Date(dateNaissance);
        const minDate = new Date(dateRange.min);
        const maxDate = new Date(dateRange.max);
        
        if (birthDate < minDate || birthDate > maxDate) {
            return {
                valid: false,
                message: `Pour la catégorie ${categorie}, le joueur doit être né entre le ${formatDate(minDate)} et le ${formatDate(maxDate)}`
            };
        }
        
        return { valid: true };
    }
    
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
    
    nextBtn.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    
    prevBtn.addEventListener('click', () => {
        currentStep--;
        showStep(currentStep);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Initialize wizard
    showStep(currentStep);
    
    // Add event listeners to remove errors on input
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('error')) {
            e.target.classList.remove('error');
            const errorMsg = e.target.closest('.form-group')?.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    });
    
    // Add event listeners for select fields
    document.addEventListener('change', function(e) {
        if (e.target.tagName === 'SELECT' && e.target.classList.contains('error')) {
            e.target.classList.remove('error');
            const errorMsg = e.target.closest('.form-group')?.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        }
    });
    
    // Handle optional fields with pattern validation
    const whatsappField = document.getElementById('responsable-whatsapp');
    if (whatsappField) {
        // Remove pattern if field is empty to avoid validation issues
        whatsappField.addEventListener('blur', function() {
            if (!this.value) {
                this.removeAttribute('pattern');
            } else if (this.dataset.originalPattern) {
                this.setAttribute('pattern', this.dataset.originalPattern);
            }
        });
        
        // Store original pattern
        whatsappField.dataset.originalPattern = whatsappField.getAttribute('pattern');
    }
    
    // Gestion des types de football selon la catégorie
    const categorieSelect = document.getElementById('football-category-id');
    const typeFootballSelect = document.getElementById('type-football');
    const typeFootballHelp = document.getElementById('type-football-help');
    
    categorieSelect?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categorieName = selectedOption?.text || '';
        typeFootballSelect.value = ''; // Reset selection
        nombreJoueursRequis.textContent = ''; // Reset player count display
        
        // Clear existing players
        joueursContainer.innerHTML = '';
        joueurCount = 0;
        
        // Update football type options based on category
        const options = typeFootballSelect.querySelectorAll('option');
        options.forEach(option => {
            if (option.value === '') return; // Skip the default option
            
            if (categorieName === 'U12' || categorieName === 'U15') {
                // U12 and U15 can only choose 6x6
                if (option.value === '6x6') {
                    option.style.display = 'block';
                    option.disabled = false;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            } else if (categorieName === 'U18' || categorieName === '18+') {
                // U18 and 18+ can choose 5x5 or 11x11
                if (option.value === '5x5' || option.value === '11x11') {
                    option.style.display = 'block';
                    option.disabled = false;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            } else {
                // No category selected, show all options
                option.style.display = 'block';
                option.disabled = false;
            }
        });
        
        // Update help text based on category
        if (typeFootballHelp) {
            if (categorieName === 'U12' || categorieName === 'U15') {
                typeFootballHelp.textContent = 'Les catégories U12 et U15 participent uniquement au football à 6';
            } else if (categorieName === 'U18' || categorieName === '18+') {
                typeFootballHelp.textContent = 'Les catégories U18 et 18+ peuvent choisir entre football à 5 et football à 11';
            } else {
                typeFootballHelp.textContent = '';
            }
        }
        
        // Auto-select if only one option is available
        const visibleOptions = Array.from(options).filter(opt => 
            opt.value !== '' && opt.style.display !== 'none' && !opt.disabled
        );
        if (visibleOptions.length === 1) {
            typeFootballSelect.value = visibleOptions[0].value;
            typeFootballSelect.dispatchEvent(new Event('change'));
        }
    });
    
    // Mettre à jour le nombre de joueurs requis
    typeFootballSelect?.addEventListener('change', function() {
        const type = this.value;
        if (type && joueursMin[type]) {
            nombreJoueursRequis.innerHTML = `<strong>Minimum ${joueursMin[type]}</strong> joueurs, <strong>Maximum ${joueursMax[type]}</strong> joueurs`;
            // Ajouter automatiquement le minimum de joueurs
            while (joueurCount < joueursMin[type]) {
                ajouterJoueur();
            }
        } else {
            nombreJoueursRequis.textContent = '';
        }
    });
    
    // Gérer l'entraîneur identique au responsable
    const sameAsResponsable = document.getElementById('sameAsResponsable');
    const entraineurFields = document.getElementById('entraineurFields');
    
    sameAsResponsable?.addEventListener('change', function() {
        if (this.checked) {
            entraineurFields.style.opacity = '0.5';
            entraineurFields.querySelectorAll('input, select').forEach(field => {
                field.disabled = true;
                field.removeAttribute('required');
            });
        } else {
            entraineurFields.style.opacity = '1';
            entraineurFields.querySelectorAll('input, select').forEach(field => {
                field.disabled = false;
                if (field.dataset.wasRequired !== 'false') {
                    field.setAttribute('required', 'required');
                }
            });
        }
    });
    
    // Ajouter un joueur
    ajouterJoueurBtn?.addEventListener('click', function() {
        const categorieField = document.getElementById('football-category-id');
        const categorie = categorieField?.value;
        if (!categorie) {
            showNotification('Veuillez d\'abord sélectionner la catégorie d\'âge', 'error');
            return;
        }
        
        const type = typeFootballSelect?.value;
        if (!type) {
            showNotification('Veuillez d\'abord sélectionner le type de football', 'error');
            return;
        }
        
        if (joueurCount >= joueursMax[type]) {
            showNotification(`Nombre maximum de joueurs atteint pour le ${type}`, 'error');
            return;
        }
        
        ajouterJoueur();
    });
    
    function ajouterJoueur() {
        joueurCount++;
        const joueurDiv = document.createElement('div');
        joueurDiv.className = 'joueur-item';
        joueurDiv.innerHTML = `
            <div class="joueur-header">
                <h3>Joueur ${joueurCount}</h3>
                <button type="button" class="btn btn-danger" onclick="supprimerJoueur(this, ${joueurCount})">
                    Supprimer
                </button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="joueur-${joueurCount}-nom-complet">Nom complet *</label>
                    <input type="text" id="joueur-${joueurCount}-nom-complet" name="joueurs[${joueurCount}][nom_complet]" required>
                </div>
                <div class="form-group">
                    <label for="joueur-${joueurCount}-date-naissance">Date de naissance *</label>
                    <input type="date" id="joueur-${joueurCount}-date-naissance" name="joueurs[${joueurCount}][date_naissance]" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="joueur-${joueurCount}-identifiant">CIN ou Code Massar *</label>
                    <input type="text" id="joueur-${joueurCount}-identifiant" name="joueurs[${joueurCount}][identifiant]" required>
                    <small>CIN pour les adultes, Code Massar pour les mineurs</small>
                </div>
                <div class="form-group">
                    <label for="joueur-${joueurCount}-taille">Taille vestimentaire *</label>
                    <select id="joueur-${joueurCount}-taille" name="joueurs[${joueurCount}][taille_vestimentaire]" required>
                        <option value="">Sélectionner une taille</option>
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
        joueursContainer.appendChild(joueurDiv);
        
        // Ajouter la validation de date au nouveau joueur
        const dateField = joueurDiv.querySelector(`#joueur-${joueurCount}-date-naissance`);
        if (dateField) {
            dateField.addEventListener('change', function() {
                validateJoueurDateField(this);
            });
        }
        
        // Animate the new player
        setTimeout(() => {
            joueurDiv.style.opacity = '1';
        }, 10);
    }
    
    function validateJoueurDateField(dateField) {
        const categorieField = document.getElementById('football-category-id');
        const categorieName = categorieField?.options[categorieField.selectedIndex]?.text || '';
        const validation = validateJoueurDateNaissance(dateField.value, categorieName);
        
        const formGroup = dateField.closest('.form-group');
        const existingError = formGroup?.querySelector('.error-message');
        
        if (!validation.valid) {
            dateField.classList.add('error');
            if (formGroup && !existingError) {
                const errorMsg = document.createElement('span');
                errorMsg.className = 'error-message';
                errorMsg.textContent = validation.message;
                errorMsg.style.color = '#dc3545';
                errorMsg.style.fontSize = '0.875rem';
                errorMsg.style.marginTop = '0.25rem';
                errorMsg.style.display = 'block';
                formGroup.appendChild(errorMsg);
            } else if (existingError) {
                existingError.textContent = validation.message;
            }
        } else {
            dateField.classList.remove('error');
            if (existingError) {
                existingError.remove();
            }
        }
    }
    
    // Supprimer un joueur
    window.supprimerJoueur = function(button, index) {
        const type = typeFootballSelect?.value;
        if (type && joueurCount <= joueursMin[type]) {
            showNotification(`Nombre minimum de joueurs requis pour le ${type}: ${joueursMin[type]}`, 'error');
            return;
        }
        
        const joueurItem = button.closest('.joueur-item');
        joueurItem.style.opacity = '0';
        
        setTimeout(() => {
            joueurItem.remove();
            joueurCount--;
            
            // Renuméroter les joueurs
            const joueurs = document.querySelectorAll('.joueur-item');
            joueurs.forEach((joueur, idx) => {
                const newIndex = idx + 1;
                joueur.querySelector('h3').textContent = `Joueur ${newIndex}`;
                
                // Update all field names and IDs
                joueur.querySelectorAll('[name*="joueurs"]').forEach(field => {
                    field.name = field.name.replace(/joueurs\[\d+\]/, `joueurs[${newIndex}]`);
                });
                joueur.querySelectorAll('[id*="joueur-"]').forEach(field => {
                    field.id = field.id.replace(/joueur-\d+-/, `joueur-${newIndex}-`);
                });
                joueur.querySelectorAll('[for*="joueur-"]').forEach(label => {
                    label.setAttribute('for', label.getAttribute('for').replace(/joueur-\d+-/, `joueur-${newIndex}-`));
                });
            });
        }, 300);
    };
    
    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Form submission
    inscriptionForm?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all steps before submission
        let allValid = true;
        for (let step = 1; step <= totalSteps; step++) {
            if (!validateStep(step)) {
                allValid = false;
                showNotification(`Veuillez vérifier les informations de l'étape ${step}`, 'error');
                currentStep = step;
                showStep(currentStep);
                break;
            }
        }
        
        if (!allValid) {
            return;
        }
        
        // Remove validation attributes from hidden fields to prevent browser validation errors
        const allSteps = document.querySelectorAll('.wizard-step');
        allSteps.forEach((step, index) => {
            // For all steps (including current), handle pattern validation on optional fields
            const fields = step.querySelectorAll('[pattern]');
            fields.forEach(field => {
                // If field has pattern but no required and is empty, remove pattern temporarily
                if (!field.hasAttribute('required') && !field.value) {
                    field.dataset.originalPattern = field.getAttribute('pattern');
                    field.removeAttribute('pattern');
                }
            });
            
            if (index + 1 !== currentStep) {
                // For non-visible steps, temporarily remove all validation
                const validationFields = step.querySelectorAll('[required], [pattern]');
                validationFields.forEach(field => {
                    if (field.hasAttribute('required')) {
                        field.dataset.originalRequired = 'true';
                        field.removeAttribute('required');
                    }
                    if (field.hasAttribute('pattern')) {
                        field.dataset.originalPattern = field.getAttribute('pattern');
                        field.removeAttribute('pattern');
                    }
                });
            }
        });
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi en cours...';
        
        // Submit the form
        setTimeout(() => {
            this.submit();
        }, 100);
    });
});