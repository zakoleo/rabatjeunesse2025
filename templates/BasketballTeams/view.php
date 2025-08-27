<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BasketballTeam $basketballTeam
 */

$this->assign('title', 'Équipe de Basketball - ' . h($basketballTeam->nom_equipe));
?>

<div class="basketball-team-view">
    <div class="team-header">
        <h1><?= h($basketballTeam->nom_equipe) ?></h1>
        <div class="team-badges">
            <span class="badge badge-sport"><?= h($basketballTeam->type_basketball) ?></span>
            <span class="badge badge-category"><?= h($basketballTeam->categorie) ?></span>
            <span class="badge badge-genre"><?= h($basketballTeam->genre) ?></span>
        </div>
    </div>

    <div class="team-content">
        <div class="team-details">
            <div class="detail-card">
                <h3>Informations de l'équipe</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Nom de l'équipe:</label>
                        <span><?= h($basketballTeam->nom_equipe) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Type de Basketball:</label>
                        <span><?= h($basketballTeam->type_basketball) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Catégorie:</label>
                        <span><?= h($basketballTeam->categorie) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Genre:</label>
                        <span><?= h($basketballTeam->genre) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>District:</label>
                        <span><?= h($basketballTeam->district) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Organisation:</label>
                        <span><?= h($basketballTeam->organisation) ?></span>
                    </div>
                    <div class="detail-item full-width">
                        <label>Adresse:</label>
                        <span><?= nl2br(h($basketballTeam->adresse)) ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h3>Informations d'inscription</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Date d'inscription:</label>
                        <span><?= $basketballTeam->created->format('d/m/Y à H:i') ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Dernière modification:</label>
                        <span><?= $basketballTeam->modified->format('d/m/Y à H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="team-actions">
            <h3>Actions</h3>
            <div class="action-buttons">
                <?= $this->Html->link('Modifier l\'équipe', ['action' => 'edit', $basketballTeam->id], ['class' => 'btn btn-primary']) ?>
                <?= $this->Html->link('Retour à mes équipes', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
                <?= $this->Html->link('Voir tous les sports', ['controller' => 'Sports', 'action' => 'index'], ['class' => 'btn btn-outline']) ?>
            </div>
        </div>
    </div>
</div>

<style>
    .basketball-team-view {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .team-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .team-header h1 {
        color: #FF6B35;
        margin-bottom: 1rem;
        font-size: 2.5rem;
    }

    .team-badges {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .badge-sport {
        background: #FF6B35;
        color: white;
    }

    .badge-category {
        background: #F7931E;
        color: white;
    }

    .badge-genre {
        background: #FFB84D;
        color: white;
    }

    .team-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .detail-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .detail-card h3 {
        color: #FF6B35;
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #FF6B35;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-item label {
        display: block;
        font-weight: 600;
        color: #555;
        margin-bottom: 0.25rem;
    }

    .detail-item span {
        color: #333;
        font-size: 1rem;
    }

    .team-actions h3 {
        color: #FF6B35;
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #FF6B35;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background: #FF6B35;
        color: white;
    }

    .btn-primary:hover {
        background: #E55A2B;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
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
        border: 2px solid #FF6B35;
    }

    .btn-outline:hover {
        background: #FF6B35;
        color: white;
    }

    /* Success message styling */
    .message.success {
        background: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border: 1px solid #c3e6cb;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .basketball-team-view {
            padding: 1rem;
        }

        .team-header h1 {
            font-size: 2rem;
        }

        .team-content {
            grid-template-columns: 1fr;
        }

        .team-badges {
            flex-direction: column;
            align-items: center;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>