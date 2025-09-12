<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HandballCategoriesTypes Model
 *
 * @property \App\Model\Table\HandballCategoriesTable&\Cake\ORM\Association\BelongsTo $HandballCategories
 * @property \App\Model\Table\HandballTypesTable&\Cake\ORM\Association\BelongsTo $HandballTypes
 *
 * @method \App\Model\Entity\HandballCategoriesType newEmptyEntity()
 * @method \App\Model\Entity\HandballCategoriesType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballCategoriesType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HandballCategoriesType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\HandballCategoriesType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\HandballCategoriesType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballCategoriesType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\HandballCategoriesType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\HandballCategoriesType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategoriesType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategoriesType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategoriesType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategoriesType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HandballCategoriesTypesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('handball_categories_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('HandballCategories', [
            'foreignKey' => 'handball_category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('HandballTypes', [
            'foreignKey' => 'handball_type_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('handball_category_id')
            ->notEmptyString('handball_category_id');

        $validator
            ->integer('handball_type_id')
            ->notEmptyString('handball_type_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['handball_category_id', 'handball_type_id']), ['errorField' => 'handball_category_id']);
        $rules->add($rules->existsIn(['handball_category_id'], 'HandballCategories'), ['errorField' => 'handball_category_id']);
        $rules->add($rules->existsIn(['handball_type_id'], 'HandballTypes'), ['errorField' => 'handball_type_id']);

        return $rules;
    }
}
