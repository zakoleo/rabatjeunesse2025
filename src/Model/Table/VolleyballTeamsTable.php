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
 * VolleyballTeams Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\VolleyballTeam newEmptyEntity()
 * @method \App\Model\Entity\VolleyballTeam newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\VolleyballTeam> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VolleyballTeam get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\VolleyballTeam findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\VolleyballTeam patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\VolleyballTeam> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\VolleyballTeam|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\VolleyballTeam saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballTeam>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballTeam> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballTeam>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\VolleyballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\VolleyballTeam> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VolleyballTeamsTable extends Table
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

        $this->setTable('volleyball_teams');
        $this->setDisplayField('nom_equipe');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        
        $this->belongsTo('VolleyballCategories', [
            'foreignKey' => 'volleyball_category_id',
            'joinType' => 'LEFT',
        ]);
        
        $this->belongsTo('FootballDistricts', [
            'foreignKey' => 'volleyball_district_id',
            'joinType' => 'LEFT',
        ]);
        
        $this->belongsTo('FootballOrganisations', [
            'foreignKey' => 'volleyball_organisation_id',
            'joinType' => 'LEFT',
        ]);
        
        $this->hasMany('VolleyballTeamsJoueurs', [
            'foreignKey' => 'volleyball_team_id',
            'dependent' => true,
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
            ->maxLength('nom_equipe', 255, 'Le nom de l\'équipe ne peut pas dépasser 255 caractères.')
            ->requirePresence('nom_equipe', 'create', 'Le nom de l\'équipe est requis.')
            ->notEmptyString('nom_equipe', 'Le nom de l\'équipe ne peut pas être vide.');

        $validator
            ->scalar('categorie')
            ->maxLength('categorie', 10, 'La catégorie ne peut pas dépasser 10 caractères.')
            ->requirePresence('categorie', 'create', 'La catégorie est requise.')
            ->notEmptyString('categorie', 'La catégorie ne peut pas être vide.');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 10, 'Le genre ne peut pas dépasser 10 caractères.')
            ->requirePresence('genre', 'create', 'Le genre est requis.')
            ->notEmptyString('genre', 'Le genre ne peut pas être vide.');

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

        // Validations pour les champs de relation
        $validator
            ->allowEmptyString('volleyball_category_id')
            ->integer('volleyball_category_id');

        $validator
            ->allowEmptyString('volleyball_district_id')
            ->integer('volleyball_district_id');

        $validator
            ->allowEmptyString('volleyball_organisation_id')
            ->integer('volleyball_organisation_id');

        $validator
            ->scalar('type_volleyball')
            ->maxLength('type_volleyball', 10)
            ->requirePresence('type_volleyball', 'create')
            ->notEmptyString('type_volleyball')
            ->inList('type_volleyball', ['6x6']);

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

        $validator
            ->scalar('reference_inscription')
            ->maxLength('reference_inscription', 50)
            ->allowEmptyString('reference_inscription');

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
     * Get minimum and maximum players for volleyball type
     */
    public function getPlayerLimits($type)
    {
        $limits = [
            '6x6' => ['min' => 6, 'max' => 12]
        ];
        
        return $limits[$type] ?? null;
    }
    
    /**
     * Before save callback
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options): void
    {
        if ($entity->isNew() && empty($entity->reference_inscription)) {
            $entity->reference_inscription = $this->generateReference();
        }
    }
    
    /**
     * Generate reference number for volleyball team
     */
    private function generateReference(): string
    {
        $year = date('Y');
        $month = date('m');
        
        // Get the last sequence number for this month
        $lastTeam = $this->find()
            ->where([
                'reference_inscription LIKE' => "VB{$year}{$month}%"
            ])
            ->order(['id' => 'DESC'])
            ->first();
        
        $sequence = 1;
        if ($lastTeam && !empty($lastTeam->reference_inscription)) {
            $lastSequence = (int) substr($lastTeam->reference_inscription, -4);
            $sequence = $lastSequence + 1;
        }
        
        // Format: VB + year(4) + month(2) + sequence(4) (VB for Volleyball)
        return sprintf("VB%s%s%04d", $year, $month, $sequence);
    }
}
