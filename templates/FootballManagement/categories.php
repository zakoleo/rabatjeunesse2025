<?php
/**
 * @var \App\View\AppView $this
 * @var array $categories
 */
?>
<div class="football-categories-dashboard content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📋 Football Categories</h2>
            <p class="text-muted mb-0">Gestion des catégories d'âge pour le football</p>
        </div>
        <div class="btn-group">
            <?= $this->Html->link('⚽ Dashboard', ['action' => 'index'], ['class' => 'btn btn-outline-secondary btn-modern']) ?>
            <?= $this->Html->link('➕ Nouvelle Catégorie', ['action' => 'addCategory'], ['class' => 'btn btn-primary btn-modern']) ?>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">📋 Liste des Catégories d'Âge</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                    <?= $this->Html->link('<i class="fas fa-plus fa-sm"></i> Nouvelle Catégorie', 
                        ['action' => 'addCategory'], 
                        ['class' => 'dropdown-item', 'escape' => false]) ?>
                    <?= $this->Html->link('<i class="fas fa-sync fa-sm"></i> Actualiser', 
                        ['action' => 'categories'], 
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
                            <th>Catégorie</th>
                            <th>Période de Naissance</th>
                            <th>Types de Football</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="text-center">
                                <span class="badge badge-light"><?= $category->id ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-tag text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold"><?= h($category->name) ?></div>
                                        <div class="small text-gray-600">
                                            Années: <?= $category->min_birth_year ?>-<?= $category->max_birth_year ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($category->min_date && $category->max_date): ?>
                                    <div class="text-sm">
                                        <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                        <strong>Du:</strong> <?= h(is_string($category->min_date) ? $category->min_date : $category->min_date->format('d/m/Y')) ?><br>
                                        <i class="fas fa-calendar-alt text-info mr-1"></i>
                                        <strong>Au:</strong> <?= h(is_string($category->max_date) ? $category->max_date : $category->max_date->format('d/m/Y')) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Non défini
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($category->football_types)): ?>
                                    <div class="mb-1">
                                        <?php foreach ($category->football_types as $type): ?>
                                            <span class="badge badge-info mr-1 mb-1" title="<?= h($type->name) ?>">
                                                <?= h($type->code) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <?= count($category->football_types) ?> type(s) configuré(s)
                                    </small>
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="fas fa-exclamation-triangle text-warning mb-2"></i><br>
                                        <span class="text-warning small">Aucun type assigné</span><br>
                                        <?= $this->Html->link(
                                            '<i class="fas fa-plus"></i> Configurer',
                                            ['action' => 'manageRelationships', $category->id],
                                            ['class' => 'btn btn-sm btn-outline-success mt-1', 'escape' => false]
                                        ) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($category->active): ?>
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
                                <div class="btn-group" role="group">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-edit"></i>',
                                        ['action' => 'editCategory', $category->id],
                                        ['class' => 'btn btn-sm btn-primary', 'escape' => false, 'title' => 'Modifier']
                                    ) ?>
                                    <?= $this->Html->link(
                                        '<i class="fas fa-link"></i>',
                                        ['action' => 'manageRelationships', $category->id],
                                        ['class' => 'btn btn-sm btn-success', 'escape' => false, 'title' => 'Relations']
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        ['action' => 'deleteCategory', $category->id],
                                        [
                                            'confirm' => 'Êtes-vous sûr de vouloir supprimer la catégorie "' . $category->name . '"?',
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

                <?php if (empty($categories)): ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-users fa-4x text-gray-300"></i>
                        </div>
                        <h4 class="text-gray-500">Aucune catégorie d'âge trouvée</h4>
                        <p class="text-gray-400 mb-4">Commencez par créer votre première catégorie d'âge pour le football.</p>
                        <?= $this->Html->link(
                            '<i class="fas fa-plus"></i> Créer la Première Catégorie',
                            ['action' => 'addCategory'],
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
    
    /* Gradients */
    --gradient-blue: linear-gradient(135deg, var(--rj-blue) 0%, var(--rj-purple) 100%);
    --gradient-green: linear-gradient(135deg, var(--rj-green) 0%, var(--rj-blue) 100%);
    --gradient-orange: linear-gradient(135deg, var(--rj-orange) 0%, var(--rj-red) 100%);
    --gradient-purple: linear-gradient(135deg, var(--rj-purple) 0%, var(--rj-blue) 100%);
}

/* Modern Dashboard Background */
.football-categories-dashboard {
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Modern Cards */
.card {
    border-radius: 1rem;
    border: 1px solid var(--gray-200);
    box-shadow: var(--shadow);
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
    box-shadow: var(--shadow-xl);
}

.card-header {
    border-radius: 1rem 1rem 0 0;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--gray-200);
}

/* Modern Border Left Cards */
.border-left-primary {
    border-left: 5px solid var(--rj-blue) !important;
    background: linear-gradient(90deg, rgba(59, 130, 246, 0.08) 0%, transparent 50%);
}

.border-left-success {
    border-left: 5px solid var(--rj-green) !important;
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
}

.border-left-info {
    border-left: 5px solid var(--rj-purple) !important;
    background: linear-gradient(90deg, rgba(139, 92, 246, 0.08) 0%, transparent 50%);
}

.border-left-warning {
    border-left: 5px solid var(--rj-orange) !important;
    background: linear-gradient(90deg, rgba(249, 115, 22, 0.08) 0%, transparent 50%);
}

/* Modern Icon Circles */
.icon-circle {
    height: 3.5rem;
    width: 3.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
}

.icon-circle.bg-primary {
    background: var(--gradient-blue);
}

.icon-circle.bg-success {
    background: var(--gradient-green);
}

.icon-circle.bg-info {
    background: var(--gradient-purple);
}

.icon-circle.bg-warning {
    background: var(--gradient-orange);
}

.icon-circle:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-lg);
}

/* Modern Text Colors */
.text-gray-800 { color: var(--gray-800) !important; }
.text-gray-600 { color: var(--gray-600) !important; }
.text-gray-500 { color: var(--gray-500) !important; }
.text-gray-400 { color: var(--gray-400) !important; }
.text-gray-300 { color: var(--gray-300) !important; }

.text-rj-blue { color: var(--rj-blue) !important; }
.text-rj-green { color: var(--rj-green) !important; }
.text-rj-orange { color: var(--rj-orange) !important; }
.text-rj-purple { color: var(--rj-purple) !important; }

.text-xs { font-size: 12px; }
.text-sm { font-size: 14px; }

/* Modern Buttons */
.btn-modern {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow);
    border: none;
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.btn-primary.btn-modern {
    background: var(--gradient-blue);
}

.btn-success.btn-modern {
    background: var(--gradient-green);
}

.btn-info.btn-modern {
    background: var(--gradient-purple);
}

.btn-warning.btn-modern {
    background: var(--gradient-orange);
}

.btn-outline-primary.btn-modern {
    border: 2px solid var(--rj-blue);
    color: var(--rj-blue);
    background: transparent;
}

.btn-outline-primary.btn-modern:hover {
    background: var(--gradient-blue);
    color: white;
}

.btn-outline-success.btn-modern {
    border: 2px solid var(--rj-green);
    color: var(--rj-green);
    background: transparent;
}

.btn-outline-success.btn-modern:hover {
    background: var(--gradient-green);
    color: white;
}

/* Modern Badges */
.badge {
    font-size: 12px;
    font-weight: 500;
    padding: 0.35rem 0.65rem;
    border-radius: 0.5rem;
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
}

.badge-primary {
    background: var(--gradient-blue);
}

.badge-success {
    background: var(--gradient-green);
}

.badge-info {
    background: var(--gradient-purple);
}

.badge-warning {
    background: var(--gradient-orange);
}

.badge-pill {
    border-radius: 1rem;
}

/* Modern Table */
.table {
    border-radius: 0.75rem;
    overflow: hidden;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    color: var(--gray-600);
    background: var(--gray-50);
    letter-spacing: 0.05em;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: var(--gray-50);
    transform: scale(1.01);
}

/* Modern Dropdown */
.dropdown-menu {
    border: none;
    box-shadow: var(--shadow-lg);
    border-radius: 0.75rem;
    padding: 0.5rem;
}

.dropdown-item {
    border-radius: 0.5rem;
    margin-bottom: 0.25rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, var(--rj-blue), var(--rj-purple));
    color: white;
    transform: translateX(4px);
}

/* Glass Effect Cards */
.glass-card {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}

/* Animations */
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

.animate-slide-in {
    animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.animated--fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Responsive Design */
@media (max-width: 768px) {
    .football-categories-dashboard {
        padding: 1rem 0;
    }
    
    .icon-circle {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 16px;
    }
    
    .card {
        margin-bottom: 1rem;
    }
}

/* Modern Focus States */
.btn:focus,
.form-control:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: var(--rj-blue);
}

/* Modern Alert */
.alert {
    border-radius: 0.75rem;
    border: none;
    box-shadow: var(--shadow);
}

.alert-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
    color: var(--gray-700);
}
</style>