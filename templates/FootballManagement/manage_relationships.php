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
        <div class="col-md-8">
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

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">💡 Recommendations</h6>
                </div>
                <div class="card-body">
                    <?php 
                    $categoryName = $category->name;
                    if (strpos($categoryName, '-12') !== false): 
                    ?>
                        <div class="alert alert-warning">
                            <h6>For -12 Categories:</h6>
                            <p>Usually only <strong>6x6</strong> is recommended for younger players (smaller field size).</p>
                        </div>
                    <?php elseif (strpos($categoryName, '-15') !== false): ?>
                        <div class="alert alert-info">
                            <h6>For -15 Categories:</h6>
                            <p>Commonly allow <strong>6x6</strong> and <strong>11x11</strong> as transitional age group.</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <h6>For Adult Categories:</h6>
                            <p>Usually all formats are allowed: <strong>5x5</strong>, <strong>6x6</strong>, and <strong>11x11</strong>.</p>
                        </div>
                    <?php endif; ?>

                    <h6 class="mt-3">🏟️ Format Guide:</h6>
                    <ul class="list-unstyled small">
                        <li><strong>5x5:</strong> Indoor/futsal, smaller teams</li>
                        <li><strong>6x6:</strong> Small outdoor field</li>
                        <li><strong>11x11:</strong> Full-size football field</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">⚡ Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="btn-group-vertical btn-block">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAll()">
                            ✅ Select All Types
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="selectNone()">
                            ❌ Select None
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="selectRecommended()">
                            💡 Select Recommended
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">📊 Current Selection</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <span id="selected-count"><?= count($currentTypeIds) ?></span> of <?= count($allTypes) ?> types selected
                    </p>
                    <div class="progress mt-2">
                        <div id="progress-bar" class="progress-bar" 
                             style="width: <?= count($allTypes) > 0 ? (count($currentTypeIds) / count($allTypes) * 100) : 0 ?>%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCounter() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="type_ids"]');
    const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    const totalCount = checkboxes.length;
    
    document.getElementById('selected-count').textContent = selectedCount;
    
    const progressBar = document.getElementById('progress-bar');
    const percentage = totalCount > 0 ? (selectedCount / totalCount * 100) : 0;
    progressBar.style.width = percentage + '%';
    
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

function selectAll() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="type_ids"]');
    checkboxes.forEach(cb => cb.checked = true);
    updateCounter();
}

function selectNone() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="type_ids"]');
    checkboxes.forEach(cb => cb.checked = false);
    updateCounter();
}

function selectRecommended() {
    const categoryName = '<?= $category->name ?>';
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="type_ids"]');
    
    // First uncheck all
    checkboxes.forEach(cb => cb.checked = false);
    
    // Then check recommended based on category
    checkboxes.forEach(function(checkbox) {
        const label = checkbox.nextElementSibling;
        const typeCode = label.querySelector('.badge').textContent;
        
        if (categoryName.includes('-12')) {
            // -12: only 6x6
            if (typeCode === '6x6') checkbox.checked = true;
        } else if (categoryName.includes('-15')) {
            // -15: 6x6 and 11x11
            if (typeCode === '6x6' || typeCode === '11x11') checkbox.checked = true;
        } else {
            // Others: all formats
            checkbox.checked = true;
        }
    });
    
    updateCounter();
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