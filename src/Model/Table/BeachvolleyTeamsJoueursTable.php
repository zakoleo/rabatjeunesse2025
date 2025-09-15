<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BeachvolleyTeamsJoueurs Model
 *
 * @property \App\Model\Table\BeachvolleyTeamsTable&\Cake\ORM\Association\BelongsTo $BeachvolleyTeams
 *
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur newEmptyEntity()
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BeachvolleyTeamsJoueur> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BeachvolleyTeamsJoueur> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BeachvolleyTeamsJoueur saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyTeamsJoueur>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyTeamsJoueur> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyTeamsJoueur>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BeachvolleyTeamsJoueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BeachvolleyTeamsJoueur> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BeachvolleyTeamsJoueursTable extends Table
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

        $this->setTable('beachvolley_teams_joueurs');
        $this->setDisplayField(['nom_complet']);
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('BeachvolleyTeams', [
            'foreignKey' => 'beachvolley_team_id',
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
            ->integer('beachvolley_team_id')
            ->notEmptyString('beachvolley_team_id');

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
        $rules->add($rules->existsIn(['beachvolley_team_id'], 'BeachvolleyTeams'), ['errorField' => 'beachvolley_team_id']);

        return $rules;
    }
}