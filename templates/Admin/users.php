<?php
/**
 * Admin Users Template - Consistent with other admin pages
 * @var \App\View\AppView $this
 * @var array $users
 * @var string $title
 */

$this->assign('title', 'Administration - Gestion des utilisateurs');

// Add admin CSS
echo $this->Html->css('admin');
?>

<div class="admin-users">
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-user-friends"></i> Gestion des utilisateurs</h1>
            <div class="header-actions">
                <?= $this->Html->link('<i class="fas fa-tachometer-alt"></i> Dashboard', ['action' => 'index'], ['class' => 'btn btn-secondary', 'escape' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-download"></i> Export', ['action' => 'exportUsers'], ['class' => 'btn btn-success', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-section">
        <div class="section-header">
            <h2><i class="fas fa-users"></i> Utilisateurs inscrits (<?= is_array($users) ? count($users) : 0 ?>)</h2>
        </div>
        
        <?php if (!empty($users) && is_array($users)): ?>
            <div class="users-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="user-row">
                                <td><?= $user->id ?></td>
                                <td class="username">
                                    <?= h($user->username) ?>
                                    <?php 
                                    $userIsAdmin = false;
                                    try {
                                        if ($user->is_admin == 1 || $user->is_admin === true) {
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
                                    
                                    if ($userIsAdmin): ?>
                                        <span class="admin-badge">
                                            <i class="fas fa-crown"></i> Admin
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= h($user->email) ?></td>
                                <td>
                                    <span class="status-badge status-active">
                                        <i class="fas fa-check-circle"></i> Actif
                                    </span>
                                </td>
                                <td>
                                    <div><?= $user->created->format('d/m/Y') ?></div>
                                    <small class="text-muted"><?= $user->created->format('H:i') ?></small>
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
                                    <?php endif; ?>
                                    
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
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users fa-3x"></i>
                <h3>Aucun utilisateur trouvé</h3>
                <p>Aucun utilisateur n'est encore inscrit.</p>
            </div>
        <?php endif; ?>
    </div>
</div>