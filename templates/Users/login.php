<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="form-wrapper">
    <div class="form-card">
        <div class="form-header">
            <h2>Connexion</h2>
            <p>Connectez-vous à votre compte</p>
        </div>
        
        <?= $this->Flash->render() ?>
        
        <?= $this->Form->create(null, ['class' => 'login-form']) ?>
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
                <?= $this->Form->button('Se connecter', [
                    'class' => 'btn btn-primary btn-block btn-large'
                ]) ?>
            </div>
        <?= $this->Form->end() ?>
        
        <div class="form-footer text-center mt-3">
            <p>Pas encore de compte ?</p>
            <?= $this->Html->link("Créer un compte", ['action' => 'register'], ['class' => 'btn btn-secondary btn-block']) ?>
        </div>
    </div>
</div>

<style>
    .form-footer p {
        margin-bottom: 1rem;
        color: var(--text-light);
    }
    
    .login-form .btn-primary {
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>