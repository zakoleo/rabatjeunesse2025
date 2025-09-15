<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HandballTeamsJoueurs Model
 *
 * @property \App\Model\Table\HandballTeamsTable&\Cake\ORM\Association\BelongsTo $HandballTeams
 *
 * @method \App\Model\Entity\HandballTeamsJoueur newEmptyEntity()
 * @method \App\Model\Entity\HandballTeamsJoueur newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballTeamsJoueur> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HandballTeamsJoueur get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\HandballTeamsJoueur findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\HandballTeamsJoueur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballTeamsJoueur> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\HandballTeamsJoueur|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\HandballTeamsJoueur saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeamsJoueur>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeamsJoueur> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeamsJoueur>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeamsJoueur> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HandballTeamsJoueursTable extends Table
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

        $this->setTable('handball_teams_joueurs');
        $this->setDisplayField('nom_complet');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('HandballTeams', [
            'foreignKey' => 'handball_team_id',
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
            ->integer('handball_team_id')
            ->allowEmptyString('handball_team_id');

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
        $rules->add($rules->existsIn(['handball_team_id'], 'HandballTeams'), ['errorField' => 'handball_team_id']);

        return $rules;
    }
}