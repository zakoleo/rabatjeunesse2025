<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BasketballCategories Model
 *
 * @property \App\Model\Table\BasketballTeamsTable&\Cake\ORM\Association\HasMany $BasketballTeams
 *
 * @method \App\Model\Entity\BasketballCategory newEmptyEntity()
 * @method \App\Model\Entity\BasketballCategory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballCategory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BasketballCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BasketballCategory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BasketballCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BasketballCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BasketballCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballCategory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BasketballCategoriesTable extends Table
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

        $this->setTable('basketball_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('BasketballTeams', [
            'foreignKey' => 'basketball_category_id',
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
