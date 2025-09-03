<?php
/**
 * Admin Users Template - Consistent with other admin pages
 * @var \App\View\AppView $this
 * @var array $users
 * @var string $title
 */

$this->assign('title', 'Gestion des utilisateurs');
?>

<div class="admin-page admin-users-page">
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="header-content">
            <div class="header-info">
                <h1><i class="fas fa-user-friends"></i> Gestion des Utilisateurs</h1>
                <p>Gérez les comptes utilisateurs, les permissions et les accès administrateurs</p>
                <div class="stats-summary">
                    <span class="stat-item">
                        <strong><?= is_array($users) ? count($users) : 0 ?></strong> utilisateurs total
                    </span>
                </div>
            </div>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-user-plus"></i> Nouvel Utilisateur', 
                    ['action' => 'addUser'], 
                    ['class' => 'btn btn-primary header-btn', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-download"></i> Exporter', 
                    ['action' => 'exportUsers'], 
                    ['class' => 'btn btn-secondary header-btn', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <!-- Users Management Section -->
    <div class="content-section users-management-section">
        <div class="widget-header">
            <h3><i class="fas fa-table"></i> Liste des Utilisateurs</h3>
            <div class="widget-actions">
                <?= $this->Html->link('<i class="fas fa-filter"></i> Filtrer', '#', ['class' => 'btn btn-sm btn-outline-primary', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-search"></i> Rechercher', '#', ['class' => 'btn btn-sm btn-outline-secondary', 'escape' => false]) ?>
            </div>
        </div>
        
        <?php if (!empty($users) && is_array($users)): ?>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Utilisateur</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-shield-alt"></i> Rôle</th>
                            <th><i class="fas fa-calendar"></i> Inscription</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php 
                            // Determine if user is admin
                            $userIsAdmin = false;
                            try {
                                if (isset($user->is_admin) && ($user->is_admin == 1 || $user->is_admin === true)) {
                                    $userIsAdmin = true;
                                }
                            } catch (\Exception $e) {
                                // Field might not exist
                            }
                            
                            if (!$userIsAdmin) {
                                $adminEmails = ['admin@lifemoz.com', 'zouhair@gmail.com', 'admin@rabatjeunesse.ma'];
                                $adminUsernames = ['admin', 'administrator', 'admin1', 'Zouhair'];
                                
                                if (in_array($user->email, $adminEmails) || 
                                    in_array($user->username, $adminUsernames) || 
                                    $user->id == 1) {
                                    $userIsAdmin = true;
                                }
                            }
                            ?>
                            <tr class="user-row">
                                <td>
                                    <span class="user-id"><?= $user->id ?></span>
                                </td>
                                <td class="user-info">
                                    <div class="user-details">
                                        <div class="username"><?= h($user->username) ?></div>
                                        <?php if ($userIsAdmin): ?>
                                            <span class="role-badge admin">
                                                <i class="fas fa-crown"></i> Administrateur
                                            </span>
                                        <?php else: ?>
                                            <span class="role-badge user">
                                                <i class="fas fa-user"></i> Utilisateur
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="email-info">
                                        <span class="email"><?= h($user->email) ?></span>
                                        <span class="email-verified">
                                            <i class="fas fa-check-circle"></i> Vérifié
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($userIsAdmin): ?>
                                        <span class="status-badge admin">
                                            <i class="fas fa-user-shield"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge user">
                                            <i class="fas fa-user-circle"></i> Utilisateur
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="date-info">
                                    <div class="date"><?= $user->created->format('d/m/Y') ?></div>
                                    <small class="time"><?= $user->created->format('H:i') ?></small>
                                </td>
                                <td class="actions">
                                    <?= $this->Html->link(
                                        '<i class="fas fa-eye"></i>',
                                        ['action' => 'userView', $user->id],
                                        ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir détails']
                                    ) ?>
                                    
                                    <?php if (!$userIsAdmin): ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-user-shield"></i>',
                                            ['action' => 'makeAdmin', $user->id],
                                            [
                                                'class' => 'btn btn-sm btn-warning',
                                                'escape' => false,
                                                'title' => 'Promouvoir admin',
                                                'confirm' => 'Promouvoir cet utilisateur en tant qu\'administrateur ?'
                                            ]
                                        ) ?>
                                        <?= $this->Form->postLink(
                                            '<i class="fas fa-trash"></i>',
                                            ['action' => 'deleteUser', $user->id],
                                            [
                                                'class' => 'btn btn-sm btn-danger',
                                                'escape' => false,
                                                'title' => 'Supprimer',
                                                'confirm' => 'Êtes-vous sûr de vouloir supprimer cet utilisateur ?'
                                            ]
                                        ) ?>
                                    <?php else: ?>
                                        <span class="admin-protected">
                                            <i class="fas fa-lock" title="Compte protégé"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users fa-4x"></i>
                <h3>Aucun utilisateur trouvé</h3>
                <p>Aucun utilisateur n'est encore inscrit dans le système.</p>
                <?= $this->Html->link('<i class="fas fa-user-plus"></i> Ajouter le premier utilisateur', 
                    ['action' => 'addUser'], 
                    ['class' => 'btn btn-primary', 'escape' => false]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

