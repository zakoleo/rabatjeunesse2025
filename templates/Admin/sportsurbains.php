<?php
/**
 * @var \App\View\AppView $this
 * @var array $participants
 * @var array $stats
 * @var array $categories
 * @var array $sportTypes
 */
?>

<div class="admin-sportsurbains-index">
    <!-- Stats Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total'] ?></div>
                <div class="stat-label">Total participants</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['pending'] ?></div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['verified'] ?></div>
                <div class="stat-label">Vérifiés</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['rejected'] ?></div>
                <div class="stat-label">Rejetés</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline']) ?>
                <div class="form-group mr-3">
                    <?= $this->Form->control('search', [
                        'class' => 'form-control',
                        'placeholder' => 'Rechercher...',
                        'value' => $this->request->getQuery('search'),
                        'label' => false
                    ]) ?>
                </div>
                <div class="form-group mr-3">
                    <?= $this->Form->control('type', [
                        'class' => 'form-control',
                        'options' => ['' => 'Tous les types'] + $sportTypes,
                        'value' => $this->request->getQuery('type'),
                        'label' => false
                    ]) ?>
                </div>
                <div class="form-group mr-3">
                    <?= $this->Form->control('status', [
                        'class' => 'form-control',
                        'options' => [
                            '' => 'Tous les statuts',
                            'pending' => 'En attente',
                            'verified' => 'Vérifié',
                            'rejected' => 'Rejeté'
                        ],
                        'value' => $this->request->getQuery('status'),
                        'label' => false
                    ]) ?>
                </div>
                <div class="form-group mr-3">
                    <?= $this->Form->control('category', [
                        'class' => 'form-control',
                        'options' => ['' => 'Toutes les catégories'] + $categories,
                        'value' => $this->request->getQuery('category'),
                        'label' => false
                    ]) ?>
                </div>
                <?= $this->Form->button('Filtrer', ['class' => 'btn btn-primary']) ?>
                <?= $this->Html->link('Réinitialiser', ['action' => 'sportsurbains'], ['class' => 'btn btn-secondary ml-2']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Participants Sports Urbains</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Nom complet</th>
                            <th>Type Sport</th>
                            <th>Catégorie</th>
                            <th>CIN</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Date inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $participant): ?>
                        <tr>
                            <td><code><?= h($participant->reference_inscription) ?></code></td>
                            <td><?= h($participant->nom_complet) ?></td>
                            <td>
                                <span class="badge badge-primary"><?= h($participant->type_sport) ?></span>
                            </td>
                            <td>
                                <?php if ($participant->has('sportsurbains_category')): ?>
                                    <span class="badge badge-info">
                                        <?= h($participant->sportsurbains_category->gender) ?> - 
                                        <?= h($participant->sportsurbains_category->age_category) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?= h($participant->cin) ?></td>
                            <td><?= $participant->has('user') ? h($participant->user->email) : '-' ?></td>
                            <td><?= h($participant->telephone) ?></td>
                            <td>
                                <?php
                                $statusClass = 'secondary';
                                $statusText = 'En attente';
                                switch($participant->status) {
                                    case 'verified':
                                        $statusClass = 'success';
                                        $statusText = 'Vérifié';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'danger';
                                        $statusText = 'Rejeté';
                                        break;
                                }
                                ?>
                                <span class="badge badge-<?= $statusClass ?>"><?= $statusText ?></span>
                            </td>
                            <td><?= $participant->created->format('d/m/Y H:i') ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?= $this->Html->link('<i class="fas fa-eye"></i>', 
                                        ['action' => 'viewSportsurbainsFullParticipant', $participant->id], 
                                        ['class' => 'btn btn-sm btn-info', 'escape' => false, 'title' => 'Voir détails']
                                    ) ?>
                                    <button type="button" class="btn btn-sm btn-secondary" 
                                            onclick="viewParticipant(<?= $participant->id ?>)">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <?php if ($participant->status === 'pending'): ?>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="updateStatus(<?= $participant->id ?>, 'verified')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="updateStatus(<?= $participant->id ?>, 'rejected')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->first('<< ' . __('Premier')) ?>
                    <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('Suivant') . ' >') ?>
                    <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
                </ul>
                <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, affichage de {{current}} enregistrement(s) sur {{count}} au total')) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- View Participant Modal -->
<div class="modal fade" id="viewParticipantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du participant</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="participantDetails">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(id, status) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut?')) {
        $.ajax({
            url: '<?= $this->Url->build(['action' => 'updateSportsurbainsStatus']) ?>',
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Erreur lors de la mise à jour du statut');
            }
        });
    }
}

function viewParticipant(id) {
    $.ajax({
        url: '<?= $this->Url->build(['action' => 'viewSportsurbainsParticipant']) ?>/' + id,
        method: 'GET',
        success: function(response) {
            $('#participantDetails').html(response);
            $('#viewParticipantModal').modal('show');
        },
        error: function() {
            alert('Erreur lors du chargement des détails');
        }
    });
}
</script>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>