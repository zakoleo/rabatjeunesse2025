// Unified Sports Registration Form JavaScript
class UnifiedSportForm {
    constructor() {
        this.sportType = window.SPORT_TYPE || this.detectSportType();
        this.baseUrl = window.APP_BASE_URL || '/';
        this.currentStep = 1;
        this.joueurCount = 0;
        this.categoriesDateRanges = {};
        
        this.initializeElements();
        this.initializePlayerLimits();
        this.bindEvents();
        this.loadDateRanges();
        this.updateProgressBar();
    }
    
    detectSportType() {
        // Detect sport type from data attribute or form elements
        const container = document.querySelector('[data-sport]');
        if (container) {
            return container.getAttribute('data-sport');
        }
        
        // Fallback: detect from form elements
        if (document.getElementById('type-volleyball')) return 'volleyball';
        if (document.getElementById('type-basketball')) return 'basketball';
        if (document.getElementById('type-handball')) return 'handball';
        if (document.getElementById('type-beachvolley')) return 'beachvolley';
        return 'football';
    }
    
    initializeElements() {
        this.elements = {
            form: document.getElementById('inscriptionForm'),
            progressBar: document.querySelector('.progress-bar'),
            progressSteps: document.querySelectorAll('.progress-step'),
            wizardSteps: document.querySelectorAll('.wizard-step'),
            prevBtn: document.getElementById('prevBtn'),
            nextBtn: document.getElementById('nextBtn'),
            submitBtn: document.querySelector('button[type="submit"]'),
            typeField: document.getElementById(`type-${this.sportType}`),
            categoryField: document.getElementById(`${this.sportType}-category-id`),
            nombreJoueursRequis: document.getElementById('nombreJoueursRequis'),
            joueursContainer: document.getElementById('joueursContainer'),
            ajouterJoueurBtn: document.getElementById('ajouterJoueur'),
            sameAsResponsable: document.getElementById('sameAsResponsable'),
            entraineurFields: document.getElementById('entraineurFields')
        };
    }
    
    initializePlayerLimits() {
        this.playerLimits = {
            football: {
                '5x5': { min: 5, max: 8 },
                '6x6': { min: 6, max: 10 },
                '11x11': { min: 11, max: 18 }
            },
            basketball: {
                '5x5': { min: 5, max: 12 }
            },
            handball: {
                '7x7': { min: 7, max: 14 }
            },
            volleyball: {
                '6x6': { min: 6, max: 12 }
            },
            beachvolley: {
                '2x2': { min: 2, max: 4 }
            }
        };
    }
    
    bindEvents() {
        // Navigation buttons
        if (this.elements.prevBtn) {
            this.elements.prevBtn.addEventListener('click', () => this.previousStep());
        }
        if (this.elements.nextBtn) {
            this.elements.nextBtn.addEventListener('click', () => this.nextStep());
        }
        
        // Form submission
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
        
        // Type field change
        if (this.elements.typeField) {
            this.elements.typeField.addEventListener('change', () => this.updatePlayerRequirements());
        }
        
        // Category field change  
        if (this.elements.categoryField) {
            this.elements.categoryField.addEventListener('change', () => this.updatePlayerRequirements());
        }
        
        // Add player button
        if (this.elements.ajouterJoueurBtn) {
            this.elements.ajouterJoueurBtn.addEventListener('click', () => this.addPlayer());
        }
        
        // Same as responsible checkbox
        if (this.elements.sameAsResponsable) {
            this.elements.sameAsResponsable.addEventListener('change', (e) => this.toggleEntraineurFields(e));
        }
    }
    
    async loadDateRanges() {
        try {
            const apiEndpoint = `${this.baseUrl}api/${this.sportType}-date-ranges`;
            console.log(`Loading date ranges from: ${apiEndpoint}`);
            
            const response = await fetch(apiEndpoint);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            console.log('Date ranges loaded:', data);
            
            if (data && data.categories) {
                this.categoriesDateRanges = data.categories;
            } else {
                console.warn('No categories found in response, using fallback data');
                this.categoriesDateRanges = this.getFallbackDateRanges();
            }
            
            this.updatePlayerRequirements();
            
        } catch (error) {
            console.error('Error loading date ranges:', error);
            this.categoriesDateRanges = this.getFallbackDateRanges();
            this.updatePlayerRequirements();
        }
    }
    
    getFallbackDateRanges() {
        const currentYear = new Date().getFullYear();
        return {
            '1': {
                name: 'U12 (2013-2014)',
                date_debut: `${currentYear - 12}-01-01`,
                date_fin: `${currentYear - 10}-12-31`
            },
            '2': {
                name: 'U15 (2010-2012)',
                date_debut: `${currentYear - 15}-01-01`,
                date_fin: `${currentYear - 13}-12-31`
            },
            '3': {
                name: 'U18 (2007-2009)', 
                date_debut: `${currentYear - 18}-01-01`,
                date_fin: `${currentYear - 16}-12-31`
            }
        };
    }
    
    updatePlayerRequirements() {
        const typeValue = this.elements.typeField?.value;
        const categoryValue = this.elements.categoryField?.value;
        
        if (!typeValue || !this.elements.nombreJoueursRequis) return;
        
        const limits = this.playerLimits[this.sportType]?.[typeValue];
        if (!limits) return;
        
        // Get category name for display
        const categoryName = categoryValue && this.categoriesDateRanges[categoryValue] ? 
            this.categoriesDateRanges[categoryValue].name : 'la catégorie sélectionnée';
        
        this.elements.nombreJoueursRequis.innerHTML = `
            <div class="requirement-info">
                <h4>Nombre de joueurs requis pour ${typeValue}</h4>
                <p><strong>Minimum:</strong> ${limits.min} joueurs</p>
                <p><strong>Maximum:</strong> ${limits.max} joueurs</p>
                <p><strong>Catégorie:</strong> ${categoryName}</p>
                <p class="note">Vous devez ajouter entre ${limits.min} et ${limits.max} joueurs pour pouvoir soumettre votre inscription.</p>
            </div>
        `;
        
        this.updateAddPlayerButtonState();
    }
    
    updateAddPlayerButtonState() {
        const typeValue = this.elements.typeField?.value;
        const limits = this.playerLimits[this.sportType]?.[typeValue];
        
        if (!limits || !this.elements.ajouterJoueurBtn) return;
        
        if (this.joueurCount >= limits.max) {
            this.elements.ajouterJoueurBtn.disabled = true;
            this.elements.ajouterJoueurBtn.textContent = `Maximum atteint (${limits.max})`;
        } else {
            this.elements.ajouterJoueurBtn.disabled = false;
            this.elements.ajouterJoueurBtn.textContent = 'Ajouter un joueur';
        }
    }
    
    addPlayer() {
        const typeValue = this.elements.typeField?.value;
        const limits = this.playerLimits[this.sportType]?.[typeValue];
        
        if (!limits || this.joueurCount >= limits.max) return;
        
        this.joueurCount++;
        const playerId = this.joueurCount;
        
        const playerForm = this.createPlayerForm(playerId);
        this.elements.joueursContainer.appendChild(playerForm);
        this.updateAddPlayerButtonState();
    }
    
    createPlayerForm(playerId) {
        const div = document.createElement('div');
        div.className = 'joueur-form';
        div.innerHTML = `
            <div class="joueur-header">
                <h4>Joueur ${playerId}</h4>
                <button type="button" class="btn-remove-joueur" onclick="this.closest('.joueur-form').remove(); window.unifiedForm.joueurCount--; window.unifiedForm.updateAddPlayerButtonState();">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 6L18 18M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="joueur_${playerId}_nom_complet">Nom complet *</label>
                    <input type="text" name="joueurs[${playerId}][nom_complet]" id="joueur_${playerId}_nom_complet" required>
                </div>
                <div class="form-group">
                    <label for="joueur_${playerId}_date_naissance">Date de naissance *</label>
                    <input type="date" name="joueurs[${playerId}][date_naissance]" id="joueur_${playerId}_date_naissance" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="joueur_${playerId}_numero_maillot">Numéro de maillot *</label>
                    <input type="number" name="joueurs[${playerId}][numero_maillot]" id="joueur_${playerId}_numero_maillot" min="1" max="99" required>
                </div>
                <div class="form-group">
                    <label for="joueur_${playerId}_position">Position préférée</label>
                    <input type="text" name="joueurs[${playerId}][position]" id="joueur_${playerId}_position">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="joueur_${playerId}_cin_recto">CIN Recto *</label>
                    <input type="file" name="joueurs[${playerId}][cin_recto]" id="joueur_${playerId}_cin_recto" accept="image/*,.pdf" required>
                    <small>Face avant de la CIN - Formats : JPG, PNG, PDF</small>
                </div>
                <div class="form-group">
                    <label for="joueur_${playerId}_cin_verso">CIN Verso *</label>
                    <input type="file" name="joueurs[${playerId}][cin_verso]" id="joueur_${playerId}_cin_verso" accept="image/*,.pdf" required>
                    <small>Face arrière de la CIN - Formats : JPG, PNG, PDF</small>
                </div>
            </div>
        `;
        
        return div;
    }
    
    toggleEntraineurFields(event) {
        if (!this.elements.entraineurFields) return;
        
        if (event.target.checked) {
            this.elements.entraineurFields.style.display = 'none';
            this.setEntraineurFieldsRequired(false);
        } else {
            this.elements.entraineurFields.style.display = 'block';
            this.setEntraineurFieldsRequired(true);
        }
    }
    
    setEntraineurFieldsRequired(required) {
        const fields = this.elements.entraineurFields?.querySelectorAll('input[required], select[required]');
        fields?.forEach(field => {
            if (required) {
                field.setAttribute('required', '');
            } else {
                field.removeAttribute('required');
            }
        });
    }
    
    nextStep() {
        if (this.validateStep(this.currentStep)) {
            this.currentStep++;
            this.showStep(this.currentStep);
            this.updateProgressBar();
        }
    }
    
    previousStep() {
        this.currentStep--;
        this.showStep(this.currentStep);
        this.updateProgressBar();
    }
    
    showStep(step) {
        // Hide all steps
        this.elements.wizardSteps.forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        
        // Show current step
        const currentStepEl = document.querySelector(`[data-step="${step}"]`);
        if (currentStepEl) {
            currentStepEl.classList.add('active');
        }
        
        // Update navigation buttons
        this.elements.prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
        this.elements.nextBtn.style.display = step < 3 ? 'inline-block' : 'none';
        this.elements.submitBtn.style.display = step === 3 ? 'inline-block' : 'none';
    }
    
    updateProgressBar() {
        // Update progress steps
        this.elements.progressSteps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber < this.currentStep) {
                step.classList.add('completed');
            } else if (stepNumber === this.currentStep) {
                step.classList.add('active');
            }
        });
        
        // Update progress bar data attribute
        if (this.elements.progressBar) {
            this.elements.progressBar.setAttribute('data-progress', this.currentStep);
        }
    }
    
    validateStep(step) {
        const stepElement = document.querySelector(`[data-step="${step}"]`);
        if (!stepElement) return false;
        
        const requiredFields = stepElement.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            this.clearFieldError(field);
            
            if (!field.value.trim()) {
                this.showFieldError(field, 'Ce champ est requis');
                isValid = false;
            } else if (field.type === 'email' && !this.isValidEmail(field.value)) {
                this.showFieldError(field, 'Email invalide');
                isValid = false;
            } else if (field.type === 'tel' && !this.isValidPhone(field.value)) {
                this.showFieldError(field, 'Numéro de téléphone invalide');
                isValid = false;
            }
        });
        
        // Special validation for step 3 (players)
        if (step === 3) {
            const typeValue = this.elements.typeField?.value;
            const limits = this.playerLimits[this.sportType]?.[typeValue];
            
            if (limits && (this.joueurCount < limits.min || this.joueurCount > limits.max)) {
                this.showAlert(`Vous devez ajouter entre ${limits.min} et ${limits.max} joueurs.`, 'error');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    showFieldError(field, message) {
        field.classList.add('error');
        const formGroup = field.closest('.form-group');
        if (formGroup) {
            formGroup.classList.add('error');
            
            // Remove existing error message
            const existingError = formGroup.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
            
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        }
    }
    
    clearFieldError(field) {
        field.classList.remove('error');
        const formGroup = field.closest('.form-group');
        if (formGroup) {
            formGroup.classList.remove('error');
            const errorMessage = formGroup.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        }
    }
    
    showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        
        // Insert at the top of the current step
        const currentStep = document.querySelector('.wizard-step.active');
        if (currentStep) {
            currentStep.insertBefore(alertDiv, currentStep.firstChild);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    }
    
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    isValidPhone(phone) {
        return /^[0-9]{10}$/.test(phone.replace(/\s/g, ''));
    }
    
    handleSubmit(event) {
        if (!this.validateStep(3)) {
            event.preventDefault();
            return false;
        }
        
        // Show loading state
        this.elements.submitBtn.disabled = true;
        this.elements.submitBtn.textContent = 'Envoi en cours...';
        
        return true;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.unifiedForm = new UnifiedSportForm();
});