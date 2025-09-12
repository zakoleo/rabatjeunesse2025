<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FootballCategory $category
 */
?>
<div class="add-category content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('➕ Add New Football Category') ?></h3>
        <div class="btn-group">
            <?= $this->Html->link(__('← Back to Categories'), ['action' => 'categories'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📋 Category Information</h5>
                </div>
                <div class="card-body">
                    <?= $this->Form->create($category, ['class' => 'needs-validation', 'novalidate' => false]) ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->control('name', [
                                'label' => '🏷️ Category Name',
                                'class' => 'form-control',
                                'placeholder' => 'e.g., -12, -15, +19, Senior',
                                'help' => 'Short identifier for the age category',
                                'required' => true
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('active', [
                                'label' => '✅ Active',
                                'type' => 'checkbox',
                                'class' => 'form-check-input',
                                'checked' => true,
                                'help' => 'Only active categories appear in forms'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->control('min_date', [
                                'label' => '📅 Minimum Birth Date',
                                'type' => 'date',
                                'class' => 'form-control',
                                'help' => 'Earliest allowed birth date (YYYY-MM-DD)',
                                'required' => true
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('max_date', [
                                'label' => '📅 Maximum Birth Date',
                                'type' => 'date',
                                'class' => 'form-control',
                                'help' => 'Latest allowed birth date (YYYY-MM-DD)',
                                'required' => true
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $this->Form->control('min_birth_year', [
                                'label' => '🗓️ Minimum Birth Year',
                                'type' => 'number',
                                'class' => 'form-control',
                                'min' => 1950,
                                'max' => date('Y'),
                                'help' => 'Earliest allowed birth year'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $this->Form->control('max_birth_year', [
                                'label' => '🗓️ Maximum Birth Year',
                                'type' => 'number',
                                'class' => 'form-control',
                                'min' => 1950,
                                'max' => date('Y'),
                                'help' => 'Latest allowed birth year'
                            ]) ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <?= $this->Html->link(
                            '❌ Cancel',
                            ['action' => 'categories'],
                            ['class' => 'btn btn-outline-secondary']
                        ) ?>
                        <?= $this->Form->button('💾 Save Category', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">💡 Quick Help</h6>
                </div>
                <div class="card-body">
                    <h6>📋 Category Examples:</h6>
                    <ul class="list-unstyled">
                        <li><strong>-12:</strong> Under 12 years old</li>
                        <li><strong>-15:</strong> Under 15 years old</li>
                        <li><strong>-18:</strong> Under 18 years old</li>
                        <li><strong>+19:</strong> 19 years and older</li>
                        <li><strong>Senior:</strong> Senior category</li>
                    </ul>

                    <h6 class="mt-3">📅 Date Guidelines:</h6>
                    <ul class="list-unstyled">
                        <li>• Use YYYY-MM-DD format</li>
                        <li>• Min date = earliest eligible birth date</li>
                        <li>• Max date = latest eligible birth date</li>
                        <li>• Birth years are calculated automatically</li>
                    </ul>

                    <div class="alert alert-info mt-3">
                        <small>
                            <strong>💡 Tip:</strong> After creating the category, 
                            don't forget to assign football types that this category can play!
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">⚡ Next Steps</h6>
                </div>
                <div class="card-body">
                    <p class="card-text small">After saving this category:</p>
                    <ol class="small">
                        <li>Go to <strong>Relationships</strong></li>
                        <li>Assign football types (5x5, 6x6, 11x11)</li>
                        <li>Test in the registration form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill birth years when dates change
document.addEventListener('DOMContentLoaded', function() {
    const minDateInput = document.querySelector('input[name="min_date"]');
    const maxDateInput = document.querySelector('input[name="max_date"]');
    const minYearInput = document.querySelector('input[name="min_birth_year"]');
    const maxYearInput = document.querySelector('input[name="max_birth_year"]');

    function updateBirthYear(dateInput, yearInput) {
        if (dateInput.value) {
            const year = new Date(dateInput.value).getFullYear();
            yearInput.value = year;
        }
    }

    minDateInput.addEventListener('change', function() {
        updateBirthYear(minDateInput, minYearInput);
    });

    maxDateInput.addEventListener('change', function() {
        updateBirthYear(maxDateInput, maxYearInput);
    });
});
</script>

<style>
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.list-unstyled li {
    margin-bottom: 0.25rem;
}

.card-body ol li {
    margin-bottom: 0.25rem;
}
</style>