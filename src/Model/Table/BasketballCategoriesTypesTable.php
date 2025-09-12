<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BasketballCategoriesTypes Model
 *
 * @property \App\Model\Table\BasketballCategoriesTable&\Cake\ORM\Association\BelongsTo $BasketballCategories
 * @property \App\Model\Table\BasketballTypesTable&\Cake\ORM\Association\BelongsTo $BasketballTypes
 *
 * @method \App\Model\Entity\BasketballCategoriesType newEmptyEntity()
 * @method \App\Model\Entity\BasketballCategoriesType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballCategoriesType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BasketballCategoriesType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BasketballCategoriesType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BasketballCategoriesType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballCategoriesType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BasketballCategoriesType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BasketballCategoriesType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategoriesType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategoriesType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategoriesType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategoriesType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BasketballCategoriesTypesTable extends Table
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

        $this->setTable('basketball_categories_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('BasketballCategories', [
            'foreignKey' => 'basketball_category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('BasketballTypes', [
            'foreignKey' => 'basketball_type_id',
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
            ->integer('basketball_category_id')
            ->notEmptyString('basketball_category_id');

        $validator
            ->integer('basketball_type_id')
            ->notEmptyString('basketball_type_id');

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
        $rules->add($rules->isUnique(['basketball_category_id', 'basketball_type_id']), ['errorField' => 'basketball_category_id']);
        $rules->add($rules->existsIn(['basketball_category_id'], 'BasketballCategories'), ['errorField' => 'basketball_category_id']);
        $rules->add($rules->existsIn(['basketball_type_id'], 'BasketballTypes'), ['errorField' => 'basketball_type_id']);

        return $rules;
    }
}