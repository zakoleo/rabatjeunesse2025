<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User|null $user
 */
?>
<div class="sport-landing football-landing">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Tournoi de Football</h1>
                <p class="hero-subtitle">
                    Vous êtes de jeunes talents passionnés de football ? 
                    Avec Rabat Jeunesse, venez exprimer votre savoir-faire et gagner plein de lots intéressants.
                </p>
                <div class="hero-benefits">
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                        </svg>
                        <span>Tenues et équipements sportifs gratuits</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <span>Assurance tout au long des compétitions</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        <span>Des prix exceptionnels pour les gagnants</span>
                    </div>
                </div>
                <div class="cta-buttons">
                    <?php if ($user): ?>
                        <?= $this->Html->link('Inscrire une équipe', ['controller' => 'Teams', 'action' => 'add'], ['class' => 'btn btn-primary btn-large']) ?>
                    <?php else: ?>
                        <?= $this->Html->link('Créer un compte pour inscrire une équipe', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-large']) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <?= $this->Html->image('disciplines/img_sport_football.png', ['alt' => 'Tournoi de Football']) ?>
            </div>
        </div>
    </section>

    <!-- Section Compétitions -->
    <section class="competitions" id="competitions">
        <div class="container">
            <h2>Catégories du Tournoi</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </div>
                    <h3>Catégories d'âge</h3>
                    <ul>
                        <li>U12 (Moins de 12 ans)</li>
                        <li>U15 (12-15 ans)</li>
                        <li>U18 (15-18 ans)</li>
                        <li>18+ (18 ans et plus)</li>
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
                    <h3>Genres</h3>
                    <ul>
                        <li>Masculin</li>
                        <li>Féminin</li>
                    </ul>
                </div>
                <div class="category-card">
                    <div class="card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3>Types de Football</h3>
                    <ul>
                        <li>Football à 5 (5x5) - U18 et 18+</li>
                        <li>Football à 6 (6x6) - U12 et U15</li>
                        <li>Football à 11 (11x11) - U18 et 18+</li>
                    </ul>
                    <p style="margin-top: 1rem; font-size: 0.9rem; color: var(--text-light); font-style: italic;">
                        Note: Les catégories U12 et U15 participent uniquement au football à 6.<br>
                        Les catégories U18 et 18+ peuvent choisir entre football à 5 et football à 11.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Comment s'inscrire -->
    <section class="how-to-register" id="participer">
        <div class="container">
            <h2>Comment s'inscrire ?</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Créez votre compte</h3>
                    <p><?= $user ? 'Vous êtes déjà connecté' : 'Créez un compte pour gérer vos inscriptions' ?></p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Préparez vos documents</h3>
                    <p>Rassemblez les copies CIN du responsable et de l'entraîneur, ainsi que les informations des joueurs</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Remplissez le formulaire</h3>
                    <p>Complétez toutes les informations requises sur l'équipe, les encadrants et les joueurs</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Validez votre inscription</h3>
                    <p>Vérifiez vos informations et soumettez votre inscription pour participer au tournoi</p>
                </div>
            </div>
            <div class="register-cta">
                <p class="cta-text">Qui sait ? Peut-être que vous serez l'un des gagnants du tournoi !</p>
                <?php if ($user): ?>
                    <?= $this->Html->link('Inscrire une équipe', ['controller' => 'Teams', 'action' => 'add'], ['class' => 'btn btn-primary btn-large']) ?>
                <?php else: ?>
                    <?= $this->Html->link('Créer un compte pour inscrire une équipe', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-large']) ?>
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
        --primary-color: #5956E9;
        --secondary-color: #FFB84D;
        --accent-color: #FF6B6B;
        --text-dark: #2D3436;
        --text-light: #636E72;
        --background-light: #F5F6FA;
        --white: #FFFFFF;
    }

    .sport-landing {
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
        background: linear-gradient(135deg, #F5F6FA 0%, #E8E9F3 100%);
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

    .steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .step {
        text-align: center;
        position: relative;
        padding: 2rem;
    }

    .step-number {
        display: inline-block;
        width: 60px;
        height: 60px;
        line-height: 60px;
        background: var(--primary-color);
        color: var(--white);
        border-radius: 50%;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .step h3 {
        font-size: 1.25rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .step p {
        color: var(--text-light);
        line-height: 1.6;
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

        .steps {
            grid-template-columns: 1fr;
        }
    }
</style>