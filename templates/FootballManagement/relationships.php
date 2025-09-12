<?php
/**
 * @var \App\View\AppView $this
 * @var array $categories
 * @var array $allTypes
 */
?>
<div class="football-relationships-dashboard content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">🔗 Relations Football</h2>
            <p class="text-muted mb-0">Configuration des associations catégories ↔ types de terrain</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('⚽ Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= $this->Html->link('📋 Catégories', ['action' => 'categories'], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('🏟️ Types', ['action' => 'types'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Relations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $totalRelations = array_sum(array_map(function($cat) { 
                                    return count($cat['football_types']); 
                                }, $categories));
                                echo $totalRelations;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Catégories Configurées
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($categories, function($cat) { return !empty($cat['football_types']); })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                À Configurer
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($categories, function($cat) { return empty($cat['football_types']); })) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Types Disponibles
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($allTypes) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-futbol fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Relationship -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-success">⚡ Ajout Rapide de Relations</h6>
            <div class="text-xs text-gray-500">
                <i class="fas fa-plus-circle"></i> Configuration Express
            </div>
        </div>
        <div class="card-body">
            <?= $this->Form->create(null, ['url' => ['action' => 'addRelationship']]) ?>
            <div class="row align-items-end">
                <div class="col-md-4">
                    <?= $this->Form->control('category_id', [
                        'type' => 'select',
                        'options' => array_combine(
                            array_column($categories, 'id'),
                            array_column($categories, 'name')
                        ),
                        'empty' => '-- Select Category --',
                        'label' => '📋 Category',
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $this->Form->control('type_id', [
                        'type' => 'select',
                        'options' => array_combine(
                            array_column($allTypes, 'id'),
                            array_map(function($type) { 
                                return $type['name'] . ' (' . $type['code'] . ')'; 
                            }, $allTypes)
                        ),
                        'empty' => '-- Select Type --',
                        'label' => '🏟️ Football Type',
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $this->Form->button('➕ Add Relationship', [
                        'class' => 'btn btn-success',
                        'style' => 'margin-top: 8px;'
                    ]) ?>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <!-- Current Relationships Matrix -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <div>
                <h6 class="m-0 font-weight-bold text-primary">🎯 Matrice des Relations Actuelles</h6>
                <small class="text-muted">Les coches vertes indiquent les combinaisons autorisées</small>
            </div>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="matrixDropdown" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <div class="dropdown-header">Actions de la Matrice:</div>
                    <a class="dropdown-item" href="#" onclick="window.print()">
                        <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>
                        Imprimer Matrice
                    </a>
                    <?= $this->Html->link(
                        '<i class="fas fa-sync fa-sm fa-fw mr-2 text-gray-400"></i> Actualiser',
                        ['action' => 'relationships'],
                        ['class' => 'dropdown-item', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Category / Type</th>
                            <?php foreach ($allTypes as $type): ?>
                                <th class="text-center">
                                    <strong><?= h($type['code']) ?></strong><br>
                                    <small><?= h($type['name']) ?></small><br>
                                    <small class="text-muted"><?= $type['min_players'] ?>-<?= $type['max_players'] ?> players</small>
                                </th>
                            <?php endforeach; ?>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td class="align-middle">
                                    <strong><?= h($category['name']) ?></strong><br>
                                    <small class="text-muted">ID: <?= $category['id'] ?></small><br>
                                    <small class="text-muted">
                                        📅 <?= h($category['min_date']) ?> - <?= h($category['max_date']) ?>
                                    </small>
                                </td>
                                
                                <?php 
                                // Get assigned type IDs for this category
                                $assignedTypeIds = array_column($category['football_types'], 'id');
                                ?>
                                
                                <?php foreach ($allTypes as $type): ?>
                                    <td class="text-center align-middle">
                                        <?php if (in_array($type['id'], $assignedTypeIds)): ?>
                                            <!-- Relationship exists -->
                                            <div class="relationship-cell allowed">
                                                <span class="text-success fs-4">✅</span><br>
                                                <?= $this->Form->postLink(
                                                    '❌',
                                                    ['action' => 'removeRelationship'],
                                                    [
                                                        'data' => [
                                                            'category_id' => $category['id'],
                                                            'type_id' => $type['id']
                                                        ],
                                                        'confirm' => 'Remove relationship: ' . $category['name'] . ' → ' . $type['code'] . '?',
                                                        'class' => 'btn btn-outline-danger btn-sm',
                                                        'style' => 'font-size: 10px; padding: 1px 4px;',
                                                        'title' => 'Remove relationship'
                                                    ]
                                                ) ?>
                                            </div>
                                        <?php else: ?>
                                            <!-- No relationship -->
                                            <div class="relationship-cell not-allowed">
                                                <span class="text-muted">❌</span><br>
                                                <?= $this->Form->postLink(
                                                    '➕',
                                                    ['action' => 'addRelationship'],
                                                    [
                                                        'data' => [
                                                            'category_id' => $category['id'],
                                                            'type_id' => $type['id']
                                                        ],
                                                        'class' => 'btn btn-outline-success btn-sm',
                                                        'style' => 'font-size: 10px; padding: 1px 4px;',
                                                        'title' => 'Add relationship'
                                                    ]
                                                ) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                
                                <td class="text-center align-middle">
                                    <?= $this->Html->link(
                                        '🔧 Manage',
                                        ['action' => 'manageRelationships', $category['id']],
                                        ['class' => 'btn btn-primary btn-sm']
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (empty($categories)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-link fa-4x text-gray-300"></i>
                    </div>
                    <h4 class="text-gray-500">Aucune catégorie trouvée</h4>
                    <p class="text-gray-400 mb-4">Créez d'abord des catégories d'âge pour gérer les relations.</p>
                    <?= $this->Html->link(
                        '<i class="fas fa-plus"></i> Créer des Catégories',
                        ['action' => 'addCategory'],
                        ['class' => 'btn btn-primary btn-lg', 'escape' => false]
                    ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mt-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">📊 Résumé des Relations</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($categories as $category): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-left-<?= !empty($category['football_types']) ? 'success' : 'warning' ?>">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <?php if (!empty($category['football_types'])): ?>
                                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold"><?= h($category['name']) ?></div>
                                                <div class="small text-muted">
                                                    <?php if (!empty($category['football_types'])): ?>
                                                        <span class="badge badge-success"><?= count($category['football_types']) ?> type(s)</span>
                                                    <?php else: ?>
                                                        <span class="text-warning">⚠️ Aucun type assigné</span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($category['football_types'])): ?>
                                                    <div class="mt-1">
                                                        <?php foreach (array_slice($category['football_types'], 0, 3) as $type): ?>
                                                            <span class="badge badge-info badge-sm"><?= h($type['code']) ?></span>
                                                        <?php endforeach; ?>
                                                        <?php if (count($category['football_types']) > 3): ?>
                                                            <span class="badge badge-secondary badge-sm">+<?= count($category['football_types']) - 3 ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">💡 Guide des Relations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold mb-3">📜 Règles Recommandées</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <span class="badge badge-primary">-12</span>
                                <strong>Jeunes:</strong> Seulement 6x6 (petit terrain)
                            </li>
                            <li class="mb-2">
                                <span class="badge badge-primary">-15</span>
                                <strong>Cadets:</strong> 6x6 + 11x11
                            </li>
                            <li class="mb-2">
                                <span class="badge badge-primary">-18, +19</span>
                                <strong>Adultes:</strong> Tous les formats
                            </li>
                            <li class="mb-2">
                                <span class="badge badge-info">5x5</span>
                                <strong>Futsal:</strong> Terrain couvert
                            </li>
                            <li class="mb-2">
                                <span class="badge badge-info">6x6</span>
                                <strong>Mini:</strong> Petit terrain extérieur
                            </li>
                            <li class="mb-2">
                                <span class="badge badge-info">11x11</span>
                                <strong>Standard:</strong> Terrain complet
                            </li>
                        </ul>
                    </div>
                    
                    <div class="text-center">
                        <h6 class="font-weight-bold mb-3">⚡ Actions Rapides</h6>
                        <div class="d-grid gap-2">
                            <?= $this->Html->link(
                                '<i class="fas fa-magic"></i> Attribution Auto',
                                ['action' => 'assignRecommended'],
                                ['class' => 'btn btn-warning btn-block btn-sm mb-2', 'escape' => false]
                            ) ?>
                            <?= $this->Html->link(
                                '<i class="fas fa-chart-bar"></i> Rapport Relations',
                                ['action' => 'report'],
                                ['class' => 'btn btn-info btn-block btn-sm', 'escape' => false]
                            ) ?>
                        </div>
                    </div>
                </div>
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
    --gradient-primary: linear-gradient(135deg, var(--rj-blue) 0%, var(--rj-purple) 100%);
    --gradient-success: linear-gradient(135deg, var(--rj-green) 0%, var(--rj-blue) 100%);
    --gradient-warning: linear-gradient(135deg, var(--rj-orange) 0%, var(--rj-red) 100%);
    --gradient-info: linear-gradient(135deg, var(--rj-purple) 0%, var(--rj-blue) 100%);
    
    /* Glass Effects */
    --glass-bg: rgba(255, 255, 255, 0.25);
    --glass-border: rgba(255, 255, 255, 0.18);
}

/* Modern Dashboard Background with Animated Gradient */
.football-relationships-dashboard {
    background: linear-gradient(135deg, 
        var(--gray-50) 0%, 
        var(--gray-100) 25%, 
        rgba(59, 130, 246, 0.05) 50%, 
        var(--gray-100) 75%, 
        var(--gray-50) 100%);
    background-size: 200% 200%;
    animation: gradientShift 20s ease infinite;
    min-height: 100vh;
    padding: 2rem 0;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Ultra-Modern Glass Morphism Cards */
.card {
    border-radius: 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(40px);
    box-shadow: 
        var(--shadow-xl),
        0 8px 32px rgba(59, 130, 246, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.8s ease;
}

.card:hover::before {
    left: 100%;
}

.card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 
        var(--shadow-xl),
        0 20px 60px rgba(59, 130, 246, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
    border-color: rgba(59, 130, 246, 0.3);
}

.card-header {
    border-radius: 1.5rem 1.5rem 0 0;
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.95), 
        rgba(255, 255, 255, 0.85));
    backdrop-filter: blur(30px);
    border-bottom: 2px solid rgba(203, 213, 225, 0.2);
    position: relative;
}

/* Enhanced Gradient Border Cards */
.border-left-primary {
    border-left: 8px solid;
    border-image: var(--gradient-primary) 1;
    background: linear-gradient(90deg, 
        rgba(59, 130, 246, 0.1) 0%, 
        rgba(139, 92, 246, 0.05) 50%, 
        transparent 100%);
    position: relative;
}

.border-left-primary::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: var(--gradient-primary);
    border-radius: 0 4px 4px 0;
    animation: pulse 3s ease-in-out infinite;
}

.border-left-success {
    border-left: 8px solid;
    border-image: var(--gradient-success) 1;
    background: linear-gradient(90deg, 
        rgba(16, 185, 129, 0.1) 0%, 
        rgba(59, 130, 246, 0.05) 50%, 
        transparent 100%);
}

.border-left-success::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: var(--gradient-success);
    border-radius: 0 4px 4px 0;
    animation: pulse 3s ease-in-out infinite 1s;
}

.border-left-warning {
    border-left: 8px solid;
    border-image: var(--gradient-warning) 1;
    background: linear-gradient(90deg, 
        rgba(249, 115, 22, 0.1) 0%, 
        rgba(239, 68, 68, 0.05) 50%, 
        transparent 100%);
}

.border-left-warning::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: var(--gradient-warning);
    border-radius: 0 4px 4px 0;
    animation: pulse 3s ease-in-out infinite 2s;
}

.border-left-info {
    border-left: 8px solid;
    border-image: var(--gradient-info) 1;
    background: linear-gradient(90deg, 
        rgba(139, 92, 246, 0.1) 0%, 
        rgba(59, 130, 246, 0.05) 50%, 
        transparent 100%);
}

.border-left-info::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 8px;
    background: var(--gradient-info);
    border-radius: 0 4px 4px 0;
    animation: pulse 3s ease-in-out infinite 0.5s;
}

/* Advanced 3D Matrix Relationship Cells */
.relationship-cell {
    padding: 1rem;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 1rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.relationship-cell::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: inherit;
    filter: blur(20px);
    opacity: 0.3;
    z-index: -1;
}

.relationship-cell.allowed {
    background: linear-gradient(135deg, 
        rgba(16, 185, 129, 0.2), 
        rgba(59, 130, 246, 0.1));
    border: 2px solid rgba(16, 185, 129, 0.3);
    box-shadow: 
        var(--shadow-md),
        0 0 20px rgba(16, 185, 129, 0.2);
}

.relationship-cell.allowed:hover {
    transform: scale(1.1) rotateY(10deg);
    box-shadow: 
        var(--shadow-xl),
        0 0 30px rgba(16, 185, 129, 0.4);
    border-color: rgba(16, 185, 129, 0.6);
}

.relationship-cell.not-allowed {
    background: linear-gradient(135deg, 
        rgba(148, 163, 184, 0.1), 
        rgba(203, 213, 225, 0.05));
    border: 2px dashed rgba(148, 163, 184, 0.3);
    box-shadow: var(--shadow);
}

.relationship-cell.not-allowed:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg, 
        rgba(249, 115, 22, 0.1), 
        rgba(239, 68, 68, 0.05));
    border-color: rgba(249, 115, 22, 0.3);
    box-shadow: 
        var(--shadow-lg),
        0 0 20px rgba(249, 115, 22, 0.2);
}

/* Futuristic Table Design */
.table {
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
}

.table th {
    vertical-align: middle;
    border-top: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
    color: var(--gray-700);
    background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
    letter-spacing: 0.1em;
    padding: 1.5rem 1rem;
    position: relative;
}

.table th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--gradient-primary);
}

.table td {
    vertical-align: middle;
    padding: 1.25rem 1rem;
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.table tbody tr {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.table tbody tr:hover {
    background: linear-gradient(135deg, 
        rgba(59, 130, 246, 0.1), 
        rgba(139, 92, 246, 0.05));
    transform: scale(1.02);
    box-shadow: 
        var(--shadow-lg),
        0 0 30px rgba(59, 130, 246, 0.1);
}

/* Modern Typography */
.text-gray-800 { color: var(--gray-800) !important; }
.text-gray-600 { color: var(--gray-600) !important; }
.text-gray-500 { color: var(--gray-500) !important; }
.text-gray-400 { color: var(--gray-400) !important; }
.text-gray-300 { color: var(--gray-300) !important; }

.text-xs { 
    font-size: 0.75rem; 
    font-weight: 500;
    letter-spacing: 0.05em;
}

/* Next-Gen Badges */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    margin: 0.25rem;
    box-shadow: var(--shadow-md);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s ease;
}

.badge:hover::before {
    left: 100%;
}

.badge:hover {
    transform: scale(1.1) rotate(-2deg);
    box-shadow: var(--shadow-xl);
}

.badge-primary {
    background: var(--gradient-primary);
    color: white;
}

.badge-success {
    background: var(--gradient-success);
    color: white;
}

.badge-info {
    background: var(--gradient-info);
    color: white;
}

.badge-warning {
    background: var(--gradient-warning);
    color: white;
}

.badge-sm {
    font-size: 0.65rem;
    padding: 0.35rem 0.75rem;
}

/* Ultramodern Dropdown */
.dropdown-menu {
    border: none;
    box-shadow: 
        var(--shadow-xl),
        0 25px 50px rgba(0, 0, 0, 0.15);
    border-radius: 1.5rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(30px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.dropdown-item {
    border-radius: 1rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    padding: 1rem 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.dropdown-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: var(--gradient-primary);
    transition: left 0.5s ease;
    z-index: -1;
}

.dropdown-item:hover::before {
    left: 0;
}

.dropdown-item:hover {
    color: white;
    transform: translateX(10px) scale(1.05);
    box-shadow: var(--shadow-lg);
}

/* Advanced Animations */
@keyframes pulse {
    0%, 100% { 
        opacity: 1;
        transform: scaleX(1);
    }
    50% { 
        opacity: 0.7;
        transform: scaleX(1.05);
    }
}

@keyframes slideInUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        filter: blur(10px);
    }
    to { 
        opacity: 1; 
        filter: blur(0);
    }
}

@keyframes rotateIn {
    from {
        transform: rotate(-10deg) scale(0.9);
        opacity: 0;
    }
    to {
        transform: rotate(0) scale(1);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slideInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animated--fade-in {
    animation: fadeIn 0.5s ease-out;
}

.rotate-in {
    animation: rotateIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.fs-4 {
    font-size: 1.8rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Ultra-Responsive Design */
@media (max-width: 768px) {
    .football-relationships-dashboard {
        padding: 1rem 0;
    }
    
    .table-responsive {
        font-size: 0.8rem;
        border-radius: 1rem;
        overflow: hidden;
    }
    
    .relationship-cell {
        min-height: 60px;
        padding: 0.75rem;
    }
    
    .btn-group .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
    }
    
    .card {
        margin-bottom: 1.5rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.35rem 0.75rem;
    }
}

/* High-End Focus States */
.btn:focus,
.form-control:focus {
    box-shadow: 
        0 0 0 4px rgba(59, 130, 246, 0.25),
        var(--shadow-lg);
    border-color: var(--rj-blue);
    transform: scale(1.02);
}

/* Premium Alert Styling */
.alert {
    border-radius: 1.5rem;
    border: none;
    box-shadow: var(--shadow-lg);
    background: linear-gradient(135deg, 
        rgba(59, 130, 246, 0.1), 
        rgba(139, 92, 246, 0.05));
    backdrop-filter: blur(10px);
}
</style>

<script>
// Add confirmation for relationship changes
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh page after form submissions to show updated matrix
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('updated') === '1') {
        // Show success message or highlight changes
        console.log('Relationships updated successfully');
    }
});
</script>