<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HandballCategories Model
 *
 * @property \App\Model\Table\HandballTeamsTable&\Cake\ORM\Association\HasMany $HandballTeams
 *
 * @method \App\Model\Entity\HandballCategory newEmptyEntity()
 * @method \App\Model\Entity\HandballCategory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballCategory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HandballCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\HandballCategory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\HandballCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\HandballCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\HandballCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballCategory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HandballCategoriesTable extends Table
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

        $this->setTable('handball_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('HandballTeams', [
            'foreignKey' => 'handball_category_id',
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('age_range')
            ->maxLength('age_range', 255)
            ->requirePresence('age_range', 'create')
            ->notEmptyString('age_range');

        $validator
            ->date('min_date')
            ->requirePresence('min_date', 'create')
            ->notEmptyDate('min_date');

        $validator
            ->date('max_date')
            ->requirePresence('max_date', 'create')
            ->notEmptyDate('max_date');

        $validator
            ->integer('min_birth_year')
            ->requirePresence('min_birth_year', 'create')
            ->notEmptyString('min_birth_year');

        $validator
            ->integer('max_birth_year')
            ->requirePresence('max_birth_year', 'create')
            ->notEmptyString('max_birth_year');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        return $validator;
    }
}
