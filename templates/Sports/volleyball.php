<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User|null $user
 */
?>
<div class="sport-landing volleyball-landing">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Tournoi de Volleyball</h1>
                <p class="hero-subtitle">
                    Formez votre équipe de volleyball (6x6 ou 4x4) et participez au tournoi. 
                    Montrez votre esprit d'équipe, votre technique et votre passion pour le volleyball !
                </p>
                <div class="hero-benefits">
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                        </svg>
                        <span>Matériel et équipements fournis</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        <span>Assurance pour tous les participants</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        <span>Trophées et prix à gagner</span>
                    </div>
                </div>
                <div class="cta-buttons">
                    <?php if ($user): ?>
                        <?= $this->Html->link('Inscrire une équipe', ['controller' => 'Teams', 'action' => 'addVolleyball'], ['class' => 'btn btn-primary']) ?>
                    <?php else: ?>
                        <?= $this->Html->link('S\'inscrire maintenant', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary']) ?>
                        <?= $this->Html->link('Se connecter', ['controller' => 'Users', 'action' => 'login'], ['class' => 'btn btn-secondary']) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <?= $this->Html->image('disciplines/img_sport_volleyball-768x461.png', ['alt' => 'Tournoi de Volleyball']) ?>
            </div>
        </div>
    </section>

    <!-- Section Info -->
    <section class="competitions">
        <div class="container">
            <h2>Informations du tournoi</h2>
            <div class="info-text">
                <p>Le volleyball est un sport passionnant qui allie technique, tactique et esprit d'équipe. Rejoignez-nous pour une compétition enrichissante !</p>
                <ul>
                    <li>Format 6x6 (6-12 joueurs) ou 4x4 (4-8 joueurs)</li>
                    <li>Toutes catégories d'âge acceptées</li>
                    <li>Tournois masculins, féminins et mixtes</li>
                    <li>Compétition par poules puis phases éliminatoires</li>
                    <li>Équipement et matériel fournis</li>
                </ul>
            </div>
            <div class="register-cta">
                <?php if ($user): ?>
                    <?= $this->Html->link('Inscrire mon équipe de volleyball', ['controller' => 'Teams', 'action' => 'addVolleyball'], ['class' => 'btn btn-primary btn-large']) ?>
                <?php else: ?>
                    <?= $this->Html->link('Créer un compte pour s\'inscrire', ['controller' => 'Users', 'action' => 'register'], ['class' => 'btn btn-primary btn-large']) ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
    :root {
        --primary-color: #3498DB;
        --secondary-color: #5DADE2;
        --text-dark: #2D3436;
        --text-light: #636E72;
        --white: #FFFFFF;
    }

    .volleyball-landing {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-dark);
    }

    .hero {
        padding: 4rem 0;
        background: linear-gradient(135deg, #EBF5FB 0%, #D6EAF8 100%);
        min-height: 70vh;
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
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: var(--text-light);
        margin-bottom: 2rem;
        line-height: 1.8;
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
    }

    .btn-primary {
        background: var(--primary-color);
        color: var(--white);
    }

    .btn-secondary {
        background: var(--white);
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .hero-image img {
        width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .competitions {
        padding: 5rem 0;
        background: var(--white);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .competitions h2 {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
    }

    .info-text {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 3rem;
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .info-text ul {
        list-style: none;
        margin-top: 2rem;
    }

    .info-text li {
        padding: 0.5rem 0;
        position: relative;
        padding-left: 30px;
    }

    .info-text li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--primary-color);
        font-weight: bold;
    }

    .register-cta {
        text-align: center;
    }

    .btn-primary.large {
        font-size: 1.125rem;
        padding: 16px 40px;
    }

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
    }
</style>