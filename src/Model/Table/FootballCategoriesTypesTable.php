<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FootballCategoriesTypes Model
 *
 * @property \App\Model\Table\FootballCategoriesTable&\Cake\ORM\Association\BelongsTo $FootballCategories
 * @property \App\Model\Table\FootballTypesTable&\Cake\ORM\Association\BelongsTo $FootballTypes
 *
 * @method \App\Model\Entity\FootballCategoriesType newEmptyEntity()
 * @method \App\Model\Entity\FootballCategoriesType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballCategoriesType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FootballCategoriesType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\FootballCategoriesType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\FootballCategoriesType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballCategoriesType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FootballCategoriesType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\FootballCategoriesType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategoriesType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategoriesType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategoriesType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategoriesType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FootballCategoriesTypesTable extends Table
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

        $this->setTable('football_categories_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('FootballCategories', [
            'foreignKey' => 'football_category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FootballTypes', [
            'foreignKey' => 'football_type_id',
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
            ->integer('football_category_id')
            ->notEmptyString('football_category_id');

        $validator
            ->integer('football_type_id')
            ->notEmptyString('football_type_id');

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
        $rules->add($rules->isUnique(['football_category_id', 'football_type_id']), ['errorField' => 'football_category_id']);
        $rules->add($rules->existsIn(['football_category_id'], 'FootballCategories'), ['errorField' => 'football_category_id']);
        $rules->add($rules->existsIn(['football_type_id'], 'FootballTypes'), ['errorField' => 'football_type_id']);

        return $rules;
    }
}
