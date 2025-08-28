<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * Teams Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\EntraineursTable&\Cake\ORM\Association\HasMany $Entraineurs
 * @property \App\Model\Table\JoueursTable&\Cake\ORM\Association\HasMany $Joueurs
 * @property \App\Model\Table\ResponsablesTable&\Cake\ORM\Association\HasMany $Responsables
 *
 * @method \App\Model\Entity\Team newEmptyEntity()
 * @method \App\Model\Entity\Team newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Team> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Team get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Team findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Team patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Team> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Team|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Team saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Team>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Team>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Team>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Team> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Team>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Team>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Team>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Team> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TeamsTable extends Table
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

        $this->setTable('teams');
        $this->setDisplayField('nom_equipe');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('FootballCategories', [
            'foreignKey' => 'football_category_id',
        ]);
        $this->belongsTo('FootballDistricts', [
            'foreignKey' => 'football_district_id',
        ]);
        $this->belongsTo('FootballOrganisations', [
            'foreignKey' => 'football_organisation_id',
        ]);
        $this->hasMany('Joueurs', [
            'foreignKey' => 'team_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->maxLength('categorie', 50)
            ->requirePresence('categorie', 'create')
            ->notEmptyString('categorie');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 10)
            ->requirePresence('genre', 'create')
            ->notEmptyString('genre')
            ->inList('genre', ['Homme', 'Femme']);

        $validator
            ->scalar('type_football')
            ->maxLength('type_football', 10)
            ->requirePresence('type_football', 'create')
            ->notEmptyString('type_football')
            ->inList('type_football', ['5x5', '6x6', '11x11']);

        $validator
            ->scalar('district')
            ->maxLength('district', 100)
            ->requirePresence('district', 'create')
            ->notEmptyString('district');

        $validator
            ->scalar('organisation')
            ->maxLength('organisation', 100)
            ->requirePresence('organisation', 'create')
            ->notEmptyString('organisation');

        $validator
            ->scalar('adresse')
            ->requirePresence('adresse', 'create')
            ->notEmptyString('adresse');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        // Validations pour les champs de relation
        $validator
            ->allowEmptyString('football_category_id')
            ->integer('football_category_id');

        $validator
            ->allowEmptyString('football_district_id')
            ->integer('football_district_id');

        $validator
            ->allowEmptyString('football_organisation_id')
            ->integer('football_organisation_id');

        // Champs responsable
        $validator
            ->scalar('responsable_nom_complet')
            ->maxLength('responsable_nom_complet', 255)
            ->requirePresence('responsable_nom_complet', 'create')
            ->notEmptyString('responsable_nom_complet');

        $validator
            ->date('responsable_date_naissance')
            ->requirePresence('responsable_date_naissance', 'create')
            ->notEmptyDate('responsable_date_naissance');

        $validator
            ->scalar('responsable_tel')
            ->maxLength('responsable_tel', 20)
            ->requirePresence('responsable_tel', 'create')
            ->notEmptyString('responsable_tel');

        $validator
            ->scalar('responsable_whatsapp')
            ->maxLength('responsable_whatsapp', 20)
            ->allowEmptyString('responsable_whatsapp');

        $validator
            ->scalar('responsable_cin_recto')
            ->maxLength('responsable_cin_recto', 255)
            ->allowEmptyString('responsable_cin_recto');

        $validator
            ->scalar('responsable_cin_verso')
            ->maxLength('responsable_cin_verso', 255)
            ->allowEmptyString('responsable_cin_verso');

        // Champs entraineur
        $validator
            ->scalar('entraineur_nom_complet')
            ->maxLength('entraineur_nom_complet', 255)
            ->allowEmptyString('entraineur_nom_complet');

        $validator
            ->date('entraineur_date_naissance')
            ->allowEmptyDate('entraineur_date_naissance');

        $validator
            ->scalar('entraineur_tel')
            ->maxLength('entraineur_tel', 20)
            ->allowEmptyString('entraineur_tel');

        $validator
            ->scalar('entraineur_whatsapp')
            ->maxLength('entraineur_whatsapp', 20)
            ->allowEmptyString('entraineur_whatsapp');

        $validator
            ->scalar('entraineur_cin_recto')
            ->maxLength('entraineur_cin_recto', 255)
            ->allowEmptyString('entraineur_cin_recto');

        $validator
            ->scalar('entraineur_cin_verso')
            ->maxLength('entraineur_cin_verso', 255)
            ->allowEmptyString('entraineur_cin_verso');

        $validator
            ->boolean('entraineur_same_as_responsable')
            ->allowEmptyString('entraineur_same_as_responsable');

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

    /**
     * Get minimum and maximum players for football type
     */
    public function getPlayerLimits($type)
    {
        $limits = [
            '5x5' => ['min' => 5, 'max' => 8],
            '6x6' => ['min' => 6, 'max' => 10],
            '11x11' => ['min' => 11, 'max' => 18]
        ];
        
        return $limits[$type] ?? null;
    }
    
    /**
     * Before save callback
     *
     * @param \Cake\Event\EventInterface $event The event instance
     * @param \Cake\Datasource\EntityInterface $entity The entity being saved
     * @param \ArrayObject $options The options passed to the save method
     * @return void
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew() && empty($entity->reference_inscription)) {
            $entity->reference_inscription = $this->generateReference();
        } elseif (!$entity->isNew() && $entity->isDirty() && !$entity->isDirty('reference_inscription')) {
            // Si l'entité est modifiée (mais pas la référence elle-même), ajouter ou incrémenter le suffixe de version
            // On exclut les modifications importantes qui justifient un versionning
            $significantFields = [
                'nom_equipe', 'categorie', 'genre', 'type_football', 'district', 'organisation',
                'responsable_nom_complet', 'entraineur_nom_complet'
            ];
            
            $hasSignificantChanges = false;
            foreach ($significantFields as $field) {
                if ($entity->isDirty($field)) {
                    $hasSignificantChanges = true;
                    break;
                }
            }
            
            if ($hasSignificantChanges) {
                $this->updateReferenceVersion($entity);
            }
        }
    }
    
    /**
     * Génère une référence unique pour l'inscription
     *
     * @return string
     */
    private function generateReference(): string
    {
        $year = date('Y');
        $month = date('m');
        
        // Récupérer le dernier numéro de séquence pour ce mois
        $lastTeam = $this->find()
            ->where([
                'reference_inscription LIKE' => "FB{$year}{$month}%"
            ])
            ->order(['id' => 'DESC'])
            ->first();
        
        $sequence = 1;
        if ($lastTeam && !empty($lastTeam->reference_inscription)) {
            // Extraire le numéro de séquence de la dernière référence
            $lastSequence = (int) substr($lastTeam->reference_inscription, -4);
            $sequence = $lastSequence + 1;
        }
        
        // Format: FB + année(4) + mois(2) + séquence(4)
        return sprintf("FB%s%s%04d", $year, $month, $sequence);
    }
    
    /**
     * Met à jour la version de la référence lors d'une modification
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity being saved
     * @return void
     */
    private function updateReferenceVersion(EntityInterface $entity): void
    {
        $currentReference = $entity->reference_inscription;
        
        if (empty($currentReference)) {
            // Si pas de référence, en générer une nouvelle
            $entity->reference_inscription = $this->generateReference();
            return;
        }
        
        // Vérifier si la référence contient déjà un suffixe de version
        if (preg_match('/^(FB\d{10})_v(\d+)$/', $currentReference, $matches)) {
            // Extraire la référence de base et le numéro de version
            $baseReference = $matches[1];
            $versionNumber = (int) $matches[2];
            $newVersion = $versionNumber + 1;
        } else {
            // Première modification, ajouter _v2
            $baseReference = $currentReference;
            $newVersion = 2;
        }
        
        // Mettre à jour la référence avec la nouvelle version
        $entity->reference_inscription = $baseReference . '_v' . $newVersion;
    }
}
