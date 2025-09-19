<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FootballCategory $category
 */
?>
<div class="edit-category content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('✏️ Edit Football Category: {0}', h($category->name)) ?></h3>
        <div class="btn-group">
            <?= $this->Html->link(__('← Back to Categories'), ['action' => 'categories'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
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
                        <?= $this->Form->button('💾 Update Category', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?= $this->Form->end() ?>
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

    // Date range validation
    function validateDateRange() {
        const minDate = new Date(minDateInput.value);
        const maxDate = new Date(maxDateInput.value);

        if (minDate && maxDate && minDate > maxDate) {
            maxDateInput.setCustomValidity('Maximum birth date must be later than minimum birth date');
        } else {
            maxDateInput.setCustomValidity('');
        }
    }

    minDateInput.addEventListener('change', validateDateRange);
    maxDateInput.addEventListener('change', validateDateRange);
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

.alert {
    padding: 0.5rem;
}

.small {
    font-size: 0.875rem;
}
</style>