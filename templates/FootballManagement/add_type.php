<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FootballType $type
 */
?>
<div class="add-type content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('➕ Add New Football Type') ?></h3>
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
                                'checked' => true,
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
                        <?= $this->Form->button('💾 Save Football Type', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">💡 Type Examples</h6>
                </div>
                <div class="card-body">
                    <h6>🏟️ Common Football Types:</h6>
                    <div class="example-type mb-3">
                        <strong>Indoor/Futsal (5x5)</strong>
                        <ul class="small list-unstyled">
                            <li>• Code: 5x5</li>
                            <li>• Min Players: 5</li>
                            <li>• Max Players: 10</li>
                            <li>• Field: Indoor/Small</li>
                        </ul>
                    </div>

                    <div class="example-type mb-3">
                        <strong>Small Field (6x6)</strong>
                        <ul class="small list-unstyled">
                            <li>• Code: 6x6</li>
                            <li>• Min Players: 6</li>
                            <li>• Max Players: 12</li>
                            <li>• Field: Small outdoor</li>
                        </ul>
                    </div>

                    <div class="example-type mb-3">
                        <strong>Full Field (11x11)</strong>
                        <ul class="small list-unstyled">
                            <li>• Code: 11x11</li>
                            <li>• Min Players: 11</li>
                            <li>• Max Players: 18</li>
                            <li>• Field: Standard size</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">📏 Player Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="small list-unstyled">
                        <li><strong>Min Players:</strong> Minimum to start a match</li>
                        <li><strong>Max Players:</strong> Total squad size (including substitutes)</li>
                        <li><strong>Recommendations:</strong></li>
                        <li>• 5x5: 5-10 players</li>
                        <li>• 6x6: 6-12 players</li>
                        <li>• 7x7: 7-14 players</li>
                        <li>• 11x11: 11-18 players</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">⚡ Next Steps</h6>
                </div>
                <div class="card-body">
                    <p class="card-text small">After creating this type:</p>
                    <ol class="small">
                        <li>Go to <strong>Relations Cat/Types</strong></li>
                        <li>Assign age categories that can play this format</li>
                        <li>Test in the registration form</li>
                    </ol>
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

    // Auto-suggest max players based on min players
    minPlayersInput.addEventListener('input', function() {
        const minVal = parseInt(this.value);
        if (minVal && !maxPlayersInput.value) {
            maxPlayersInput.value = minVal * 2; // Suggest double for substitutes
        }
    });
});
</script>

<style>
.example-type {
    padding: 12px;
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
    border-radius: 4px;
}

.example-type strong {
    color: #007bff;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.list-unstyled li {
    margin-bottom: 0.25rem;
}
</style>