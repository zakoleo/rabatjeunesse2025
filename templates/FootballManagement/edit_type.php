<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FootballType $type
 */
?>
<div class="edit-type content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('✏️ Edit Football Type: {0}', h($type->name)) ?></h3>
        <div class="btn-group">
            <?= $this->Html->link(__('← Back to Types'), ['action' => 'types'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🏟️ Football Type Information</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->create($type, ['class' => 'needs-validation', 'novalidate' => false]) ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->control('name', [
                                'label' => '🏷️ Type Name',
                                'class' => 'form-control',
                                'placeholder' => 'e.g., Football à 6 (6x6)',
                                'help' => 'Full descriptive name for this football format',
                                'required' => true
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('code', [
                                'label' => '📝 Type Code',
                                'class' => 'form-control',
                                'placeholder' => 'e.g., 6x6, 11x11',
                                'help' => 'Short identifier (max 10 chars)',
                                'required' => true,
                                'maxlength' => 10
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->control('min_players', [
                                'label' => '👥 Minimum Players',
                                'type' => 'number',
                                'class' => 'form-control',
                                'min' => 1,
                                'max' => 50,
                                'help' => 'Minimum players per team (including subs)',
                                'required' => true
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('max_players', [
                                'label' => '👥 Maximum Players',
                                'type' => 'number',
                                'class' => 'form-control',
                                'min' => 1,
                                'max' => 50,
                                'help' => 'Maximum players per team (including subs)',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $this->Form->control('active', [
                                'label' => '✅ Active',
                                'type' => 'checkbox',
                                'class' => 'form-check-input',
                                'help' => 'Only active types appear in registration forms'
                            ]) ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <?= $this->Html->link(
                            '❌ Cancel',
                            ['action' => 'types'],
                            ['class' => 'btn btn-outline-secondary']
                        ) ?>
                        <?= $this->Form->button('💾 Update Football Type', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📊 Current Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><strong>ID:</strong> <?= $type->id ?></li>
                        <li><strong>Current Name:</strong> <?= h($type->name) ?></li>
                        <li><strong>Current Code:</strong> <span class="badge badge-info"><?= h($type->code) ?></span></li>
                        <li><strong>Current Players:</strong> <?= $type->min_players ?>-<?= $type->max_players ?></li>
                        <li><strong>Status:</strong> 
                            <?php if ($type->active): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactive</span>
                            <?php endif; ?>
                        </li>
                        <li><strong>Created:</strong> <?= $type->created ? (is_string($type->created) ? $type->created : $type->created->format('Y-m-d H:i')) : 'N/A' ?></li>
                        <li><strong>Modified:</strong> <?= $type->modified ? (is_string($type->modified) ? $type->modified : $type->modified->format('Y-m-d H:i')) : 'N/A' ?></li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">🔗 Current Relationships</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($type->football_categories)): ?>
                        <p><strong>Used by categories:</strong></p>
                        <ul class="list-unstyled">
                            <?php foreach ($type->football_categories as $category): ?>
                                <li>
                                    <span class="badge badge-success"><?= h($category->name) ?></span>
                                    <small class="text-muted">(<?= h($category->min_date) ?> - <?= h($category->max_date) ?>)</small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <small>
                                <strong>⚠️ Not assigned to any categories</strong><br>
                                This type won't appear in registration forms until assigned to categories.
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <?= $this->Html->link(
                        '🔗 Manage Relationships',
                        ['action' => 'relationships'],
                        ['class' => 'btn btn-outline-success btn-sm']
                    ) ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">⚠️ Important Notes</h6>
                </div>
                <div class="card-body">
                    <ul class="small list-unstyled">
                        <li><strong>Code Changes:</strong> Changing the code may affect existing relationships</li>
                        <li><strong>Player Limits:</strong> Teams using this type must comply with these limits</li>
                        <li><strong>Deactivating:</strong> Will hide this type from registration forms</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">🗑️ Danger Zone</h6>
                </div>
                <div class="card-body">
                    <?= $this->Form->postLink(
                        '🗑️ Delete This Type',
                        ['action' => 'deleteType', $type->id],
                        [
                            'confirm' => 'Are you sure you want to delete "' . $type->name . '"? This will remove all relationships and cannot be undone.',
                            'class' => 'btn btn-danger btn-sm'
                        ]
                    ) ?>
                    <p class="small text-muted mt-2">
                        This will permanently delete the type and all its relationships with categories.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-validation for min/max players
document.addEventListener('DOMContentLoaded', function() {
    const minPlayersInput = document.querySelector('input[name="min_players"]');
    const maxPlayersInput = document.querySelector('input[name="max_players"]');

    function validatePlayers() {
        const minVal = parseInt(minPlayersInput.value);
        const maxVal = parseInt(maxPlayersInput.value);

        if (minVal && maxVal && minVal > maxVal) {
            maxPlayersInput.setCustomValidity('Maximum players must be greater than or equal to minimum players');
        } else {
            maxPlayersInput.setCustomValidity('');
        }
    }

    minPlayersInput.addEventListener('input', validatePlayers);
    maxPlayersInput.addEventListener('input', validatePlayers);
});
</script>

<style>
.badge {
    font-size: 0.75em;
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.list-unstyled li {
    margin-bottom: 0.5rem;
}

.card-body ul li {
    margin-bottom: 0.25rem;
}

.alert {
    padding: 0.5rem;
}
</style>