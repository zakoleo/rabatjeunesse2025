<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FootballDistricts Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\HasMany $Teams
 *
 * @method \App\Model\Entity\FootballDistrict newEmptyEntity()
 * @method \App\Model\Entity\FootballDistrict newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballDistrict> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FootballDistrict get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\FootballDistrict findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\FootballDistrict patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\FootballDistrict> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\FootballDistrict|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\FootballDistrict saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\FootballDistrict>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballDistrict>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballDistrict>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballDistrict> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballDistrict>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballDistrict>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\FootballDistrict>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\FootballDistrict> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FootballDistrictsTable extends Table
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

        $this->setTable('football_districts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Teams', [
            'foreignKey' => 'football_district_id',
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
            ->boolean('active')
            ->requirePresence('active', 'create')
            ->notEmptyString('active');

        return $validator;
    }
}
