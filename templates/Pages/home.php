<?php
/**
 * @var \App\View\AppView $this
 */

$this->assign('title', 'Accueil');
?>

<div class="home-page">
    <section class="hero-section">
        <h1>Bienvenue sur Rabat Jeunesse</h1>
        <p class="lead">Plateforme d'inscription aux tournois sportifs de Rabat</p>
        
        <?php if (!$this->request->getAttribute('identity')): ?>
            <div class="cta-buttons">
                <?= $this->Html->link('S\'inscrire', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-lg']) ?>
                <?= $this->Html->link('Se connecter', ['controller' => 'Users', 'action' => 'login'], ['class' => 'btn btn-secondary btn-lg']) ?>
            </div>
        <?php else: ?>
            <div class="cta-buttons">
                <?= $this->Html->link('Accéder au tableau de bord', ['controller' => 'Users', 'action' => 'dashboard'], ['class' => 'btn btn-primary btn-lg']) ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="sports-section">
        <h2>Tournois disponibles</h2>
        <div class="sports-grid">
            <div class="sport-card">
                <h3>Football</h3>
                <ul>
                    <li>Football à 5 (5x5)</li>
                    <li>Football à 6 (6x6)</li>
                    <li>Football à 11 (11x11)</li>
                </ul>
                <p>Catégories : U12, U15, U18, +18 ans</p>
            </div>
            
            <div class="sport-card">
                <h3>Handball</h3>
                <p>7 joueurs minimum, 10 maximum</p>
                <p>Toutes catégories d'âge</p>
            </div>
            
            <div class="sport-card">
                <h3>Basketball</h3>
                <ul>
                    <li>Basket à 5 : 5-8 joueurs</li>
                    <li>Basket à 3 : 3-4 joueurs</li>
                </ul>
                <p>Catégories : -15, -17, -21, +21 ans</p>
            </div>
            
            <div class="sport-card">
                <h3>Volleyball</h3>
                <p>6 joueurs minimum, 10 maximum</p>
                <p>Catégories : -15, -17, -19 ans</p>
            </div>
            
            <div class="sport-card">
                <h3>Beach-volley</h3>
                <p>2 joueurs par équipe</p>
                <p>Catégories : -17, -21, +21 ans</p>
            </div>
        </div>
    </section>

    <section class="info-section">
        <h2>Comment ça marche ?</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h4>Créez un compte</h4>
                <p>Inscrivez-vous gratuitement sur la plateforme</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h4>Choisissez votre sport</h4>
                <p>Sélectionnez le tournoi qui vous intéresse</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h4>Inscrivez votre équipe</h4>
                <p>Remplissez le formulaire avec les informations de votre équipe</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h4>Gérez vos inscriptions</h4>
                <p>Consultez et modifiez vos équipes depuis votre tableau de bord</p>
            </div>
        </div>
    </section>
</div>

<style>
    .home-page {
        padding: 2rem 0;
    }
    
    .hero-section {
        text-align: center;
        padding: 4rem 0;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 3rem;
    }
    
    .hero-section h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }
    
    .lead {
        font-size: 1.25rem;
        color: #6c757d;
        margin-bottom: 2rem;
    }
    
    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .btn {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s;
    }
    
    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
    }
    
    .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
        color: white;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #545b62;
        color: white;
    }
    
    .sports-section {
        margin: 4rem 0;
    }
    
    .sports-section h2 {
        text-align: center;
        margin-bottom: 3rem;
        color: #2c3e50;
    }
    
    .sports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .sport-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .sport-card h3 {
        color: #007bff;
        margin-bottom: 1rem;
    }
    
    .sport-card ul {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }
    
    .sport-card p {
        color: #6c757d;
        margin: 0.5rem 0;
    }
    
    .info-section {
        margin: 4rem 0;
        text-align: center;
    }
    
    .info-section h2 {
        margin-bottom: 3rem;
        color: #2c3e50;
    }
    
    .steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }
    
    .step {
        text-align: center;
    }
    
    .step-number {
        width: 60px;
        height: 60px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0 auto 1rem;
    }
    
    .step h4 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .step p {
        color: #6c757d;
    }
</style>