<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $inscriptions
 * @var int $totalInscriptions
 */
?>
<div class="page-header">
    <div class="container">
        <h1>Bienvenue <?= h($user->username) ?> !</h1>
        <p>Gérez vos inscriptions et participez aux tournois</p>
    </div>
</div>

<div class="content-section">
    <div class="container">
        <div class="grid grid-2">
            <!-- Statistiques -->
            <div class="card">
                <div class="card-header">
                    <h3>Vos inscriptions</h3>
                </div>
                <div class="card-body">
                    <?php if ($totalInscriptions > 0): ?>
                        <p class="mb-3">Vous avez <strong><?= $totalInscriptions ?></strong> inscription(s) au total :</p>
                        <ul class="stats-list">
                            <?php if ($inscriptions['football'] > 0): ?>
                                <li>
                                    <span class="sport-name">Football</span>
                                    <span class="sport-count"><?= $inscriptions['football'] ?> équipe(s)</span>
                                </li>
                            <?php endif; ?>
                            <?php if ($inscriptions['handball'] > 0): ?>
                                <li>
                                    <span class="sport-name">Handball</span>
                                    <span class="sport-count"><?= $inscriptions['handball'] ?> équipe(s)</span>
                                </li>
                            <?php endif; ?>
                            <?php if ($inscriptions['basketball'] > 0): ?>
                                <li>
                                    <span class="sport-name">Basketball</span>
                                    <span class="sport-count"><?= $inscriptions['basketball'] ?> équipe(s)</span>
                                </li>
                            <?php endif; ?>
                            <?php if ($inscriptions['volleyball'] > 0): ?>
                                <li>
                                    <span class="sport-name">Volleyball</span>
                                    <span class="sport-count"><?= $inscriptions['volleyball'] ?> équipe(s)</span>
                                </li>
                            <?php endif; ?>
                            <?php if ($inscriptions['beachvolley'] > 0): ?>
                                <li>
                                    <span class="sport-name">Beach-volley</span>
                                    <span class="sport-count"><?= $inscriptions['beachvolley'] ?> équipe(s)</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Vous n'avez pas encore d'inscriptions.</p>
                            <p>Commencez par inscrire votre première équipe !</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h3>Actions rapides</h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <?= $this->Html->link(
                            '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"></path>
                            </svg>
                            Nouvelle inscription',
                            ['controller' => 'Sports', 'action' => 'index'],
                            ['class' => 'action-item primary', 'escape' => false]
                        ) ?>
                        
                        <?php if ($totalInscriptions > 0): ?>
                            <?php if ($inscriptions['football'] > 0): ?>
                                <?= $this->Html->link(
                                    '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Mes équipes Football',
                                    ['controller' => 'Teams', 'action' => 'index'],
                                    ['class' => 'action-item', 'escape' => false]
                                ) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des équipes récentes -->
        <?php if ($totalInscriptions > 0): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Gérer mes équipes</h3>
                </div>
                <div class="card-body">
                    <div class="teams-grid">
                        <?php if ($inscriptions['football'] > 0): ?>
                            <div class="team-card">
                                <h4>Football</h4>
                                <p><?= $inscriptions['football'] ?> équipe(s) inscrite(s)</p>
                                <?= $this->Html->link('Voir mes équipes', ['controller' => 'Teams', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($inscriptions['handball'] > 0): ?>
                            <div class="team-card">
                                <h4>Handball</h4>
                                <p><?= $inscriptions['handball'] ?> équipe(s) inscrite(s)</p>
                                <?= $this->Html->link('Voir mes équipes', ['controller' => 'HandballTeams', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($inscriptions['basketball'] > 0): ?>
                            <div class="team-card">
                                <h4>Basketball</h4>
                                <p><?= $inscriptions['basketball'] ?> équipe(s) inscrite(s)</p>
                                <?= $this->Html->link('Voir mes équipes', ['controller' => 'BasketballTeams', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($inscriptions['volleyball'] > 0): ?>
                            <div class="team-card">
                                <h4>Volleyball</h4>
                                <p><?= $inscriptions['volleyball'] ?> équipe(s) inscrite(s)</p>
                                <?= $this->Html->link('Voir mes équipes', ['controller' => 'VolleyballTeams', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($inscriptions['beachvolley'] > 0): ?>
                            <div class="team-card">
                                <h4>Beach-volley</h4>
                                <p><?= $inscriptions['beachvolley'] ?> équipe(s) inscrite(s)</p>
                                <?= $this->Html->link('Voir mes équipes', ['controller' => 'BeachvolleyTeams', 'action' => 'index'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .stats-list {
        list-style: none;
        padding: 0;
    }
    
    .stats-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: var(--background-light);
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .sport-name {
        font-weight: 500;
        color: var(--text-dark);
    }
    
    .sport-count {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .empty-state p {
        margin-bottom: 0.5rem;
    }
    
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .action-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: var(--background-light);
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-dark);
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .action-item:hover {
        background: var(--border-color);
        transform: translateX(5px);
    }
    
    .action-item.primary {
        background: var(--primary-color);
        color: white;
    }
    
    .action-item.primary:hover {
        background: var(--primary-hover);
    }
    
    .action-item .icon {
        width: 24px;
        height: 24px;
    }
    
    .teams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .team-card {
        background: var(--background-light);
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
    }
    
    .team-card h4 {
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .team-card p {
        margin-bottom: 1rem;
    }
    
    .team-card .btn {
        font-size: 0.875rem;
    }
</style>