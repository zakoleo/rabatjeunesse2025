<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sports Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\HasMany $Categories
 * @property \App\Model\Table\NewTeamsTable&\Cake\ORM\Association\HasMany $NewTeams
 *
 * @method \App\Model\Entity\Sport newEmptyEntity()
 * @method \App\Model\Entity\Sport newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Sport> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sport get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Sport findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Sport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Sport> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sport|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Sport saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Sport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Sport>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Sport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Sport> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Sport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Sport>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Sport>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Sport> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SportsTable extends Table
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

        $this->setTable('sports');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Categories', [
            'foreignKey' => 'sport_id',
        ]);
        $this->hasMany('NewTeams', [
            'foreignKey' => 'sport_id',
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
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('code')
            ->maxLength('code', 20)
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
