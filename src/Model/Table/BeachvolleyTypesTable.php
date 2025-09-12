<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BeachvolleyTypes Model
 *
 * @property \App\Model\Table\BeachvolleyCategoriesTypesTable&\Cake\ORM\Association\HasMany $BeachvolleyCategoriesTypes
 *
 * @method \App\Model\Entity\BeachvolleyType newEmptyEntity()
 * @method \App\Model\Entity\BeachvolleyType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BeachvolleyType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BeachvolleyType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BeachvolleyType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BeachvolleyType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BeachvolleyType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BeachvolleyType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BeachvolleyType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BeachvolleyTypesTable extends Table
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

        $this->setTable('beachvolley_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('BeachvolleyCategoriesTypes', [
            'foreignKey' => 'beachvolley_type_id',
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
            ->scalar('name')
            ->maxLength('name', 20)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('code')
            ->maxLength('code', 10)
            ->requirePresence('code', 'create')
            ->notEmptyString('code')
            ->add('code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('min_players')
            ->requirePresence('min_players', 'create')
            ->notEmptyString('min_players');

        $validator
            ->integer('max_players')
            ->requirePresence('max_players', 'create')
            ->notEmptyString('max_players');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

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
        $rules->add($rules->isUnique(['code']), ['errorField' => 'code']);

        return $rules;
    }
}
