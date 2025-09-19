<?php
/**
 * @var \App\View\AppView $this
 * @var array $types
 */
?>
<div class="football-types-dashboard content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🏟️ Types de Football</h2>
            <p class="text-muted mb-0">Gestion des formats de terrain et configurations de jeu</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('⚽ Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary btn-modern']) ?>
            <?= $this->Html->link('➕ Nouveau Type', ['action' => 'addType'], ['class' => 'btn btn-primary btn-modern']) ?>
        </div>
    </div>

    <!-- Types Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-info">🏟️ Liste des Types de Football</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <?= $this->Html->link('<i class="fas fa-plus fa-sm"></i> Nouveau Type', 
                        ['action' => 'addType'], 
                        ['class' => 'dropdown-item', 'escape' => false]) ?>
                    <?= $this->Html->link('<i class="fas fa-sync fa-sm"></i> Actualiser', 
                        ['action' => 'types'], 
                        ['class' => 'dropdown-item', 'escape' => false]) ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type de Football</th>
                            <th>Code</th>
                            <th>Joueurs</th>
                            <th>Catégories Assignées</th>
                            <th>Statut</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($types as $type): ?>
                        <tr>
                            <td class="text-center">
                                <span class="badge badge-light"><?= $type->id ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-info">
                                            <i class="fas fa-futbol text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold"><?= h($type->name) ?></div>
                                        <div class="small text-gray-600">
                                            Format de terrain de football
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info badge-lg"><?= h($type->code) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="text-sm">
                                    <i class="fas fa-users text-primary mr-1"></i>
                                    <strong><?= $type->min_players ?>-<?= $type->max_players ?></strong>
                                </div>
                                <div class="text-xs text-gray-500">joueurs par équipe</div>
                            </td>
                            <td>
                                <?php if (!empty($type->football_categories)): ?>
                                    <div class="mb-1">
                                        <?php foreach ($type->football_categories as $category): ?>
                                            <span class="badge badge-success mr-1 mb-1">
                                                <?= h($category->name) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?= count($type->football_categories) ?> catégorie(s)
                                    </small>
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="fas fa-exclamation-triangle text-warning mb-2"></i><br>
                                        <span class="text-warning small">Non assigné</span><br>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-plus"></i> Assigner',
                                            ['action' => 'relationships'],
                                            ['class' => 'btn btn-sm btn-outline-success mt-1', 'escape' => false]
                                        ) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($type->active): ?>
                                    <span class="badge badge-success badge-pill">
                                        <i class="fas fa-check"></i> Actif
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary badge-pill">
                                        <i class="fas fa-times"></i> Inactif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="text-xs text-gray-500">
                                    <?= $type->created ? (is_string($type->created) ? $type->created : $type->created->format('d/m/Y')) : 'N/A' ?>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <?= $type->created ? (is_string($type->created) ? '' : $type->created->format('H:i')) : '' ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'editType', $type->id],
                                        ['class' => 'btn btn-sm btn-primary', 'escape' => false, 'title' => 'Modifier']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-link"></i>',
                                        ['action' => 'relationships'],
                                        ['class' => 'btn btn-sm btn-success', 'escape' => false, 'title' => 'Voir Relations']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'deleteType', $type->id],
                                        [
                                            'confirm' => 'Êtes-vous sûr de vouloir supprimer le type "' . $type->name . '"?',
                                            'class' => 'btn btn-sm btn-danger',
                                            'escape' => false,
                                            'title' => 'Supprimer'
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if (empty($types)): ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-futbol fa-4x text-gray-300"></i>
                        </div>
                        <h4 class="text-gray-500">Aucun type de football trouvé</h4>
                        <p class="text-gray-400 mb-4">Commencez par créer votre premier type de terrain (format de jeu).</p>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus"></i> Créer le Premier Type',
                            ['action' => 'addType'],
                            ['class' => 'btn btn-primary btn-lg', 'escape' => false]
                        ) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<style>
/* Modern Rabat Jeunesse Brand Colors */
:root {
    /* Logo-based Brand Colors */
    --rj-blue: #3b82f6;
    --rj-orange: #f97316;
    --rj-green: #10b981;
    --rj-red: #ef4444;
    --rj-purple: #8b5cf6;
    
    /* Modern Grays */
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    
    /* Modern Shadows & Effects */
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    
    /* Modern Gradients */
    --gradient-blue: linear-gradient(135deg, var(--rj-blue) 0%, var(--rj-purple) 100%);
    --gradient-green: linear-gradient(135deg, var(--rj-green) 0%, var(--rj-blue) 100%);
    --gradient-orange: linear-gradient(135deg, var(--rj-orange) 0%, var(--rj-red) 100%);
    --gradient-purple: linear-gradient(135deg, var(--rj-purple) 0%, var(--rj-blue) 100%);
    --gradient-info: var(--gradient-purple);
}

/* Modern Dashboard Background */
.football-types-dashboard {
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Modern Glass-morphism Cards */
.card {
    border-radius: 1.25rem;
    border: 1px solid rgba(203, 213, 225, 0.2);
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    box-shadow: var(--shadow-lg);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.card:hover {
    box-shadow: var(--shadow-xl);
    border-color: rgba(59, 130, 246, 0.3);
}

.card-header {
    border-radius: 1.25rem 1.25rem 0 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
    backdrop-filter: blur(30px);
    border-bottom: 1px solid rgba(203, 213, 225, 0.2);
}

/* Modern Gradient Border Cards */
.border-left-info {
    border-left: 6px solid transparent;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
        var(--gradient-purple);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    position: relative;
}

.border-left-info::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: var(--gradient-purple);
    border-radius: 0 4px 4px 0;
}

.border-left-success {
    border-left: 6px solid transparent;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
        var(--gradient-green);
    background-origin: border-box;
    background-clip: padding-box, border-box;
}

.border-left-success::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: var(--gradient-green);
    border-radius: 0 4px 4px 0;
}

.border-left-primary {
    border-left: 6px solid transparent;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
        var(--gradient-blue);
    background-origin: border-box;
    background-clip: padding-box, border-box;
}

.border-left-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: var(--gradient-blue);
    border-radius: 0 4px 4px 0;
}

.border-left-warning {
    border-left: 6px solid transparent;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
        var(--gradient-orange);
    background-origin: border-box;
    background-clip: padding-box, border-box;
}

.border-left-warning::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 6px;
    background: var(--gradient-orange);
    border-radius: 0 4px 4px 0;
}

/* Modern 3D Icon Circles */
.icon-circle {
    height: 4rem;
    width: 4rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    box-shadow: 
        var(--shadow-lg),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.icon-circle::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    right: 2px;
    bottom: 2px;
    border-radius: 50%;
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
    pointer-events: none;
}

.icon-circle.bg-info {
    background: var(--gradient-purple);
    box-shadow: 
        var(--shadow-lg),
        inset 0 1px 0 rgba(255, 255, 255, 0.2),
        0 0 20px rgba(139, 92, 246, 0.3);
}

.icon-circle.bg-success {
    background: var(--gradient-green);
    box-shadow: 
        var(--shadow-lg),
        inset 0 1px 0 rgba(255, 255, 255, 0.2),
        0 0 20px rgba(16, 185, 129, 0.3);
}

.icon-circle.bg-primary {
    background: var(--gradient-blue);
    box-shadow: 
        var(--shadow-lg),
        inset 0 1px 0 rgba(255, 255, 255, 0.2),
        0 0 20px rgba(59, 130, 246, 0.3);
}

.icon-circle.bg-warning {
    background: var(--gradient-orange);
    box-shadow: 
        var(--shadow-lg),
        inset 0 1px 0 rgba(255, 255, 255, 0.2),
        0 0 20px rgba(249, 115, 22, 0.3);
}

.icon-circle:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 
        var(--shadow-xl),
        inset 0 1px 0 rgba(255, 255, 255, 0.3),
        0 0 30px rgba(139, 92, 246, 0.4);
}

/* Modern Typography */
.text-gray-800 { color: var(--gray-800) !important; }
.text-gray-600 { color: var(--gray-600) !important; }
.text-gray-500 { color: var(--gray-500) !important; }
.text-gray-400 { color: var(--gray-400) !important; }
.text-gray-300 { color: var(--gray-300) !important; }

.text-xs { 
    font-size: 12px; 
    font-weight: 500;
}
.text-sm { 
    font-size: 14px; 
    font-weight: 500;
}

/* Modern 3D Buttons */
.btn-modern {
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 14px;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-md);
    border: none;
    position: relative;
    overflow: hidden;
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-modern:hover::before {
    left: 100%;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.btn-info.btn-modern {
    background: var(--gradient-purple);
    color: white;
}

.btn-success.btn-modern {
    background: var(--gradient-green);
    color: white;
}

.btn-primary.btn-modern {
    background: var(--gradient-blue);
    color: white;
}

.btn-warning.btn-modern {
    background: var(--gradient-orange);
    color: white;
}

/* Modern Badges with Gradients */
.badge {
    font-size: 12px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    margin: 0.25rem;
    box-shadow: var(--shadow);
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-md);
}

.badge-info {
    background: var(--gradient-purple);
    color: white;
}

.badge-success {
    background: var(--gradient-green);
    color: white;
}

.badge-primary {
    background: var(--gradient-blue);
    color: white;
}

.badge-warning {
    background: var(--gradient-orange);
    color: white;
}

.badge-lg {
    font-size: 16px;
    padding: 0.75rem 1.5rem;
    border-radius: 1rem;
}

/* Modern Table with Hover Effects */
.table {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: var(--shadow);
}

.table th {
    border-top: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 11px;
    color: var(--gray-600);
    background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
    letter-spacing: 0.1em;
    padding: 1.5rem 1rem;
}

.table td {
    vertical-align: middle;
    padding: 1.25rem 1rem;
    transition: all 0.3s ease;
}

.table tbody tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.table tbody tr:hover {
    background: linear-gradient(135deg, var(--gray-50), rgba(59, 130, 246, 0.05));
    transform: scale(1.02);
    box-shadow: var(--shadow-md);
}

/* Modern Dropdown with Animation */
.dropdown-menu {
    border: none;
    box-shadow: var(--shadow-xl);
    border-radius: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
}

.dropdown-item {
    border-radius: 0.75rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    padding: 0.75rem 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.dropdown-item:hover {
    background: var(--gradient-purple);
    color: white;
    transform: translateX(8px);
    box-shadow: var(--shadow-md);
}

/* Animation Keyframes */
@keyframes slideInUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pulse {
    0%, 100% { 
        transform: scale(1);
    }
    50% { 
        transform: scale(1.05);
    }
}

.animate-slide-in {
    animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animated--fade-in {
    animation: fadeIn 0.4s ease-out;
}

.pulse-on-hover:hover {
    animation: pulse 2s infinite;
}

/* Responsive Design */
@media (max-width: 768px) {
    .football-types-dashboard {
        padding: 1rem 0;
    }
    
    .icon-circle {
        width: 3rem;
        height: 3rem;
        font-size: 16px;
    }
    
    .btn-modern {
        padding: 0.5rem 1rem;
        font-size: 13px;
    }
}

/* Focus States */
.btn:focus,
.form-control:focus {
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    border-color: var(--rj-blue);
}

/* Modern Alert */
.alert {
    border-radius: 1rem;
    border: none;
    box-shadow: var(--shadow-md);
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.05));
}
</style>