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
 * HandballTeams Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\HandballTeamsJoueursTable&\Cake\ORM\Association\HasMany $HandballTeamsJoueurs
 * @property \App\Model\Table\HandballCategoriesTable&\Cake\ORM\Association\BelongsTo $HandballCategories
 * @property \App\Model\Table\FootballDistrictsTable&\Cake\ORM\Association\BelongsTo $FootballDistricts
 * @property \App\Model\Table\FootballOrganisationsTable&\Cake\ORM\Association\BelongsTo $FootballOrganisations
 *
 * @method \App\Model\Entity\HandballTeam newEmptyEntity()
 * @method \App\Model\Entity\HandballTeam newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballTeam> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HandballTeam get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\HandballTeam findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\HandballTeam patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\HandballTeam> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\HandballTeam|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\HandballTeam saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeam>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeam> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeam>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\HandballTeam>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\HandballTeam> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HandballTeamsTable extends Table
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

        $this->setTable('handball_teams');
        $this->setDisplayField('nom_equipe');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('HandballCategories', [
            'foreignKey' => 'handball_category_id',
        ]);
        // Note: Using shared Football districts/organisations tables for now  
        // TODO: Create dedicated Handball districts/organisations tables
        $this->belongsTo('FootballDistricts', [
            'foreignKey' => 'handball_district_id',
            'className' => 'FootballDistricts'
        ]);
        $this->belongsTo('FootballOrganisations', [
            'foreignKey' => 'handball_organisation_id',
            'className' => 'FootballOrganisations' 
        ]);
        $this->hasMany('HandballTeamsJoueurs', [
            'foreignKey' => 'handball_team_id',
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
            ->maxLength('nom_equipe', 255, 'Le nom de l\'équipe ne peut pas dépasser 255 caractères.')
            ->requirePresence('nom_equipe', 'create', 'Le nom de l\'équipe est requis.')
            ->notEmptyString('nom_equipe', 'Le nom de l\'équipe ne peut pas être vide.');

        $validator
            ->scalar('categorie')
            ->maxLength('categorie', 50, 'La catégorie ne peut pas dépasser 50 caractères.')
            ->requirePresence('categorie', 'create', 'La catégorie est requise.')
            ->notEmptyString('categorie', 'La catégorie ne peut pas être vide.');

        $validator
            ->scalar('genre')
            ->maxLength('genre', 10, 'Le genre ne peut pas dépasser 10 caractères.')
            ->requirePresence('genre', 'create', 'Le genre est requis.')
            ->notEmptyString('genre', 'Le genre ne peut pas être vide.')
            ->inList('genre', ['Homme', 'Femme'], 'Le genre doit être "Homme" ou "Femme".');

        $validator
            ->scalar('type_handball')
            ->maxLength('type_handball', 10, 'Le type de handball ne peut pas dépasser 10 caractères.')
            ->requirePresence('type_handball', 'create', 'Le type de handball est requis.')
            ->notEmptyString('type_handball', 'Le type de handball ne peut pas être vide.')
            ->inList('type_handball', ['7x7', '5x5'], 'Le type de handball doit être "7x7" ou "5x5".');

        $validator
            ->scalar('district')
            ->maxLength('district', 100, 'Le district ne peut pas dépasser 100 caractères.')
            ->requirePresence('district', 'create', 'Le district est requis.')
            ->notEmptyString('district', 'Le district ne peut pas être vide.');

        $validator
            ->scalar('organisation')
            ->maxLength('organisation', 100, 'L\'organisation ne peut pas dépasser 100 caractères.')
            ->requirePresence('organisation', 'create', 'L\'organisation est requise.')
            ->notEmptyString('organisation', 'L\'organisation ne peut pas être vide.');

        $validator
            ->scalar('adresse')
            ->requirePresence('adresse', 'create', 'L\'adresse est requise.')
            ->notEmptyString('adresse', 'L\'adresse ne peut pas être vide.');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create', 'L\'utilisateur est requis.')
            ->notEmptyString('user_id', 'L\'utilisateur est requis.');

        $validator
            ->scalar('reference_inscription')
            ->maxLength('reference_inscription', 50)
            ->allowEmptyString('reference_inscription');

        // Validations pour les champs de relation
        $validator
            ->allowEmptyString('handball_category_id')
            ->integer('handball_category_id');

        $validator
            ->allowEmptyString('handball_district_id')
            ->integer('handball_district_id');

        $validator
            ->allowEmptyString('handball_organisation_id')
            ->integer('handball_organisation_id');

        // Responsable validation
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

        // Entraineur validation
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
            ->boolean('entraineur_same_as_responsable')
            ->allowEmptyString('entraineur_same_as_responsable');

        $validator
            ->boolean('accepter_reglement')
            ->requirePresence('accepter_reglement', 'create')
            ->notEmptyString('accepter_reglement');

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
        $rules->add($rules->existsIn(['handball_category_id'], 'HandballCategories'), ['errorField' => 'handball_category_id']);
        $rules->add($rules->existsIn(['handball_district_id'], 'FootballDistricts'), ['errorField' => 'handball_district_id']);
        $rules->add($rules->existsIn(['handball_organisation_id'], 'FootballOrganisations'), ['errorField' => 'handball_organisation_id']);

        return $rules;
    }

    /**
     * Generate reference number for handball team
     */
    public function generateReference(): string
    {
        $year = date('y');
        $month = date('m');
        
        // Get the next sequence number for this month
        $lastTeam = $this->find()
            ->where(['reference_inscription LIKE' => "HB{$year}{$month}%"])
            ->orderBy(['reference_inscription' => 'DESC'])
            ->first();
        
        $sequence = 1;
        if ($lastTeam && !empty($lastTeam->reference_inscription)) {
            $lastSequence = (int)substr($lastTeam->reference_inscription, -4);
            $sequence = $lastSequence + 1;
        }
        
        return sprintf("HB%s%s%04d", $year, $month, $sequence);
    }

    /**
     * Before save callback
     */
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options)
    {
        // Generate reference if not exists
        if ($entity->isNew() && empty($entity->reference_inscription)) {
            $entity->reference_inscription = $this->generateReference();
        }
        
        return true;
    }
}