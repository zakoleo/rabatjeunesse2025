<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User|null $user
 */

// Get the contest type from the current action
$contestType = $this->request->getParam('action');

// Define contest configurations
$contestConfigs = [
    'dessin' => [
        'name' => 'Concours de Dessin',
        'type' => 'Dessin',
        'image' => 'img_dessin-768x461.png',
        'primaryColor' => '#E74C3C',
        'secondaryColor' => '#C0392B',
        'backgroundLight' => '#FADBD8',
        'gradientStart' => '#FFF5F5',
        'gradientEnd' => '#FFE6E6',
        'subtitle' => 'Laissez libre cours à votre créativité artistique et participez à notre concours de dessin.',
        'description' => 'Exprimez votre vision artistique à travers le dessin. Que ce soit au crayon, à l\'encre, au fusain ou aux pastels, montrez-nous votre talent !',
        'requirements' => [
            'Techniques acceptées' => ['Crayon', 'Encre', 'Fusain', 'Pastels', 'Techniques mixtes'],
            'Format' => ['A4 minimum', 'A2 maximum'],
            'Critères d\'évaluation' => ['Créativité', 'Technique', 'Originalité', 'Message artistique']
        ]
    ],
    'chanson' => [
        'name' => 'Concours de Chanson',
        'type' => 'Chanson',
        'image' => 'chansson-768x461.png',
        'primaryColor' => '#3498DB',
        'secondaryColor' => '#2980B9',
        'backgroundLight' => '#D6EAF8',
        'gradientStart' => '#F5F9FF',
        'gradientEnd' => '#E6F2FF',
        'subtitle' => 'Partagez votre passion pour la musique et faites entendre votre voix.',
        'description' => 'Que vous soyez chanteur solo, auteur-compositeur ou interprète, ce concours est votre scène pour briller !',
        'requirements' => [
            'Styles acceptés' => ['Tous styles musicaux', 'Chanson originale ou reprise'],
            'Durée' => ['3 minutes minimum', '5 minutes maximum'],
            'Critères d\'évaluation' => ['Qualité vocale', 'Présence scénique', 'Émotion', 'Originalité']
        ]
    ],
    'commentateur' => [
        'name' => 'Concours de Commentateur Sportif',
        'type' => 'Commentateur sportif',
        'image' => 'entreneur-768x461.png',
        'primaryColor' => '#27AE60',
        'secondaryColor' => '#229954',
        'backgroundLight' => '#D5F4E6',
        'gradientStart' => '#F5FFF5',
        'gradientEnd' => '#E6FFE6',
        'subtitle' => 'Devenez la voix du sport et montrez vos talents de commentateur.',
        'description' => 'Commentez des moments sportifs mémorables avec passion, précision et professionnalisme.',
        'requirements' => [
            'Format' => ['Commentaire en direct', 'Vidéo de 3-5 minutes'],
            'Sports' => ['Tous sports acceptés'],
            'Critères d\'évaluation' => ['Fluidité', 'Connaissance sportive', 'Enthousiasme', 'Clarté']
        ]
    ],
    'film' => [
        'name' => 'Concours de Film Documentaire',
        'type' => 'Film documentaire',
        'image' => 'film-768x461.png',
        'primaryColor' => '#F39C12',
        'secondaryColor' => '#D68910',
        'backgroundLight' => '#FCF3CF',
        'gradientStart' => '#FFFAF5',
        'gradientEnd' => '#FFF5E6',
        'subtitle' => 'Racontez une histoire captivante à travers l\'objectif de votre caméra.',
        'description' => 'Créez un documentaire qui informe, émeut et inspire. Explorez des sujets qui vous passionnent.',
        'requirements' => [
            'Durée' => ['5 minutes minimum', '15 minutes maximum'],
            'Format' => ['HD minimum (1080p)', 'Sous-titres français requis'],
            'Critères d\'évaluation' => ['Narration', 'Qualité technique', 'Impact', 'Originalité du sujet']
        ]
    ]
];

$config = $contestConfigs[$contestType] ?? $contestConfigs['dessin'];
?>

<div class="contest-landing">
    <!-- Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, <?= $config['gradientStart'] ?> 0%, <?= $config['gradientEnd'] ?> 100%);">
        <div class="hero-content">
            <div class="hero-text">
                <h1 style="color: <?= $config['primaryColor'] ?>"><?= h($config['name']) ?></h1>
                <p class="hero-subtitle"><?= h($config['subtitle']) ?></p>
                <p class="hero-description"><?= h($config['description']) ?></p>
                
                <div class="hero-cta">
                    <?php if ($user): ?>
                        <?= $this->Html->link(
                            'S\'inscrire au concours', 
                            ['action' => 'add', '?' => ['cat' => $config['type']]],
                            ['class' => 'btn btn-primary btn-large', 'style' => 'background-color: ' . $config['primaryColor'] . '; border-color: ' . $config['primaryColor']]
                        ) ?>
                        <?= $this->Html->link(
                            'Mes participations', 
                            ['action' => 'index'],
                            ['class' => 'btn btn-secondary btn-large']
                        ) ?>
                    <?php else: ?>
                        <?= $this->Html->link(
                            'Se connecter pour participer', 
                            ['controller' => 'Users', 'action' => 'login'],
                            ['class' => 'btn btn-primary btn-large', 'style' => 'background-color: ' . $config['primaryColor'] . '; border-color: ' . $config['primaryColor']]
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <?= $this->Html->image('disciplines/' . $config['image'], [
                    'alt' => $config['name'],
                    'class' => 'contest-hero-img'
                ]) ?>
            </div>
        </div>
    </section>

    <!-- Requirements Section -->
    <section class="requirements-section">
        <div class="container">
            <h2 style="color: <?= $config['primaryColor'] ?>">Informations et règlement</h2>
            <div class="requirements-grid">
                <?php foreach ($config['requirements'] as $title => $items): ?>
                    <div class="requirement-card" style="border-top: 3px solid <?= $config['primaryColor'] ?>">
                        <h3><?= h($title) ?></h3>
                        <ul>
                            <?php foreach ($items as $item): ?>
                                <li><?= h($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="info-section" style="background-color: <?= $config['backgroundLight'] ?>">
        <div class="container">
            <h2 style="color: <?= $config['primaryColor'] ?>">Comment participer ?</h2>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number" style="background-color: <?= $config['primaryColor'] ?>">1</div>
                    <h3>Inscrivez-vous</h3>
                    <p>Créez votre compte et remplissez le formulaire d'inscription avec vos informations personnelles.</p>
                </div>
                <div class="step-card">
                    <div class="step-number" style="background-color: <?= $config['primaryColor'] ?>">2</div>
                    <h3>Préparez votre œuvre</h3>
                    <p>Créez et peaufinez votre participation selon les critères du concours.</p>
                </div>
                <div class="step-card">
                    <div class="step-number" style="background-color: <?= $config['primaryColor'] ?>">3</div>
                    <h3>Soumettez votre participation</h3>
                    <p>Envoyez votre œuvre avant la date limite et attendez les résultats du jury.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Prêt à participer ?</h2>
            <p>N'attendez plus, inscrivez-vous dès maintenant et montrez votre talent !</p>
            <?php if ($user): ?>
                <div class="cta-buttons">
                    <?= $this->Html->link(
                        'Je m\'inscris', 
                        ['action' => 'add', '?' => ['cat' => $config['type']]],
                        ['class' => 'btn btn-primary btn-large', 'style' => 'background-color: ' . $config['primaryColor'] . '; border-color: ' . $config['primaryColor']]
                    ) ?>
                    <?= $this->Html->link(
                        'Retour aux concours', 
                        ['controller' => 'Sports', 'action' => 'index', '#' => 'concours'],
                        ['class' => 'btn btn-secondary btn-large']
                    ) ?>
                </div>
            <?php else: ?>
                <?= $this->Html->link(
                    'Se connecter', 
                    ['controller' => 'Users', 'action' => 'login'],
                    ['class' => 'btn btn-primary btn-large', 'style' => 'background-color: ' . $config['primaryColor'] . '; border-color: ' . $config['primaryColor']]
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

.contest-landing {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.6;
    color: #2D3436;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Hero Section */
.hero {
    padding: 4rem 0;
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
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #636E72;
}

.hero-description {
    font-size: 1.125rem;
    color: #636E72;
    margin-bottom: 2rem;
    line-height: 1.8;
}

.hero-cta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.hero-cta .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.hero-image {
    text-align: center;
}

.contest-hero-img {
    width: 100%;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* Requirements Section */
.requirements-section {
    padding: 5rem 0;
    background: white;
}

.requirements-section h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 3rem;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.requirement-card {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.requirement-card h3 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #2D3436;
}

.requirement-card ul {
    list-style: none;
}

.requirement-card li {
    padding: 0.5rem 0;
    padding-left: 1.5rem;
    position: relative;
}

.requirement-card li:before {
    content: "✓";
    position: absolute;
    left: 0;
    font-weight: bold;
}

/* Info Section */
.info-section {
    padding: 5rem 0;
}

.info-section h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 3rem;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.step-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.step-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.step-card p {
    color: #636E72;
    line-height: 1.6;
}

/* CTA Section */
.cta-section {
    padding: 5rem 0;
    background: #f8f9fa;
    text-align: center;
}

.cta-section h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #2D3436;
}

.cta-section p {
    font-size: 1.25rem;
    color: #636E72;
    margin-bottom: 2rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
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

    .hero-subtitle {
        font-size: 1.25rem;
    }

    .hero-cta {
        justify-content: center;
    }

    .requirements-grid,
    .steps-grid {
        grid-template-columns: 1fr;
    }
}
</style>