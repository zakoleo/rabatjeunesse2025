<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User|null $user
 */

// Sport-specific configuration
$sportConfig = [
    'name' => 'Basketball',
    'image' => 'img_sport_basket.png',
    'primaryColor' => '#FF6B35',
    'secondaryColor' => '#F7931E',
    'backgroundLight' => '#FFF5F0',
    'gradientStart' => '#FFF5F0',
    'gradientEnd' => '#FFE5D9',
    'subtitle' => 'Montrez vos talents sur le terrain de basketball ! Participez aux compétitions 3x3 ou 5x5 et remportez des prix exceptionnels.',
    'formats' => [
        [
            'title' => 'Basketball 3x3',
            'items' => ['Équipe de 3 joueurs', 'Matches rapides et intenses', 'Format streetball']
        ],
        [
            'title' => 'Basketball 5x5',
            'items' => ['Équipe de 5 joueurs', 'Format classique', 'Matches complets']
        ],
        [
            'title' => 'Catégories',
            'items' => ['Toutes les catégories d\'âge', 'Masculin et Féminin', 'Niveaux débutant à confirmé']
        ]
    ]
];
?>
<div class="sport-landing basketball-landing">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Tournoi de Basketball</h1>
                <p class="hero-subtitle">
                    Montrez vos talents sur le terrain de basketball ! 
                    Participez aux compétitions 3x3 ou 5x5 et remportez des prix exceptionnels.
                </p>
                <div class="hero-benefits">
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                        </svg>
                        <span>Équipements sportifs fournis</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <span>Assurance complète pendant le tournoi</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        <span>Trophées et récompenses pour les vainqueurs</span>
                    </div>
                </div>
                <div class="cta-buttons">
                    <?php if ($user): ?>
                        <?= $this->Html->link('Inscrire une équipe', ['controller' => 'Teams', 'action' => 'add'], ['class' => 'btn btn-primary']) ?>
                    <?php else: ?>
                        <?= $this->Html->link('S\'inscrire maintenant', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary']) ?>
                        <?= $this->Html->link('Se connecter', ['controller' => 'Users', 'action' => 'login'], ['class' => 'btn btn-secondary']) ?>
                    <?php endif; ?>
                    <a href="#competitions" class="btn-secondary">En savoir plus</a>
                </div>
            </div>
            <div class="hero-image">
                <?= $this->Html->image('disciplines/img_sport_basket.png', ['alt' => 'Tournoi de Basketball']) ?>
            </div>
        </div>
    </section>

    <!-- Section Compétitions -->
    <section class="competitions" id="competitions">
        <div class="container">
            <h2>Formats de Compétition</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                    <h3>Basketball 3x3</h3>
                    <ul>
                        <li>Équipe de 3 joueurs</li>
                        <li>Matches rapides et intenses</li>
                        <li>Format streetball</li>
                    </ul>
                </div>
                <div class="category-card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <path d="M20 8v6M23 11h-6"></path>
                        </svg>
                    </div>
                    <h3>Basketball 5x5</h3>
                    <ul>
                        <li>Équipe de 5 joueurs</li>
                        <li>Format classique</li>
                        <li>Matches complets</li>
                    </ul>
                </div>
                <div class="category-card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3>Catégories</h3>
                    <ul>
                        <li>Toutes les catégories d'âge</li>
                        <li>Masculin et Féminin</li>
                        <li>Niveaux débutant à confirmé</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Comment s'inscrire -->
    <section class="how-to-register" id="participer">
        <div class="container">
            <h2>Inscrivez votre équipe</h2>
            <div class="register-cta">
                <p class="cta-text">Prêt à montrer vos talents sur le terrain ?</p>
                <?php if ($user): ?>
                    <?= $this->Html->link('Inscrire mon équipe de basketball', ['controller' => 'Teams', 'action' => 'add'], ['class' => 'btn btn-primary btn-large']) ?>
                <?php else: ?>
                    <?= $this->Html->link('Créer un compte pour s\'inscrire', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-large']) ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --primary-color: #FF6B35;
        --secondary-color: #F7931E;
        --accent-color: #FFB84D;
        --text-dark: #2D3436;
        --text-light: #636E72;
        --background-light: #FFF5F0;
        --white: #FFFFFF;
    }

    .basketball-landing {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        color: var(--text-dark);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Hero Section */
    .hero {
        padding: 4rem 0;
        background: linear-gradient(135deg, #FFF5F0 0%, #FFE5D9 100%);
        min-height: 80vh;
        display: flex;
        align-items: center;
    }

    .hero-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .hero-text h1 {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: var(--text-light);
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .hero-benefits {
        margin-bottom: 2rem;
    }

    .benefit {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .icon {
        width: 24px;
        height: 24px;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary, .btn-secondary {
        display: inline-block;
        padding: 14px 32px;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        text-align: center;
    }

    .btn-primary {
        background: var(--primary-color);
        color: var(--white);
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    }

    .btn-primary:hover {
        background: #E55A2B;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-secondary:hover {
        background: var(--primary-color);
        color: var(--white);
    }

    .hero-image img {
        width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    /* Competitions Section */
    .competitions {
        padding: 5rem 0;
        background: var(--white);
    }

    .competitions h2 {
        text-align: center;
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 3rem;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .category-card {
        background: var(--background-light);
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        transition: transform 0.3s;
    }

    .category-card:hover {
        transform: translateY(-5px);
    }

    .card-icon {
        display: inline-block;
        width: 60px;
        height: 60px;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .category-card h3 {
        font-size: 1.5rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .category-card ul {
        list-style: none;
        text-align: left;
        max-width: 250px;
        margin: 0 auto;
    }

    .category-card li {
        padding: 0.5rem 0;
        color: var(--text-light);
        position: relative;
        padding-left: 20px;
    }

    .category-card li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--primary-color);
    }

    /* How to Register Section */
    .how-to-register {
        padding: 5rem 0;
        background: var(--background-light);
    }

    .how-to-register h2 {
        text-align: center;
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 3rem;
    }

    .register-cta {
        text-align: center;
        padding: 2rem;
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .cta-text {
        font-size: 1.25rem;
        color: var(--text-dark);
        margin-bottom: 2rem;
    }

    .btn-primary.large {
        font-size: 1.125rem;
        padding: 16px 40px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-content {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .hero-text h1 {
            font-size: 2rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .hero-image {
            margin-top: 2rem;
        }
    }
</style>