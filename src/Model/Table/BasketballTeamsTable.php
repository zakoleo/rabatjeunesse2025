<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BasketballTeams Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\BasketballTeam newEmptyEntity()
 * @method \App\Model\Entity\BasketballTeam newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballTeam> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BasketballTeam get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BasketballTeam findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BasketballTeam patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballTeam> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BasketballTeam|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BasketballTeam saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeam>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeam> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeam>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeam> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BasketballTeamsTable extends Table
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

        $this->setTable('basketball_teams');
        $this->setDisplayField('nom_equipe');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->scalar('nom_equipe')
            ->maxLength('nom_equipe', 255)
            ->requirePresence('nom_equipe', 'create')
            ->notEmptyString('nom_equipe');

        $validator
            ->scalar('categorie')
            ->maxLength('categorie', 10)
            ->requirePresence('categorie', 'create')
            ->notEmptyString('categorie');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 10)
            ->requirePresence('genre', 'create')
            ->notEmptyString('genre');

        $validator
            ->scalar('type_basketball')
            ->maxLength('type_basketball', 10)
            ->requirePresence('type_basketball', 'create')
            ->notEmptyString('type_basketball');

        $validator
            ->scalar('district')
            ->maxLength('district', 50)
            ->requirePresence('district', 'create')
            ->notEmptyString('district');

        $validator
            ->scalar('organisation')
            ->maxLength('organisation', 20)
            ->requirePresence('organisation', 'create')
            ->notEmptyString('organisation');

        $validator
            ->scalar('adresse')
            ->requirePresence('adresse', 'create')
            ->notEmptyString('adresse');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
