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
 * @property \App\Model\Table\FootballCategoriesTable&\Cake\ORM\Association\BelongsTo $FootballCategories
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
        $this->belongsTo('FootballCategories', [
            'foreignKey' => 'handball_category_id',
        ]);
        $this->belongsTo('FootballDistricts', [
            'foreignKey' => 'handball_district_id',
        ]);
        $this->belongsTo('FootballOrganisations', [
            'foreignKey' => 'handball_organisation_id',
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
            ->scalar('type_handball')
            ->maxLength('type_handball', 10)
            ->requirePresence('type_handball', 'create')
            ->notEmptyString('type_handball')
            ->inList('type_handball', ['7x7', '5x5']);

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
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('reference_inscription')
            ->maxLength('reference_inscription', 50)
            ->allowEmptyString('reference_inscription');

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