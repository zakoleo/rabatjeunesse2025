<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CrosstrainingParticipant $participant
 * @var array $categories
 */
?>
<div class="crosstraining-participants edit">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Modifier l'inscription Cross Training</h3>
                    </div>
                    <div class="card-body">
                        <?= $this->Form->create($participant, ['type' => 'file']) ?>
                        
                        <?php if ($participant->status === 'verified'): ?>
                        <div class="alert alert-warning">
                            <strong>Attention :</strong> Cette inscription a déjà été vérifiée. Vous ne pouvez pas la modifier.
                        </div>
                        <?php else: ?>
                        
                        <h5 class="mb-3">Informations personnelles</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('nom_complet', [
                                    'label' => 'Nom complet',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('date_naissance', [
                                    'label' => 'Date de naissance',
                                    'type' => 'date',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('lieu_naissance', [
                                    'label' => 'Lieu de naissance',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('gender', [
                                    'label' => 'Genre',
                                    'options' => ['Homme' => 'Homme', 'Femme' => 'Femme'],
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->control('category_id', [
                                    'label' => 'Catégorie',
                                    'options' => $categories,
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Coordonnées</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('cin', [
                                    'label' => 'CIN',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('telephone', [
                                    'label' => 'Téléphone',
                                    'type' => 'tel',
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('whatsapp', [
                                    'label' => 'Téléphone WhatsApp',
                                    'type' => 'tel',
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('email', [
                                    'label' => 'Email',
                                    'type' => 'email',
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('taille_tshirt', [
                                    'label' => 'Taille',
                                    'options' => ['XS' => 'XS', 'S' => 'S', 'M' => 'M', 'L' => 'L', 'XL' => 'XL', 'XXL' => 'XXL'],
                                    'class' => 'form-control',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Documents</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('photo', [
                                    'label' => 'Photo du participant' . ($participant->photo ? ' (déjà fourni)' : ''),
                                    'type' => 'file',
                                    'class' => 'form-control',
                                    'accept' => 'image/*',
                                    'required' => false
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('certificat_medical', [
                                    'label' => 'Certificat médical' . ($participant->certificat_medical ? ' (déjà fourni)' : ''),
                                    'type' => 'file',
                                    'class' => 'form-control',
                                    'accept' => 'image/*,application/pdf',
                                    'required' => false
                                ]) ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->Form->control('cin_recto', [
                                    'label' => 'CIN Recto' . ($participant->cin_recto ? ' (déjà fourni)' : ''),
                                    'type' => 'file',
                                    'class' => 'form-control',
                                    'accept' => 'image/*',
                                    'required' => false
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->Form->control('cin_verso', [
                                    'label' => 'CIN Verso' . ($participant->cin_verso ? ' (déjà fourni)' : ''),
                                    'type' => 'file',
                                    'class' => 'form-control',
                                    'accept' => 'image/*',
                                    'required' => false
                                ]) ?>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <?= $this->Form->button(__('Enregistrer les modifications'), [
                                    'class' => 'btn btn-primary'
                                ]) ?>
                                <?= $this->Html->link(__('Annuler'), 
                                    ['action' => 'view', $participant->id], 
                                    ['class' => 'btn btn-secondary']
                                ) ?>
                            </div>
                        </div>
                        
                        <?php endif; ?>
                        
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>