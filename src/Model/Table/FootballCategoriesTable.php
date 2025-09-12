<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FootballCategories Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\HasMany $Teams
 *
 * @method \App\Model\Entity\FootballCategory newEmptyEntity()
 * @method \App\Model\Entity\FootballCategory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballCategory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FootballCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\FootballCategory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\FootballCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FootballCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\FootballCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballCategory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FootballCategoriesTable extends Table
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

        $this->setTable('football_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Teams', [
            'foreignKey' => 'football_category_id',
        ]);
        
        // Many-to-many relationship with FootballTypes through junction table
        $this->belongsToMany('FootballTypes', [
            'through' => 'FootballCategoriesTypes',
            'foreignKey' => 'football_category_id',
            'targetForeignKey' => 'football_type_id',
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
            ->scalar('age_range')
            ->maxLength('age_range', 100)
            ->requirePresence('age_range', 'create')
            ->notEmptyString('age_range');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

        return $validator;
    }
}
