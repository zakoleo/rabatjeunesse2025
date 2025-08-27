<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\BasketballTeam> $basketballTeams
 */

$this->assign('title', 'Mes équipes de Basketball');
?>

<div class="basketball-teams-index">
    <div class="page-header">
        <h1>Mes équipes de Basketball</h1>
        <div class="header-actions">
            <?= $this->Html->link('Ajouter une équipe', ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('Voir tous les sports', ['controller' => 'Sports', 'action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php if (!empty($basketballTeams)): ?>
        <div class="teams-grid">
            <?php foreach ($basketballTeams as $basketballTeam): ?>
                <div class="team-card">
                    <div class="team-header">
                        <h3><?= h($basketballTeam->nom_equipe) ?></h3>
                        <div class="team-badges">
                            <span class="badge badge-sport"><?= h($basketballTeam->type_basketball) ?></span>
                            <span class="badge badge-category"><?= h($basketballTeam->categorie) ?></span>
                        </div>
                    </div>
                    
                    <div class="team-details">
                        <div class="detail-row">
                            <strong>Genre:</strong> <?= h($basketballTeam->genre) ?>
                        </div>
                        <div class="detail-row">
                            <strong>District:</strong> <?= h($basketballTeam->district) ?>
                        </div>
                        <div class="detail-row">
                            <strong>Organisation:</strong> <?= h($basketballTeam->organisation) ?>
                        </div>
                        <div class="detail-row">
                            <strong>Inscrite le:</strong> <?= $basketballTeam->created->format('d/m/Y') ?>
                        </div>
                    </div>
                    
                    <div class="team-actions">
                        <?= $this->Html->link('Voir', ['action' => 'view', $basketballTeam->id], ['class' => 'btn btn-outline']) ?>
                        <?= $this->Html->link('Modifier', ['action' => 'edit', $basketballTeam->id], ['class' => 'btn btn-primary']) ?>
                        <?= $this->Form->postLink(
                            'Supprimer',
                            ['action' => 'delete', $basketballTeam->id],
                            [
                                'confirm' => __('Êtes-vous sûr de vouloir supprimer {0}?', $basketballTeam->nom_equipe),
                                'class' => 'btn btn-danger'
                            ]
                        ) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
            </div>
            <h3>Aucune équipe de basketball inscrite</h3>
            <p>Vous n'avez pas encore inscrit d'équipe de basketball. Commencez dès maintenant !</p>
            <?= $this->Html->link('Inscrire une équipe', ['action' => 'add'], ['class' => 'btn btn-primary btn-large']) ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .basketball-teams-index {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .page-header h1 {
        color: #FF6B35;
        margin: 0;
        font-size: 2.5rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .teams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .team-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .team-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #FF6B35, #F7931E);
        color: white;
    }

    .team-header h3 {
        margin: 0 0 1rem 0;
        font-size: 1.25rem;
    }

    .team-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-sport {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .badge-category {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .team-details {
        padding: 1.5rem;
    }

    .detail-row {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-row strong {
        color: #555;
    }

    .team-actions {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        display: flex;
        gap: 0.5rem;
        justify-content: space-between;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-block;
        text-align: center;
        flex: 1;
    }

    .btn-primary {
        background: #FF6B35;
        color: white;
    }

    .btn-primary:hover {
        background: #E55A2B;
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

    .btn-outline {
        background: transparent;
        color: #FF6B35;
        border: 1px solid #FF6B35;
    }

    .btn-outline:hover {
        background: #FF6B35;
        color: white;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 2rem;
        color: #FF6B35;
    }

    .empty-state h3 {
        color: #333;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .btn-large {
        padding: 12px 32px;
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .basketball-teams-index {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .header-actions {
            flex-direction: column;
            width: 100%;
        }

        .teams-grid {
            grid-template-columns: 1fr;
        }

        .team-actions {
            flex-direction: column;
        }
    }
</style>