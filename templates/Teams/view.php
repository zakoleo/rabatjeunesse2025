<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Team $team
 */
?>
<div class="page-header">
    <div class="container">
        <h1><?= h($team->nom_equipe) ?></h1>
        <p>Détails de l'équipe inscrite</p>
    </div>
</div>

<div class="content-section">
    <div class="container">
        <div class="actions mb-4">
            <?= $this->Html->link('Modifier', ['action' => 'edit', $team->id], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('Liste des équipes', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Html->link('Nouvelle équipe', ['action' => 'add'], ['class' => 'btn btn-success']) ?>
            <?= $this->Form->postLink('Supprimer', ['action' => 'delete', $team->id], [
                'confirm' => 'Êtes-vous sûr de vouloir supprimer cette équipe ?',
                'class' => 'btn btn-danger float-right'
            ]) ?>
        </div>

        <div class="grid grid-2">
            <!-- Informations sur l'équipe -->
            <div class="card">
                <div class="card-header">
                    <h3>Informations sur l'équipe</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th><?= __('Nom de l\'équipe') ?></th>
                            <td><?= h($team->nom_equipe) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Catégorie') ?></th>
                            <td><?= h($team->categorie) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Genre') ?></th>
                            <td><?= h($team->genre) ?></td>
                        </tr>
                        <?php if (!empty($team->type_football)): ?>
                        <tr>
                            <th><?= __('Type de football') ?></th>
                            <td><span class="badge badge-info"><?= h($team->type_football) ?></span></td>
                        </tr>
                        <?php elseif (!empty($team->type_basketball)): ?>
                        <tr>
                            <th><?= __('Type de basketball') ?></th>
                            <td><span class="badge badge-info"><?= h($team->type_basketball) ?></span></td>
                        </tr>
                        <?php elseif (!empty($team->type_handball)): ?>
                        <tr>
                            <th><?= __('Type de handball') ?></th>
                            <td><span class="badge badge-info"><?= h($team->type_handball) ?></span></td>
                        </tr>
                        <?php elseif (!empty($team->type_volleyball)): ?>
                        <tr>
                            <th><?= __('Type de volleyball') ?></th>
                            <td><span class="badge badge-info"><?= h($team->type_volleyball) ?></span></td>
                        </tr>
                        <?php elseif (!empty($team->type_beachvolley)): ?>
                        <tr>
                            <th><?= __('Type de beach volleyball') ?></th>
                            <td><span class="badge badge-info"><?= h($team->type_beachvolley) ?></span></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th><?= __('District') ?></th>
                            <td><?= h($team->district) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Organisation') ?></th>
                            <td><?= h($team->organisation) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Date d\'inscription') ?></th>
                            <td><?= h($team->created->format('d/m/Y H:i')) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Adresse -->
            <div class="card">
                <div class="card-header">
                    <h3>Adresse</h3>
                </div>
                <div class="card-body">
                    <p><?= nl2br(h($team->adresse)) ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-2 mt-4">
            <!-- Responsable de l'équipe -->
            <div class="card">
                <div class="card-header">
                    <h3>Responsable de l'équipe</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th><?= __('Nom complet') ?></th>
                            <td><?= h($team->responsable_nom_complet) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Date de naissance') ?></th>
                            <td><?= h($team->responsable_date_naissance ? $team->responsable_date_naissance->format('d/m/Y') : '') ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Téléphone') ?></th>
                            <td><?= h($team->responsable_tel) ?></td>
                        </tr>
                        <?php if ($team->responsable_whatsapp): ?>
                        <tr>
                            <th><?= __('WhatsApp') ?></th>
                            <td><?= h($team->responsable_whatsapp) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if ($team->responsable_cin_recto || $team->responsable_cin_verso): ?>
                    <div class="mt-3">
                        <h5>Documents d'identité</h5>
                        <div class="row">
                            <?php if ($team->responsable_cin_recto): ?>
                            <div class="col-md-6 mb-2">
                                <p class="small text-muted mb-1">CIN/Passeport - Recto</p>
                                <img src="<?= $this->Url->build('/' . h($team->responsable_cin_recto)) ?>" 
                                     alt="CIN Recto" 
                                     class="img-fluid document-image" 
                                     onclick="openImageModal(this)">
                            </div>
                            <?php endif; ?>
                            <?php if ($team->responsable_cin_verso): ?>
                            <div class="col-md-6 mb-2">
                                <p class="small text-muted mb-1">CIN/Passeport - Verso</p>
                                <img src="<?= $this->Url->build('/' . h($team->responsable_cin_verso)) ?>" 
                                     alt="CIN Verso" 
                                     class="img-fluid document-image" 
                                     onclick="openImageModal(this)">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Entraîneur de l'équipe -->
            <div class="card">
                <div class="card-header">
                    <h3>Entraîneur de l'équipe</h3>
                </div>
                <div class="card-body">
                    <?php if ($team->entraineur_same_as_responsable): ?>
                        <div class="alert alert-info">
                            L'entraîneur est la même personne que le responsable
                        </div>
                    <?php else: ?>
                        <table class="table table-borderless">
                            <tr>
                                <th><?= __('Nom complet') ?></th>
                                <td><?= h($team->entraineur_nom_complet) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Date de naissance') ?></th>
                                <td><?= h($team->entraineur_date_naissance ? $team->entraineur_date_naissance->format('d/m/Y') : '') ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Téléphone') ?></th>
                                <td><?= h($team->entraineur_tel) ?></td>
                            </tr>
                            <?php if ($team->entraineur_whatsapp): ?>
                            <tr>
                                <th><?= __('WhatsApp') ?></th>
                                <td><?= h($team->entraineur_whatsapp) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                        
                        <?php if ($team->entraineur_cin_recto || $team->entraineur_cin_verso): ?>
                        <div class="mt-3">
                            <h5>Documents d'identité</h5>
                            <div class="row">
                                <?php if ($team->entraineur_cin_recto): ?>
                                <div class="col-md-6 mb-2">
                                    <p class="small text-muted mb-1">CIN/Passeport - Recto</p>
                                    <img src="<?= $this->Url->build('/' . h($team->entraineur_cin_recto)) ?>" 
                                         alt="CIN Recto" 
                                         class="img-fluid document-image" 
                                         onclick="openImageModal(this)">
                                </div>
                                <?php endif; ?>
                                <?php if ($team->entraineur_cin_verso): ?>
                                <div class="col-md-6 mb-2">
                                    <p class="small text-muted mb-1">CIN/Passeport - Verso</p>
                                    <img src="<?= $this->Url->build('/' . h($team->entraineur_cin_verso)) ?>" 
                                         alt="CIN Verso" 
                                         class="img-fluid document-image" 
                                         onclick="openImageModal(this)">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Joueurs -->
        <?php if (!empty($team->joueurs)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h3>Liste des joueurs (<?= count($team->joueurs) ?>)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom complet</th>
                                <th>Date de naissance</th>
                                <th>Identifiant</th>
                                <th>Taille vestimentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($team->joueurs as $index => $joueur): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= h($joueur->nom_complet) ?></td>
                                <td><?= h($joueur->date_naissance ? $joueur->date_naissance->format('d/m/Y') : '') ?></td>
                                <td><?= h($joueur->identifiant) ?></td>
                                <td><span class="badge badge-secondary"><?= h($joueur->taille_vestimentaire) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card mt-4">
            <div class="card-body text-center">
                <p class="text-light">Aucun joueur n'a été ajouté à cette équipe.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="modal-close" onclick="closeImageModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div class="modal-caption" id="modalCaption"></div>
</div>

<style>
    .table-borderless th,
    .table-borderless td {
        border: none;
        padding: 0.5rem 1rem;
    }
    
    .table-borderless th {
        color: var(--text-light);
        font-weight: 500;
        width: 40%;
    }
    
    .table-borderless td {
        color: var(--text-dark);
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 20px;
    }
    
    .badge-info {
        background-color: var(--info-color);
        color: white;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .float-right {
        float: right;
    }
    
    .document-image {
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        max-height: 200px;
        object-fit: cover;
    }
    
    .document-image:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px);
    }
    
    .modal-content {
        margin: auto;
        display: block;
        width: auto;
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .modal-close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
    }
    
    .modal-close:hover {
        color: #ccc;
    }
    
    .modal-caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        font-size: 16px;
    }
</style>

<script>
function openImageModal(img) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');
    
    modal.style.display = 'block';
    modalImg.src = img.src;
    captionText.innerHTML = img.alt;
    
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>