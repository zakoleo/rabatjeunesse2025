<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FootballTypes Model
 *
 * @property \App\Model\Table\NewTeamsTable&\Cake\ORM\Association\HasMany $NewTeams
 *
 * @method \App\Model\Entity\FootballType newEmptyEntity()
 * @method \App\Model\Entity\FootballType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FootballType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\FootballType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\FootballType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FootballType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\FootballType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\FootballType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballType> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FootballTypesTable extends Table
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

        $this->setTable('football_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('NewTeams', [
            'foreignKey' => 'football_type_id',
        ]);
        
        // Many-to-many relationship with FootballCategories through junction table
        $this->belongsToMany('FootballCategories', [
            'through' => 'FootballCategoriesTypes',
            'foreignKey' => 'football_type_id',
            'targetForeignKey' => 'football_category_id',
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
