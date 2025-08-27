document.addEventListener('DOMContentLoaded', function() {
    // Basic elements
    const inscriptionForm = document.getElementById('inscriptionForm');
    const nombreJoueursRequis = document.getElementById('nombreJoueursRequis');
    const joueursContainer = document.getElementById('joueursContainer');
    const ajouterJoueurBtn = document.getElementById('ajouterJoueur');
    
    let joueurCount = 0;
    const joueursMin = {
        '3x3': 3,
        '5x5': 5
    };
    
    const joueursMax = {
        '3x3': 4,
        '5x5': 8
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
        console.log('Showing step:', step);
        
        // Hide all steps
        wizardSteps.forEach((wizardStep, index) => {
            wizardStep.classList.remove('active');
            if (progressSteps[index]) {
                progressSteps[index].classList.remove('active', 'completed');
            }
        });
        
        // Show current step
        const currentWizardStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (currentWizardStep) {
            currentWizardStep.classList.add('active');
        }
        
        // Update progress
        for (let i = 0; i < step; i++) {
            if (progressSteps[i]) {
                if (i < step - 1) {
                    progressSteps[i].classList.add('completed');
                } else {
                    progressSteps[i].classList.add('active');
                }
            }
        }
        
        // Update navigation buttons
        if (prevBtn) prevBtn.style.display = step > 1 ? 'inline-block' : 'none';
        if (nextBtn) nextBtn.style.display = step < totalSteps ? 'inline-block' : 'none';
        if (submitBtn) submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
    }
    
    function validateStep(step) {
        console.log('Validating step:', step);
        const currentWizardStep = document.querySelector(`.wizard-step[data-step="${step}"]`);
        if (!currentWizardStep) return false;
        
        // Clear previous error messages
        currentWizardStep.querySelectorAll('.error-message').forEach(msg => msg.remove());
        currentWizardStep.querySelectorAll('.error').forEach(field => field.classList.remove('error'));
        
        let isValid = true;
        
        // Validate required fields in current step
        const requiredFields = currentWizardStep.querySelectorAll('[required]:not(:disabled)');
        requiredFields.forEach(field => {
            if (!field.value || field.value.trim() === '') {
                field.classList.add('error');
                isValid = false;
                console.log('Required field empty:', field.name || field.id);
            }
        });
        
        if (!isValid) {
            alert('Veuillez remplir tous les champs requis');
            return false;
        }
        
        // Step-specific validation
        if (step === 1) {
            // Find type field and update player requirements
            const typeField = currentWizardStep.querySelector('select[name="type_basketball"]');
            if (typeField && typeField.value) {
                updateJoueursRequirement(typeField.value);
            }
        }
        
        if (step === 3) {
            // Validate minimum players
            const typeField = document.querySelector('select[name="type_basketball"]');
            const type = typeField ? typeField.value : null;
            const currentPlayerCount = joueursContainer ? joueursContainer.children.length : 0;
            
            if (type && joueursMin[type] && currentPlayerCount < joueursMin[type]) {
                alert(`Vous devez ajouter au minimum ${joueursMin[type]} joueurs pour le ${type}`);
                return false;
            }
        }
        
        return true;
    }
    
    // Navigation event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            console.log('Next button clicked, current step:', currentStep);
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            console.log('Previous button clicked, current step:', currentStep);
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }
    
    // Basketball type change handler
    function updateJoueursRequirement(type) {
        if (!nombreJoueursRequis) return;
        
        if (type && joueursMin[type] && joueursMax[type]) {
            const min = joueursMin[type];
            const max = joueursMax[type];
            nombreJoueursRequis.innerHTML = `
                <div class="requirement-info">
                    <h4>Composition pour ${type}</h4>
                    <p><strong>Minimum:</strong> ${min} joueurs</p>
                    <p><strong>Maximum:</strong> ${max} joueurs</p>
                    <p class="note">Pour le ${type}, vous devez inscrire entre ${min} et ${max} joueurs.</p>
                </div>
            `;
            nombreJoueursRequis.className = 'info-alert success';
        } else {
            nombreJoueursRequis.innerHTML = `<p>Veuillez d'abord sélectionner le type de basketball.</p>`;
            nombreJoueursRequis.className = 'info-alert warning';
        }
    }
    
    // Type field change listener
    const typeField = document.querySelector('select[name="type_basketball"]');
    if (typeField) {
        typeField.addEventListener('change', function() {
            const selectedType = this.value;
            console.log('Type changed to:', selectedType);
            updateJoueursRequirement(selectedType);
            
            // Clear existing players when changing type
            if (joueursContainer) {
                while (joueursContainer.firstChild) {
                    joueursContainer.removeChild(joueursContainer.firstChild);
                }
                joueurCount = 0;
                
                // Add minimum required players
                if (selectedType && joueursMin[selectedType]) {
                    for (let i = 0; i < joueursMin[selectedType]; i++) {
                        ajouterJoueur();
                    }
                }
            }
        });
    }
    
    function ajouterJoueur() {
        const typeField = document.querySelector('select[name="type_basketball"]');
        const typeValue = typeField ? typeField.value : null;
        
        if (!typeValue) {
            alert('Veuillez d\'abord sélectionner le type de basketball.');
            return;
        }
        
        if (typeValue && joueursMax[typeValue] && joueurCount >= joueursMax[typeValue]) {
            alert(`Vous ne pouvez pas ajouter plus de ${joueursMax[typeValue]} joueurs pour le ${typeValue}.`);
            return;
        }
        
        const joueurDiv = document.createElement('div');
        joueurDiv.className = 'joueur-item';
        joueurDiv.innerHTML = `
            <div class="joueur-header">
                <h4>Joueur ${joueurCount + 1}</h4>
                <button type="button" class="btn-remove" onclick="supprimerJoueur(this)">
                    Supprimer
                </button>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" name="joueurs[${joueurCount}][nom_complet]" required 
                           placeholder="Nom et prénom du joueur">
                </div>
                <div class="form-group">
                    <label>Date de naissance *</label>
                    <input type="date" name="joueurs[${joueurCount}][date_naissance]" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Identifiant (CIN/Passeport) *</label>
                    <input type="text" name="joueurs[${joueurCount}][identifiant]" required 
                           placeholder="Ex: AB123456">
                </div>
                <div class="form-group">
                    <label>Taille vestimentaire *</label>
                    <select name="joueurs[${joueurCount}][taille_vestimentaire]" required>
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
        
        if (joueursContainer) {
            joueursContainer.appendChild(joueurDiv);
            joueurCount++;
            updateRemoveButtons();
        }
    }
    
    function updateRemoveButtons() {
        const typeField = document.querySelector('select[name="type_basketball"]');
        const typeValue = typeField ? typeField.value : null;
        const removeButtons = document.querySelectorAll('.btn-remove');
        
        removeButtons.forEach((btn, index) => {
            if (typeValue && joueursMin[typeValue] && index < joueursMin[typeValue]) {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'flex';
            }
        });
    }
    
    // Global function for removing players
    window.supprimerJoueur = function(button) {
        const joueurForm = button.closest('.joueur-item');
        if (joueurForm) {
            joueurForm.remove();
            joueurCount--;
            
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
        }
    };
    
    // Add player button event listener
    if (ajouterJoueurBtn) {
        ajouterJoueurBtn.addEventListener('click', ajouterJoueur);
    }
    
    // Coach same as manager checkbox handler
    const entraineurSameCheckbox = document.getElementById('sameAsResponsable');
    const entraineurFields = document.getElementById('entraineurFields');
    
    if (entraineurSameCheckbox && entraineurFields) {
        entraineurSameCheckbox.addEventListener('change', function() {
            if (this.checked) {
                entraineurFields.style.display = 'none';
                const coachFields = entraineurFields.querySelectorAll('input, select, textarea');
                coachFields.forEach(field => {
                    field.removeAttribute('required');
                });
            } else {
                entraineurFields.style.display = 'block';
                const coachFields = entraineurFields.querySelectorAll('input, select, textarea');
                coachFields.forEach(field => {
                    if (field.type !== 'file') {
                        field.setAttribute('required', 'required');
                    }
                });
            }
        });
    }
    
    // Form submission handler
    if (inscriptionForm) {
        inscriptionForm.addEventListener('submit', function(e) {
            console.log('Form submission attempted');
            
            const typeField = document.querySelector('select[name="type_basketball"]');
            const typeValue = typeField ? typeField.value : null;
            const currentPlayerCount = joueursContainer ? joueursContainer.children.length : 0;
            
            if (typeValue && joueursMin[typeValue] && currentPlayerCount < joueursMin[typeValue]) {
                e.preventDefault();
                alert(`Vous devez avoir au moins ${joueursMin[typeValue]} joueurs pour le ${typeValue}.`);
                return false;
            }
            
            if (typeValue && joueursMax[typeValue] && currentPlayerCount > joueursMax[typeValue]) {
                e.preventDefault();
                alert(`Vous ne pouvez pas avoir plus de ${joueursMax[typeValue]} joueurs pour le ${typeValue}.`);
                return false;
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = 'Inscription en cours...';
            }
        });
    }
    
    // Initialize first step
    showStep(1);
    
    // Initialize requirements display
    const initialTypeField = document.querySelector('select[name="type_basketball"]');
    if (initialTypeField) {
        updateJoueursRequirement(initialTypeField.value);
    }
    
    console.log('Basketball form script loaded successfully');
});