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
        <div class="col-md-12">
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