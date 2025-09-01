document.addEventListener('DOMContentLoaded', function() {
    // Simple wizard functionality
    let currentStep = 1;
    const maxSteps = 3;
    let playerIndex = 0;
    
    // Player limits for different sports and types
    const joueursMin = {
        '5x5': 5,
        '6x6': 6,
        '7x7': 7,
        '11x11': 11,
        '2x2': 2
    };
    
    const joueursMax = {
        '5x5': 10,
        '6x6': 12,
        '7x7': 14,
        '11x11': 18,
        '2x2': 4
    };
    
    // Initialize the form
    updateStepDisplay();
    updatePlayerRequirements();
    
    // Navigation button handlers
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (validateCurrentStep()) {
                if (currentStep < maxSteps) {
                    currentStep++;
                    updateStepDisplay();
                }
            }
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStepDisplay();
            }
        });
    }
    
    // Add player functionality
    const ajouterJoueurBtn = document.getElementById('ajouterJoueur');
    if (ajouterJoueurBtn) {
        ajouterJoueurBtn.addEventListener('click', addPlayer);
    }
    
    // Same as manager checkbox
    const sameAsResponsable = document.getElementById('sameAsResponsable');
    if (sameAsResponsable) {
        sameAsResponsable.addEventListener('change', toggleCoachFields);
    }
    
    // Sport type change listeners
    const typeElements = document.querySelectorAll('[id*="type-"]');
    typeElements.forEach(element => {
        element.addEventListener('change', updatePlayerRequirements);
    });
    
    function updateStepDisplay() {
        // Update progress indicators
        document.querySelectorAll('.progress-step').forEach((step, index) => {
            const stepNum = index + 1;
            if (stepNum === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else if (stepNum < currentStep) {
                step.classList.remove('active');
                step.classList.add('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
        
        // Update progress bar data attribute
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.setAttribute('data-progress', currentStep);
        }
        
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
    
    function validateCurrentStep() {
        let isValid = true;
        const currentStepElement = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
        
        if (!currentStepElement) return true;
        
        // Clear previous errors
        currentStepElement.querySelectorAll('.error-message').forEach(error => {
            error.remove();
        });
        currentStepElement.querySelectorAll('.error').forEach(field => {
            field.classList.remove('error');
        });
        
        // Validate required fields in current step
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showFieldError(field, 'Ce champ est requis');
                isValid = false;
            } else if (field.type === 'email' && !isValidEmail(field.value)) {
                showFieldError(field, 'Veuillez entrer une adresse e-mail valide');
                isValid = false;
            } else if (field.type === 'tel' && !isValidPhone(field.value)) {
                showFieldError(field, 'Veuillez entrer un numéro de téléphone valide');
                isValid = false;
            }
        });
        
        // Step 3 validation: Check minimum players
        if (currentStep === 3) {
            const playersCount = document.querySelectorAll('.joueur-form, .joueur-item').length;
            const selectedType = getSelectedSportType();
            const minRequired = joueursMin[selectedType] || 5;
            
            if (playersCount < minRequired) {
                const container = document.getElementById('joueursContainer') || document.getElementById('joueurs-container');
                if (container) {
                    showContainerError(container, `Vous devez ajouter au moins ${minRequired} joueurs pour cette catégorie`);
                    isValid = false;
                }
            }
        }
        
        return isValid;
    }
    
    function showFieldError(field, message) {
        field.classList.add('error');
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
    }
    
    function showContainerError(container, message) {
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        errorElement.style.marginTop = '1rem';
        errorElement.style.padding = '0.75rem';
        errorElement.style.backgroundColor = '#fff5f5';
        errorElement.style.border = '1px solid #dc3545';
        errorElement.style.borderRadius = '4px';
        container.appendChild(errorElement);
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function isValidPhone(phone) {
        return /^[0-9+\-\s()]{10,}$/.test(phone);
    }
    
    function getSelectedSportType() {
        const typeFootball = document.getElementById('type-football');
        const typeVolleyball = document.getElementById('type-volleyball');
        const typeBasketball = document.getElementById('type-basketball');
        const typeHandball = document.getElementById('type-handball');
        const typeBeachvolley = document.getElementById('type-beachvolley');
        
        if (typeFootball && typeFootball.value) return typeFootball.value;
        if (typeVolleyball && typeVolleyball.value) return typeVolleyball.value;
        if (typeBasketball && typeBasketball.value) return typeBasketball.value;
        if (typeHandball && typeHandball.value) return typeHandball.value;
        if (typeBeachvolley && typeBeachvolley.value) return typeBeachvolley.value;
        
        return '5x5'; // default
    }
    
    function updatePlayerRequirements() {
        const selectedType = getSelectedSportType();
        const min = joueursMin[selectedType] || 5;
        const max = joueursMax[selectedType] || 10;
        
        const nombreJoueursRequis = document.getElementById('nombreJoueursRequis');
        if (nombreJoueursRequis) {
            nombreJoueursRequis.innerHTML = `
                <div class="requirement-info">
                    <h4>Composition de l'équipe</h4>
                    <p><strong>Minimum:</strong> ${min} joueurs</p>
                    <p><strong>Maximum:</strong> ${max} joueurs</p>
                    <p class="note">Vous devez inscrire au minimum ${min} joueurs pour pouvoir participer au tournoi.</p>
                </div>
            `;
        }
        
        // Also update any existing info text
        const infoText = document.querySelector('.info-text');
        if (infoText) {
            const span = infoText.querySelector('span');
            if (span) {
                span.textContent = `${min} - ${max} joueurs`;
            }
        }
    }
    
    function addPlayer() {
        playerIndex++;
        const container = document.getElementById('joueursContainer') || document.getElementById('joueurs-container');
        if (!container) return;
        
        // Clear any existing error messages
        container.querySelectorAll('.error-message').forEach(error => error.remove());
        
        const selectedType = getSelectedSportType();
        const maxPlayers = joueursMax[selectedType] || 10;
        const currentPlayers = container.querySelectorAll('.joueur-form, .joueur-item').length;
        
        if (currentPlayers >= maxPlayers) {
            showContainerError(container, `Vous ne pouvez pas ajouter plus de ${maxPlayers} joueurs pour cette catégorie`);
            return;
        }
        
        const playerDiv = document.createElement('div');
        playerDiv.className = 'joueur-form';
        playerDiv.innerHTML = `
            <div class="joueur-header">
                <h4>Joueur ${playerIndex}</h4>
                <button type="button" class="btn-remove-joueur" onclick="removePlayer(this)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
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
        updatePlayerCount();
    }
    
    function toggleCoachFields() {
        const checkbox = document.getElementById('sameAsResponsable');
        const coachFields = document.getElementById('entraineurFields') || document.getElementById('entraineur-fields');
        
        if (!checkbox || !coachFields) return;
        
        const coachInputs = coachFields.querySelectorAll('input, select');
        
        if (checkbox.checked) {
            // Copy manager data to coach fields
            const managerFields = {
                'responsable_nom_complet': 'entraineur_nom_complet',
                'responsable_date_naissance': 'entraineur_date_naissance',
                'responsable_tel': 'entraineur_tel',
                'responsable_whatsapp': 'entraineur_whatsapp'
            };
            
            Object.entries(managerFields).forEach(([managerName, coachName]) => {
                const managerField = document.querySelector(`[name="${managerName}"]`);
                const coachField = document.querySelector(`[name="${coachName}"]`);
                
                if (managerField && coachField && managerField.type !== 'file') {
                    coachField.value = managerField.value;
                    coachField.disabled = true;
                    coachField.required = false;
                }
            });
            
            // Disable file fields
            const coachFileFields = coachFields.querySelectorAll('input[type="file"]');
            coachFileFields.forEach(field => {
                field.disabled = true;
                field.required = false;
            });
            
        } else {
            // Enable all coach fields
            coachInputs.forEach(field => {
                field.disabled = false;
                if (field.hasAttribute('data-originally-required') || 
                    field.closest('.form-group').querySelector('label').textContent.includes('*')) {
                    field.required = true;
                }
            });
        }
    }
    
    function updatePlayerCount() {
        const container = document.getElementById('joueursContainer') || document.getElementById('joueurs-container');
        if (!container) return;
        
        const count = container.querySelectorAll('.joueur-form, .joueur-item').length;
        const countElement = document.getElementById('player-count');
        if (countElement) {
            countElement.textContent = count;
        }
    }
    
    // Form submission handler
    const form = document.getElementById('inscriptionForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateCurrentStep()) {
                e.preventDefault();
                return false;
            }
            
            // Final validation
            const playersCount = document.querySelectorAll('.joueur-form, .joueur-item').length;
            const selectedType = getSelectedSportType();
            const minRequired = joueursMin[selectedType] || 5;
            
            if (playersCount < minRequired) {
                e.preventDefault();
                alert(`Vous devez ajouter au moins ${minRequired} joueurs pour cette catégorie`);
                return false;
            }
        });
    }
});

// Global function for removing players
function removePlayer(button) {
    const playerDiv = button.closest('.joueur-form') || button.closest('.joueur-item');
    if (playerDiv) {
        playerDiv.remove();
        
        // Update player count if element exists
        const countElement = document.getElementById('player-count');
        if (countElement) {
            const container = document.getElementById('joueursContainer') || document.getElementById('joueurs-container');
            const count = container ? container.querySelectorAll('.joueur-form, .joueur-item').length : 0;
            countElement.textContent = count;
        }
    }
}