<?php
/**
 * @var \App\View\AppView $this
 * @var array $sports
 * @var array $concours
 */
?>
<div class="sports-list">
    <div class="header-section">
        <h1>Choisissez votre discipline sportive</h1>
        <p class="subtitle">Sélectionnez le sport dans lequel vous souhaitez inscrire votre équipe</p>
    </div>

    <div class="sports-grid">
        <?php foreach ($sports as $sport): ?>
            <div class="sport-card">
                <a href="<?= $this->Url->build(['action' => $sport['id']]) ?>" class="sport-link">
                    <div class="sport-image">
                        <?= $this->Html->image('disciplines/' . $sport['image'], [
                            'alt' => $sport['name'],
                            'class' => 'img-fluid'
                        ]) ?>
                        <div class="sport-overlay">
                            <span class="view-more">En savoir plus →</span>
                        </div>
                    </div>
                    <div class="sport-info">
                        <h3><?= h($sport['name']) ?></h3>
                        <p><?= h($sport['description']) ?></p>
                        <div class="sport-categories">
                            <?php foreach ($sport['categories'] as $category): ?>
                                <span class="category-badge"><?= h($category) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Contest Section -->
    <div class="concours-section" id="concours">
        <div class="header-section">
            <h2>Choisissez votre type de concours</h2>
            <p class="subtitle">Participez à nos concours créatifs et montrez vos talents</p>
        </div>
        
        <div class="concours-grid">
            <?php foreach ($concours as $contest): ?>
                <div class="concours-card">
                    <a href="<?= $this->Url->build($contest['url']) ?>" class="concours-link">
                        <div class="concours-image">
                            <?= $this->Html->image('disciplines/' . $contest['image'], [
                                'alt' => $contest['name'],
                                'class' => 'img-fluid'
                            ]) ?>
                            <div class="concours-overlay">
                                <span class="view-more">Participer →</span>
                            </div>
                        </div>
                        <div class="concours-info">
                            <h3><?= h($contest['name']) ?></h3>
                            <p><?= h($contest['description']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .sports-list {
        padding: 3rem 0;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .header-section {
        text-align: center;
        margin-bottom: 3rem;
    }

    .header-section h1 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 1rem;
    }

    .subtitle {
        font-size: 1.2rem;
        color: #666;
    }

    .sports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .sport-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .sport-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .sport-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .sport-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background-color: #f0f0f0;
    }

    .sport-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .sport-card:hover .sport-image img {
        transform: scale(1.05);
    }

    .sport-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sport-card:hover .sport-overlay {
        opacity: 1;
    }

    .view-more {
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .sport-info {
        padding: 1.5rem;
    }

    .sport-info h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .sport-info p {
        color: #666;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .sport-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .category-badge {
        background-color: #e8f4f8;
        color: #2980b9;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Contest Section Styles */
    .concours-section {
        background-color: #f4ecf7;
        padding: 3rem 0;
        margin-top: 3rem;
    }
    
    .concours-section .header-section h2 {
        font-size: 2.5rem;
        color: #9B59B6;
        margin-bottom: 1rem;
    }
    
    .concours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .concours-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(155, 89, 182, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .concours-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(155, 89, 182, 0.2);
    }
    
    .concours-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .concours-image {
        position: relative;
        height: 180px;
        overflow: hidden;
        background-color: #f0f0f0;
    }
    
    .concours-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .concours-card:hover .concours-image img {
        transform: scale(1.05);
    }
    
    .concours-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(155, 89, 182, 0.8), transparent);
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .concours-card:hover .concours-overlay {
        opacity: 1;
    }
    
    .concours-info {
        padding: 1.5rem;
    }
    
    .concours-info h3 {
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
        color: #9B59B6;
    }
    
    .concours-info p {
        color: #666;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .header-section h1, .header-section h2 {
            font-size: 2rem;
        }

        .sports-grid, .concours-grid {
            grid-template-columns: 1fr;
            padding: 0 1rem;
        }
        
        .concours-section {
            margin-top: 2rem;
        }
    }
</style>