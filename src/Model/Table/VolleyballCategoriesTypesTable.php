<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VolleyballCategoriesTypes Model
 *
 * @property \App\Model\Table\VolleyballCategoriesTable&\Cake\ORM\Association\BelongsTo $VolleyballCategories
 * @property \App\Model\Table\VolleyballTypesTable&\Cake\ORM\Association\BelongsTo $VolleyballTypes
 *
 * @method \App\Model\Entity\VolleyballCategoriesType newEmptyEntity()
 * @method \App\Model\Entity\VolleyballCategoriesType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\VolleyballCategoriesType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VolleyballCategoriesType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\VolleyballCategoriesType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\VolleyballCategoriesType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\VolleyballCategoriesType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\VolleyballCategoriesType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\VolleyballCategoriesType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballCategoriesType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballCategoriesType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballCategoriesType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballCategoriesType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballCategoriesType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VolleyballCategoriesTypesTable extends Table
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

        $this->setTable('volleyball_categories_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('VolleyballCategories', [
            'foreignKey' => 'volleyball_category_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('VolleyballTypes', [
            'foreignKey' => 'volleyball_type_id',
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
            ->integer('volleyball_category_id')
            ->notEmptyString('volleyball_category_id');

        $validator
            ->integer('volleyball_type_id')
            ->notEmptyString('volleyball_type_id');

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
        $rules->add($rules->isUnique(['volleyball_category_id', 'volleyball_type_id']), ['errorField' => 'volleyball_category_id']);
        $rules->add($rules->existsIn(['volleyball_category_id'], 'VolleyballCategories'), ['errorField' => 'volleyball_category_id']);
        $rules->add($rules->existsIn(['volleyball_type_id'], 'VolleyballTypes'), ['errorField' => 'volleyball_type_id']);

        return $rules;
    }
}
