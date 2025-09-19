<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FootballCategory $category
 * @var array $allTypes
 * @var array $currentTypeIds
 */
?>
<div class="manage-relationships content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('🔧 Manage Relationships for: {0}', h($category->name)) ?></h3>
        <div class="btn-group">
            <?= $this->Html->link(__('← Back to Relationships'), ['action' => 'relationships'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🎯 Select Football Types for: <strong><?= h($category->name) ?></strong></h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->create(null, ['url' => ['action' => 'manageRelationships', $category->id]]) ?>
                    
                    <div class="alert alert-info mb-4">
                        <h6><strong>📋 Category Details:</strong></h6>
                        <ul class="mb-0">
                            <li><strong>Name:</strong> <?= h($category->name) ?></li>
                            <li><strong>Birth Date Range:</strong> <?= h($category->min_date) ?> to <?= h($category->max_date) ?></li>
                            <li><strong>Currently Allowed Types:</strong> <?= count($currentTypeIds) ?></li>
                        </ul>
                    </div>

                    <div class="row">
                        <?php foreach ($allTypes as $type): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card <?= in_array($type->id, $currentTypeIds) ? 'border-success' : 'border-light' ?>">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <?= $this->Form->checkbox("type_ids.{$type->id}", [
                                                'value' => $type->id,
                                                'checked' => in_array($type->id, $currentTypeIds),
                                                'class' => 'form-check-input',
                                                'id' => "type_{$type->id}"
                                            ]) ?>
                                            <label class="form-check-label" for="type_<?= $type->id ?>">
                                                <strong><?= h($type->name) ?></strong><br>
                                                <span class="badge badge-info"><?= h($type->code) ?></span><br>
                                                <small class="text-muted">
                                                    👥 <?= $type->min_players ?>-<?= $type->max_players ?> players
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (empty($allTypes)): ?>
                        <div class="alert alert-warning">
                            <h6>No football types available</h6>
                            <p>Create football types first before assigning them to categories.</p>
                            <?= $this->Html->link(
                                '🏟️ Add Football Type',
                                ['action' => 'addType'],
                                ['class' => 'btn btn-info']
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <?= $this->Html->link(
                            '❌ Cancel',
                            ['action' => 'relationships'],
                            ['class' => 'btn btn-outline-secondary']
                        ) ?>
                        <?= $this->Form->button('💾 Save Relationships', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCounter() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="type_ids"]');
    
    // Update card borders
    checkboxes.forEach(function(checkbox) {
        const card = checkbox.closest('.card');
        if (checkbox.checked) {
            card.classList.add('border-success');
            card.classList.remove('border-light');
        } else {
            card.classList.add('border-light');
            card.classList.remove('border-success');
        }
    });
}


// Initialize counter and bind events
document.addEventListener('DOMContentLoaded', function() {
    updateCounter();
    
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="type_ids"]');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateCounter);
    });
});
</script>

<style>
.form-check-label {
    cursor: pointer;
    width: 100%;
}

.card {
    transition: border-color 0.2s ease;
}

.card.border-success {
    border-color: #28a745 !important;
}

.btn-group-vertical .btn {
    margin-bottom: 0.25rem;
}

.progress {
    height: 8px;
}
</style>