<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 */
?>
<div class="teams form">
    <h3>Test d'inscription simple</h3>
    
    <?= $this->Form->create($team) ?>
    <fieldset>
        <legend>Équipe</legend>
        <?= $this->Form->control('nom_equipe') ?>
        <?= $this->Form->control('categorie', [
            'options' => ['U12' => 'U12', 'U15' => 'U15', 'U18' => 'U18', '18PLUS' => '18+']
        ]) ?>
        <?= $this->Form->control('genre', [
            'options' => ['Homme' => 'Homme', 'Femme' => 'Femme']
        ]) ?>
        <?= $this->Form->control('type_football', [
            'options' => ['5x5' => '5x5', '6x6' => '6x6', '11x11' => '11x11']
        ]) ?>
        <?= $this->Form->control('district', [
            'options' => [
                'Souissi' => 'Souissi',
                'Agdal-RYAD' => 'Agdal-RYAD',
                'El Youssoufia' => 'El Youssoufia',
                'Yacoub elmansour' => 'Yacoub elmansour',
                'Hassan' => 'Hassan'
            ]
        ]) ?>
        <?= $this->Form->control('organisation', [
            'options' => ['Association' => 'Association', 'Club' => 'Club', 'Particulier' => 'Particulier']
        ]) ?>
        <?= $this->Form->control('adresse', ['type' => 'textarea']) ?>
    </fieldset>
    
    <fieldset>
        <legend>Responsable</legend>
        <?= $this->Form->control('responsables.0.nom_complet', ['label' => 'Nom complet']) ?>
        <?= $this->Form->control('responsables.0.date_naissance', ['type' => 'date']) ?>
        <?= $this->Form->control('responsables.0.tel', ['label' => 'Téléphone']) ?>
        <?= $this->Form->control('responsables.0.whatsapp') ?>
    </fieldset>
    
    <fieldset>
        <legend>Entraîneur</legend>
        <?= $this->Form->control('entraineurs.0.nom_complet', ['label' => 'Nom complet']) ?>
        <?= $this->Form->control('entraineurs.0.date_naissance', ['type' => 'date']) ?>
        <?= $this->Form->control('entraineurs.0.tel', ['label' => 'Téléphone']) ?>
        <?= $this->Form->control('entraineurs.0.same_as_responsable', ['type' => 'checkbox']) ?>
    </fieldset>
    
    <fieldset>
        <legend>Joueurs</legend>
        <?php for($i = 0; $i < 5; $i++): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <h4>Joueur <?= $i + 1 ?></h4>
            <?= $this->Form->control("joueurs.$i.nom_complet", ['label' => 'Nom complet']) ?>
            <?= $this->Form->control("joueurs.$i.date_naissance", ['type' => 'date']) ?>
            <?= $this->Form->control("joueurs.$i.identifiant", ['label' => 'CIN/Code Massar']) ?>
            <?= $this->Form->control("joueurs.$i.taille_vestimentaire", [
                'options' => ['XS' => 'XS', 'S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL']
            ]) ?>
        </div>
        <?php endfor; ?>
    </fieldset>
    
    <?= $this->Form->button(__('Sauvegarder')) ?>
    <?= $this->Form->end() ?>
</div>