<?php
/**
 * @var \App\View\AppView $this
 * @var array $stats
 * @var array $categories
 */
?>
<div class="football-management-dashboard content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">⚽ Football Dashboard</h2>
            <p class="text-muted mb-0">Configuration des catégories d'âge et types de terrain</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('📋 Catégories', ['action' => 'categories'], ['class' => 'btn btn-primary btn-modern']) ?>
            <?= $this->Html->link('🏟️ Types', ['action' => 'types'], ['class' => 'btn btn-info btn-modern']) ?>
            <?= $this->Html->link('🔗 Relations', ['action' => 'relationships'], ['class' => 'btn btn-success btn-modern']) ?>
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
                                Catégories d'âge
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['categories_count'] ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle">
                                <i class="fas fa-users"></i>
                            </div>
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
                                Types de terrain
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['types_count'] ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle info">
                                <i class="fas fa-futbol"></i>
                            </div>
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
                                Relations actives
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['relationships_count'] ?></div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle success">
                                <i class="fas fa-link"></i>
                            </div>
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
                                Équipes inscrites
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                // Count football teams
                                $teamsCount = 0;
                                try {
                                    $teamsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('NewTeams');
                                    $teamsCount = $teamsTable->find()->where(['sport_id' => 1])->count();
                                } catch (Exception $e) {
                                    $teamsCount = '—';
                                }
                                echo $teamsCount;
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="modern-icon-circle warning">
                                <i class="fas fa-trophy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Cards -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">📋 Gestion des Catégories</h6>
                    <?= $this->Html->link('Voir tout', ['action' => 'categories'], ['class' => 'btn btn-primary btn-modern btn-sm']) ?>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Gérez les catégories d'âge (-12, -15, +19, Senior...)</p>
                    <div class="row">
                        <div class="col-6">
                            <?= $this->Html->link('➕ Nouvelle Catégorie', ['action' => 'addCategory'], 
                                ['class' => 'btn btn-primary btn-modern btn-block mb-2']) ?>
                        </div>
                        <div class="col-6">
                            <?= $this->Html->link('📋 Liste Complète', ['action' => 'categories'], 
                                ['class' => 'btn btn-outline-primary btn-modern btn-block mb-2']) ?>
                        </div>
                    </div>
                    
                    <!-- Recent Categories Preview -->
                    <div class="mt-3">
                        <small class="text-muted d-block mb-2">Catégories récentes:</small>
                        <div class="d-flex flex-wrap">
                            <?php $count = 0; foreach ($categories as $category): if ($count >= 6) break; ?>
                                <span class="badge badge-primary mr-1 mb-1"><?= h($category->name) ?></span>
                            <?php $count++; endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">🏟️ Gestion des Types</h6>
                    <?= $this->Html->link('Voir tout', ['action' => 'types'], ['class' => 'btn btn-info btn-modern btn-sm']) ?>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Gérez les formats de terrain (5x5, 6x6, 11x11...)</p>
                    <div class="row">
                        <div class="col-6">
                            <?= $this->Html->link('🏟️ Nouveau Type', ['action' => 'addType'], 
                                ['class' => 'btn btn-info btn-modern btn-block mb-2']) ?>
                        </div>
                        <div class="col-6">
                            <?= $this->Html->link('🏟️ Liste Complète', ['action' => 'types'], 
                                ['class' => 'btn btn-outline-info btn-modern btn-block mb-2']) ?>
                        </div>
                    </div>
                    
                    <!-- Types Preview -->
                    <div class="mt-3">
                        <small class="text-muted d-block mb-2">Types disponibles:</small>
                        <div class="d-flex flex-wrap">
                            <?php 
                            $types = \Cake\ORM\TableRegistry::getTableLocator()->get('FootballTypes')
                                ->find()->where(['active' => 1])->limit(4)->toArray();
                            foreach ($types as $type): 
                            ?>
                                <span class="badge badge-info mr-1 mb-1"><?= h($type->code) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Relationships Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">🔗 Aperçu des Relations</h6>
                    <?= $this->Html->link('Gérer Relations', ['action' => 'relationships'], ['class' => 'btn btn-success btn-modern btn-sm']) ?>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Configuration des associations catégories ↔ types de terrain</p>
                    
                    <div class="row">
                        <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card border-left-<?= !empty($category->football_types) ? 'success' : 'danger' ?> h-100">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <?php if (!empty($category->football_types)): ?>
                                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold"><?= h($category->name) ?></div>
                                                <div class="small text-muted">
                                                    <?php if (!empty($category->football_types)): ?>
                                                        <?= count($category->football_types) ?> type(s)
                                                    <?php else: ?>
                                                        Aucun type assigné
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($category->football_types)): ?>
                                                    <div class="mt-1">
                                                        <?php foreach (array_slice($category->football_types, 0, 3) as $type): ?>
                                                            <span class="badge badge-success badge-sm"><?= h($type->code) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold">
                                        <?= count(array_filter($categories, function($cat) { return !empty($cat->football_types); })) ?>
                                    </div>
                                    <div class="small text-muted">Catégories configurées</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold">
                                        <?= count(array_filter($categories, function($cat) { return empty($cat->football_types); })) ?>
                                    </div>
                                    <div class="small text-muted">À configurer</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-chart-line text-info fa-lg"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold">
                                        <?php 
                                        $totalRelations = array_sum(array_map(function($cat) { 
                                            return count($cat->football_types); 
                                        }, $categories));
                                        echo $totalRelations;
                                        ?>
                                    </div>
                                    <div class="small text-muted">Relations totales</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity or System Status -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-gray-800">📊 État du Système</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3">✅ Système Prêt</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Base de données configurée
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Relations modèle créées
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Interface d'administration active
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Validation formulaires configurée
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3">⚡ Actions Rapides</h6>
                            <div class="d-grid gap-2">
                                <?= $this->Html->link('🎯 Attribution Recommandée', 
                                    ['action' => 'assignRecommended'], 
                                    ['class' => 'btn btn-warning btn-modern btn-sm mb-2']) ?>
                                <?= $this->Html->link('📊 Rapport Complet', 
                                    ['action' => 'report'], 
                                    ['class' => 'btn btn-secondary btn-modern btn-sm mb-2']) ?>
                                <?= $this->Html->link('🔄 Synchroniser', 
                                    ['action' => 'sync'], 
                                    ['class' => 'btn btn-info btn-modern btn-sm mb-2']) ?>
                            </div>
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
    
    /* Modern Shadows */
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    
    /* Modern Radius */
    --radius-sm: 0.375rem;
    --radius: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.5rem;
    
    /* Gradients */
    --gradient-primary: linear-gradient(135deg, var(--rj-blue) 0%, var(--rj-purple) 100%);
    --gradient-success: linear-gradient(135deg, var(--rj-green) 0%, var(--rj-blue) 100%);
    --gradient-warning: linear-gradient(135deg, var(--rj-orange) 0%, var(--rj-red) 100%);
    --gradient-info: linear-gradient(135deg, var(--rj-purple) 0%, var(--rj-blue) 100%);
}

/* Modern Dashboard Styles */
.football-management-dashboard {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

.modern-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(203, 213, 225, 0.3);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.border-left-primary {
    border-left: 4px solid var(--rj-blue) !important;
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
}

.border-left-info {
    border-left: 4px solid var(--rj-purple) !important;
    background: linear-gradient(90deg, rgba(139, 92, 246, 0.1) 0%, transparent 100%);
}

.border-left-success {
    border-left: 4px solid var(--rj-green) !important;
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.1) 0%, transparent 100%);
}

.border-left-warning {
    border-left: 4px solid var(--rj-orange) !important;
    background: linear-gradient(90deg, rgba(249, 115, 22, 0.1) 0%, transparent 100%);
}

.border-left-danger {
    border-left: 4px solid var(--rj-red) !important;
    background: linear-gradient(90deg, rgba(239, 68, 68, 0.1) 0%, transparent 100%);
}

.modern-icon-circle {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    color: white;
    font-size: 1.25rem;
    box-shadow: var(--shadow-md);
}

.modern-icon-circle.success {
    background: var(--gradient-success);
}

.modern-icon-circle.warning {
    background: var(--gradient-warning);
}

.modern-icon-circle.info {
    background: var(--gradient-info);
}

.text-rj-blue { color: var(--rj-blue) !important; }
.text-rj-orange { color: var(--rj-orange) !important; }
.text-rj-green { color: var(--rj-green) !important; }
.text-rj-red { color: var(--rj-red) !important; }
.text-rj-purple { color: var(--rj-purple) !important; }

.text-gray-800 { color: var(--gray-800) !important; }
.text-gray-600 { color: var(--gray-600) !important; }
.text-gray-500 { color: var(--gray-500) !important; }
.text-gray-400 { color: var(--gray-400) !important; }
.text-gray-300 { color: var(--gray-300) !important; }

.text-xs { font-size: 0.75rem; }
.text-sm { font-size: 0.875rem; }

/* Modern Buttons */
.btn-modern {
    border-radius: var(--radius);
    font-weight: 500;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-sm);
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-primary.btn-modern {
    background: var(--gradient-primary);
    border: none;
}

.btn-success.btn-modern {
    background: var(--gradient-success);
    border: none;
}

.btn-warning.btn-modern {
    background: var(--gradient-warning);
    border: none;
}

.btn-info.btn-modern {
    background: var(--gradient-info);
    border: none;
}

/* Modern Badge */
.badge-modern {
    border-radius: var(--radius-sm);
    font-weight: 500;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
}

/* Modern Shadows */
.shadow { box-shadow: var(--shadow) !important; }
.shadow-md { box-shadow: var(--shadow-md) !important; }
.shadow-lg { box-shadow: var(--shadow-lg) !important; }
.shadow-xl { box-shadow: var(--shadow-xl) !important; }

/* Modern Card */
.card {
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow);
}

.card-header {
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
}

/* Responsive */
@media (max-width: 768px) {
    .football-management-dashboard {
        padding: 1rem 0;
    }
    
    .modern-icon-circle {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1rem;
    }
}

/* Animation */
@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slideInUp 0.5s ease-out;
}

/* Glass effect */
.glass-card {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: var(--radius-xl);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}
</style>