<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ConcoursCategoriesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('concours_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ConcoursParticipants', [
            'foreignKey' => 'category_id',
            'dependent' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('gender')
            ->inList('gender', ['Homme', 'Femme'])
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender');

        $validator
            ->scalar('age_category')
            ->inList('age_category', ['U18', '18+'])
            ->requirePresence('age_category', 'create')
            ->notEmptyString('age_category');

        $validator
            ->date('date_range_start')
            ->allowEmptyDate('date_range_start');

        $validator
            ->date('date_range_end')
            ->allowEmptyDate('date_range_end');

        $validator
            ->integer('min_participants')
            ->requirePresence('min_participants', 'create')
            ->notEmptyString('min_participants');

        $validator
            ->integer('max_participants')
            ->requirePresence('max_participants', 'create')
            ->notEmptyString('max_participants');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['gender', 'age_category']), ['errorField' => 'gender']);

        return $rules;
    }

    public function findActive(SelectQuery $query, array $options): SelectQuery
    {
        return $query->where(['active' => true]);
    }
}