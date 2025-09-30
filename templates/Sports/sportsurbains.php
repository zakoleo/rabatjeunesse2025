<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User|null $user
 */

$sportConfig = [
    'name' => 'Sports Urbains',
    'image' => 'img_sport_urbains-768x461.png',
    'primaryColor' => '#FF6B35',
    'secondaryColor' => '#F7931E',
    'backgroundLight' => '#FFF5F0',
    'gradientStart' => '#FFFAF8',
    'gradientEnd' => '#FFEDE5',
    'subtitle' => 'Exprimez votre créativité avec les Sports Urbains ! Un sport individuel qui combine agilité, style et technique.',
    'formats' => [
        [
            'title' => 'Format individuel',
            'items' => ['Compétition individuelle', 'Disciplines variées', 'Expression libre et créativité']
        ],
        [
            'title' => 'Catégories',
            'items' => ['U18 Homme et Femme', '18+ Homme et Femme', 'Tous niveaux acceptés']
        ],
        [
            'title' => 'Disciplines',
            'items' => ['Skateboard', 'BMX Freestyle', 'Parkour et Breakdance']
        ]
    ]
];
?>
<div class="sport-landing sportsurbains-landing">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Tournoi de Sports Urbains</h1>
                <p class="hero-subtitle">
                    Exprimez votre créativité avec les Sports Urbains ! 
                    Un sport individuel qui combine agilité, style et technique.
                </p>
                <div class="hero-benefits">
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"></path>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                        </svg>
                        <span>Équipements fournis</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        <span>Prix pour toutes les catégories</span>
                    </div>
                    <div class="benefit">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Compétition individuelle</span>
                    </div>
                </div>
                <div class="hero-cta">
                    <?php if ($user): ?>
                        <?= $this->Html->link(
                            'S\'inscrire maintenant', 
                            ['controller' => 'Sportsurbains', 'action' => 'add'],
                            ['class' => 'btn btn-primary btn-large']
                        ) ?>
                    <?php else: ?>
                        <?= $this->Html->link(
                            'Se connecter pour s\'inscrire', 
                            ['controller' => 'Users', 'action' => 'login'],
                            ['class' => 'btn btn-primary btn-large']
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <?= $this->Html->image('disciplines/img_sport_urbains-768x461.png', [
                    'alt' => 'Sports Urbains',
                    'class' => 'sport-hero-img'
                ]) ?>
            </div>
        </div>
    </section>

    <!-- Formats Section -->
    <section class="formats-section">
        <div class="container">
            <h2>Formats de compétition</h2>
            <div class="formats-grid">
                <?php foreach ($sportConfig['formats'] as $format): ?>
                    <div class="format-card">
                        <h3><?= h($format['title']) ?></h3>
                        <ul>
                            <?php foreach ($format['items'] as $item): ?>
                                <li><?= h($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="info-section">
        <div class="container">
            <div class="info-grid">
                <div class="info-card">
                    <h3>Inscription individuelle</h3>
                    <p>Les Sports Urbains sont un sport individuel. Chaque participant s'inscrit personnellement et concourt dans sa catégorie d'âge et de genre.</p>
                </div>
                <div class="info-card">
                    <h3>Catégories disponibles</h3>
                    <p>4 catégories au total : U18 Homme, U18 Femme, 18+ Homme et 18+ Femme. Trouvez votre catégorie et montrez vos talents !</p>
                </div>
                <div class="info-card">
                    <h3>Sécurité garantie</h3>
                    <p>Tous les équipements de protection sont fournis. Notre priorité est votre sécurité tout en vous permettant d'exprimer votre créativité.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Prêt à montrer vos talents ?</h2>
            <p>Inscrivez-vous dès maintenant et rejoignez la communauté des sports urbains !</p>
            <?php if ($user): ?>
                <div class="cta-buttons">
                    <?= $this->Html->link(
                        'S\'inscrire aux Sports Urbains', 
                        ['controller' => 'Sportsurbains', 'action' => 'add'],
                        ['class' => 'btn btn-primary btn-large']
                    ) ?>
                    <?= $this->Html->link(
                        'Mes inscriptions', 
                        ['controller' => 'Sportsurbains', 'action' => 'index'],
                        ['class' => 'btn btn-secondary btn-large']
                    ) ?>
                </div>
            <?php else: ?>
                <?= $this->Html->link(
                    'Se connecter pour participer', 
                    ['controller' => 'Users', 'action' => 'login'],
                    ['class' => 'btn btn-primary btn-large']
                ) ?>
            <?php endif; ?>
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
        --primary-color: <?= $sportConfig['primaryColor'] ?>;
        --secondary-color: <?= $sportConfig['secondaryColor'] ?>;
        --accent-color: #FF6B6B;
        --text-dark: #2D3436;
        --text-light: #636E72;
        --background-light: <?= $sportConfig['backgroundLight'] ?>;
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
        background: linear-gradient(135deg, <?= $sportConfig['gradientStart'] ?> 0%, <?= $sportConfig['gradientEnd'] ?> 100%);
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

    .hero-cta {
        margin-top: 2rem;
    }

    /* Override only colors for sportsurbains */
    .sportsurbains-landing .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .sportsurbains-landing .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .sportsurbains-landing .btn-secondary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .sportsurbains-landing .btn-secondary:hover {
        background-color: var(--primary-color);
    }

    .hero-image {
        text-align: center;
    }

    .hero-image img, .sport-hero-img {
        width: 100%;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    /* Formats Section */
    .formats-section {
        padding: 5rem 0;
        background: var(--white);
    }

    .formats-section h2 {
        text-align: center;
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 3rem;
    }

    .formats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .format-card {
        background: var(--background-light);
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        transition: transform 0.3s;
    }

    .format-card:hover {
        transform: translateY(-5px);
    }

    .format-card h3 {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .format-card ul {
        list-style: none;
        text-align: left;
        max-width: 250px;
        margin: 0 auto;
    }

    .format-card li {
        padding: 0.5rem 0;
        color: var(--text-dark);
        position: relative;
        padding-left: 20px;
    }

    .format-card li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--primary-color);
    }

    /* Info Section */
    .info-section {
        padding: 5rem 0;
        background: var(--background-light);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .info-card {
        text-align: center;
        padding: 2rem;
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .info-card h3 {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .info-card p {
        color: var(--text-light);
        line-height: 1.6;
    }

    /* CTA Section */
    .cta-section {
        padding: 5rem 0;
        background: var(--white);
        text-align: center;
    }

    .cta-section h2 {
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .cta-section p {
        font-size: 1.25rem;
        color: var(--text-light);
        margin-bottom: 2rem;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
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

        .hero-cta, .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .hero-image {
            margin-top: 2rem;
        }

        .formats-grid, .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>