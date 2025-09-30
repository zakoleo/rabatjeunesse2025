<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="concours-type-selector">
    <div class="container">
        <div class="selector-header">
            <h1>Choisissez votre type de concours</h1>
            <p class="subtitle">Sélectionnez le concours auquel vous souhaitez participer</p>
        </div>
        
        <div class="types-grid">
            <a href="<?= $this->Url->build(['action' => 'add', '?' => ['cat' => 'Dessin']]) ?>" class="type-card">
                <div class="type-image">
                    <?= $this->Html->image('disciplines/img_dessin-768x461.png', [
                        'alt' => 'Dessin',
                        'class' => 'type-img'
                    ]) ?>
                </div>
                <div class="type-content">
                    <h3>Dessin</h3>
                    <p>Exprimez votre créativité artistique à travers le dessin</p>
                    <span class="btn-select">Participer <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            
            <a href="<?= $this->Url->build(['action' => 'add', '?' => ['cat' => 'Chanson']]) ?>" class="type-card">
                <div class="type-image">
                    <?= $this->Html->image('disciplines/chansson-768x461.png', [
                        'alt' => 'Chanson',
                        'class' => 'type-img'
                    ]) ?>
                </div>
                <div class="type-content">
                    <h3>Chanson</h3>
                    <p>Partagez votre talent musical et vocal</p>
                    <span class="btn-select">Participer <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            
            <a href="<?= $this->Url->build(['action' => 'add', '?' => ['cat' => 'Commentateur sportif']]) ?>" class="type-card">
                <div class="type-image">
                    <?= $this->Html->image('disciplines/entreneur-768x461.png', [
                        'alt' => 'Commentateur sportif',
                        'class' => 'type-img'
                    ]) ?>
                </div>
                <div class="type-content">
                    <h3>Commentateur sportif</h3>
                    <p>Montrez vos compétences en commentaire sportif</p>
                    <span class="btn-select">Participer <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            
            <a href="<?= $this->Url->build(['action' => 'add', '?' => ['cat' => 'Film documentaire']]) ?>" class="type-card">
                <div class="type-image">
                    <?= $this->Html->image('disciplines/film-768x461.png', [
                        'alt' => 'Film documentaire',
                        'class' => 'type-img'
                    ]) ?>
                </div>
                <div class="type-content">
                    <h3>Film documentaire</h3>
                    <p>Créez un film documentaire captivant</p>
                    <span class="btn-select">Participer <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
        
        <div class="back-link">
            <?= $this->Html->link('<i class="fas fa-arrow-left"></i> Retour', 
                ['controller' => 'Sports', 'action' => 'concours'], 
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>
</div>

<style>
.concours-type-selector {
    min-height: 100vh;
    background: linear-gradient(135deg, #FAF8FC 0%, #F0E6F5 100%);
    padding: 3rem 0;
}

.selector-header {
    text-align: center;
    margin-bottom: 3rem;
}

.selector-header h1 {
    font-size: 2.5rem;
    color: #9B59B6;
    margin-bottom: 1rem;
}

.selector-header .subtitle {
    font-size: 1.25rem;
    color: #666;
}

.types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.type-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
}

.type-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.type-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.type-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.type-card:hover .type-img {
    transform: scale(1.05);
}

.type-content {
    padding: 2rem;
}

.type-content h3 {
    font-size: 1.5rem;
    color: #9B59B6;
    margin-bottom: 0.5rem;
}

.type-content p {
    color: #666;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.btn-select {
    display: inline-block;
    background-color: #9B59B6;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.type-card:hover .btn-select {
    background-color: #8E44AD;
}

.back-link {
    text-align: center;
    margin-top: 3rem;
}

.back-link .btn {
    font-size: 1.1rem;
    padding: 0.75rem 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .types-grid {
        grid-template-columns: 1fr;
    }
    
    .selector-header h1 {
        font-size: 2rem;
    }
}
</style>