<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="form-wrapper">
    <div class="form-card">
        <div class="form-header">
            <h2>Inscription</h2>
            <p>Créez votre compte pour participer aux tournois</p>
        </div>
        
        <?= $this->Flash->render() ?>
        
        <?= $this->Form->create($user, ['class' => 'register-form']) ?>
            <div class="form-group">
                <?= $this->Form->control('username', [
                    'label' => 'Nom d\'utilisateur',
                    'required' => true,
                    'placeholder' => 'Votre nom d\'utilisateur'
                ]) ?>
            </div>
            
            <div class="form-group">
                <?= $this->Form->control('email', [
                    'label' => 'Adresse email',
                    'type' => 'email',
                    'required' => true,
                    'placeholder' => 'votre@email.com'
                ]) ?>
            </div>
            
            <div class="form-group">
                <?= $this->Form->control('password', [
                    'label' => 'Mot de passe',
                    'type' => 'password',
                    'required' => true,
                    'placeholder' => '••••••••'
                ]) ?>
            </div>
            
            <div class="form-group">
                <?= $this->Form->button('S\'inscrire', [
                    'class' => 'btn btn-primary btn-block btn-large'
                ]) ?>
            </div>
        <?= $this->Form->end() ?>
        
        <div class="form-footer text-center mt-3">
            <p>Déjà un compte ?</p>
            <?= $this->Html->link("Se connecter", ['action' => 'login'], ['class' => 'btn btn-secondary btn-block']) ?>
        </div>
    </div>
</div>