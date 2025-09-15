<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BasketballTeamsJoueurs Model
 *
 * @property \App\Model\Table\BasketballTeamsTable&\Cake\ORM\Association\BelongsTo $BasketballTeams
 *
 * @method \App\Model\Entity\BasketballTeamsJoueur newEmptyEntity()
 * @method \App\Model\Entity\BasketballTeamsJoueur newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballTeamsJoueur> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BasketballTeamsJoueur get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BasketballTeamsJoueur findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BasketballTeamsJoueur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BasketballTeamsJoueur> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BasketballTeamsJoueur|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BasketballTeamsJoueur saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeamsJoueur>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeamsJoueur> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeamsJoueur>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BasketballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BasketballTeamsJoueur> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BasketballTeamsJoueursTable extends Table
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

        $this->setTable('basketball_teams_joueurs');
        $this->setDisplayField('nom_complet');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('BasketballTeams', [
            'foreignKey' => 'basketball_team_id',
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
            ->scalar('nom_complet')
            ->maxLength('nom_complet', 255)
            ->requirePresence('nom_complet', 'create')
            ->notEmptyString('nom_complet');

        $validator
            ->date('date_naissance')
            ->requirePresence('date_naissance', 'create')
            ->notEmptyDate('date_naissance');

        $validator
            ->scalar('identifiant')
            ->maxLength('identifiant', 50)
            ->requirePresence('identifiant', 'create')
            ->notEmptyString('identifiant');

        $validator
            ->scalar('taille_vestimentaire')
            ->maxLength('taille_vestimentaire', 10)
            ->requirePresence('taille_vestimentaire', 'create')
            ->notEmptyString('taille_vestimentaire')
            ->inList('taille_vestimentaire', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']);

        $validator
            ->integer('basketball_team_id')
            ->notEmptyString('basketball_team_id');

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
        $rules->add($rules->existsIn(['basketball_team_id'], 'BasketballTeams'), ['errorField' => 'basketball_team_id']);

        return $rules;
    }
}