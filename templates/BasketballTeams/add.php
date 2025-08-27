<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BasketballTeam $basketballTeam
 */

$this->assign('title', 'Inscription - Équipe de Basketball');
?>

<div class="basketball-registration">
    <div class="form-header">
        <h1>Inscription d'une équipe de Basketball</h1>
        <p class="subtitle">Complétez le formulaire ci-dessous pour inscrire votre équipe au tournoi</p>
    </div>

    <?= $this->Form->create($basketballTeam, ['class' => 'registration-form']) ?>
    
    <div class="form-sections">
        <!-- Section Informations de l'équipe -->
        <div class="form-section">
            <h2>Informations de l'équipe</h2>
            <div class="form-grid">
                <div class="form-group">
                    <?= $this->Form->control('nom_equipe', [
                        'label' => 'Nom de l\'équipe *',
                        'class' => 'form-control',
                        'placeholder' => 'Ex: Eagles Basketball'
                    ]) ?>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('type_basketball', [
                        'label' => 'Type de Basketball *',
                        'type' => 'select',
                        'class' => 'form-control',
                        'options' => [
                            '3x3' => 'Basketball 3x3 (3 joueurs)',
                            '5x5' => 'Basketball 5x5 (5 joueurs)'
                        ],
                        'empty' => 'Sélectionnez le type'
                    ]) ?>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('categorie', [
                        'label' => 'Catégorie d\'âge *',
                        'type' => 'select',
                        'class' => 'form-control',
                        'options' => [
                            '-15' => 'Moins de 15 ans',
                            '-17' => 'Moins de 17 ans', 
                            '-21' => 'Moins de 21 ans',
                            '+21' => 'Plus de 21 ans'
                        ],
                        'empty' => 'Sélectionnez la catégorie'
                    ]) ?>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('genre', [
                        'label' => 'Genre *',
                        'type' => 'select',
                        'class' => 'form-control',
                        'options' => [
                            'Masculin' => 'Masculin',
                            'Féminin' => 'Féminin',
                            'Mixte' => 'Mixte'
                        ],
                        'empty' => 'Sélectionnez le genre'
                    ]) ?>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('district', [
                        'label' => 'District *',
                        'class' => 'form-control',
                        'placeholder' => 'Ex: Agdal-Ryad'
                    ]) ?>
                </div>
                
                <div class="form-group">
                    <?= $this->Form->control('organisation', [
                        'label' => 'Organisation *',
                        'class' => 'form-control',
                        'placeholder' => 'Ex: Association sportive'
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group full-width">
                <?= $this->Form->control('adresse', [
                    'type' => 'textarea',
                    'label' => 'Adresse complète *',
                    'class' => 'form-control',
                    'placeholder' => 'Adresse complète de l\'équipe ou du responsable',
                    'rows' => 3
                ]) ?>
            </div>
        </div>

        <!-- Section Informations du tournoi -->
        <div class="form-section info-section">
            <h3>Informations importantes</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Basketball 3x3</h4>
                    <ul>
                        <li>3-4 joueurs par équipe</li>
                        <li>Matches rapides et intenses</li>
                        <li>Format streetball</li>
                    </ul>
                </div>
                <div class="info-card">
                    <h4>Basketball 5x5</h4>
                    <ul>
                        <li>5-8 joueurs par équipe</li>
                        <li>Format traditionnel</li>
                        <li>Matches complets</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <?= $this->Form->button('Inscrire l\'équipe', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('Annuler', ['controller' => 'Sports', 'action' => 'basketball'], ['class' => 'btn btn-secondary']) ?>
    </div>
    
    <?= $this->Form->end() ?>
</div>

<style>
    .basketball-registration {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .form-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .form-header h1 {
        color: #FF6B35;
        margin-bottom: 1rem;
        font-size: 2.5rem;
    }

    .subtitle {
        color: #666;
        font-size: 1.1rem;
    }

    .registration-form {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #eee;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section h2 {
        color: #FF6B35;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #FF6B35;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        border-color: #FF6B35;
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .info-section {
        background: #FFF5F0;
    }

    .info-section h3 {
        color: #FF6B35;
        margin-bottom: 1rem;
        text-align: center;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .info-card h4 {
        color: #FF6B35;
        margin-bottom: 1rem;
        text-align: center;
    }

    .info-card ul {
        list-style: none;
        padding: 0;
    }

    .info-card li {
        padding: 0.25rem 0;
        position: relative;
        padding-left: 20px;
    }

    .info-card li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #FF6B35;
        font-weight: bold;
    }

    .form-actions {
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        padding: 12px 32px;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-block;
    }

    .btn-primary {
        background: #FF6B35;
        color: white;
    }

    .btn-primary:hover {
        background: #E55A2B;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #545b62;
        color: white;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .basketball-registration {
            padding: 1rem;
        }

        .form-header h1 {
            font-size: 2rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
            align-items: center;
        }
    }
</style>