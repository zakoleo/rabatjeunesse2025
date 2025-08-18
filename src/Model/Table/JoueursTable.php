<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Joueurs Model
 *
 * @property \App\Model\Table\TeamsTable&\Cake\ORM\Association\BelongsTo $Teams
 *
 * @method \App\Model\Entity\Joueur newEmptyEntity()
 * @method \App\Model\Entity\Joueur newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Joueur> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Joueur get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Joueur findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Joueur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Joueur> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Joueur|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Joueur saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Joueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joueur>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Joueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joueur> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Joueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joueur>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Joueur>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Joueur> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class JoueursTable extends Table
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

        $this->setTable('joueurs');
        $this->setDisplayField('nom_complet');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
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
            ->maxLength('taille_vestimentaire', 5)
            ->requirePresence('taille_vestimentaire', 'create')
            ->notEmptyString('taille_vestimentaire');

        $validator
            ->integer('team_id')
            ->notEmptyString('team_id');

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
        $rules->add($rules->existsIn(['team_id'], 'Teams'), ['errorField' => 'team_id']);
        
        // Règle de validation pour la date de naissance selon la catégorie de l'équipe
        $rules->add(function ($entity, $options) {
            if (!empty($entity->team_id) && !empty($entity->date_naissance)) {
                $team = $this->Teams->get($entity->team_id);
                $categorie = $team->categorie;
                
                $dateRanges = [
                    'U12' => ['min' => '2014-01-01', 'max' => '2015-12-31'],
                    'U15' => ['min' => '2012-01-01', 'max' => '2013-12-31'],
                    'U18' => ['min' => '2008-01-01', 'max' => '2010-12-31'],
                    '18+' => ['min' => '1970-01-01', 'max' => '2007-12-31'],
                    'U21' => ['min' => '2005-01-01', 'max' => '2007-12-31']
                ];
                
                if (isset($dateRanges[$categorie])) {
                    $range = $dateRanges[$categorie];
                    $minDate = new \DateTime($range['min']);
                    $maxDate = new \DateTime($range['max']);
                    $birthDate = new \DateTime($entity->date_naissance->format('Y-m-d'));
                    
                    if ($birthDate < $minDate || $birthDate > $maxDate) {
                        return sprintf(
                            'Pour la catégorie %s, le joueur doit être né entre le %s et le %s',
                            $categorie,
                            $minDate->format('d/m/Y'),
                            $maxDate->format('d/m/Y')
                        );
                    }
                }
            }
            return true;
        }, 'validDateNaissance', [
            'errorField' => 'date_naissance',
            'message' => 'La date de naissance ne correspond pas à la catégorie de l\'équipe'
        ]);

        return $rules;
    }
}
