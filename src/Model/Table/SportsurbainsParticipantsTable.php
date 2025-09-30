<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SportsurbainsParticipantsTable extends Table
{
    /**
     * Get available sport types
     *
     * @return array
     */
    public static function getSportTypes(): array
    {
        return [
            'Skateboard' => 'Skateboard',
            'BMX' => 'BMX', 
            'Breakdance' => 'Breakdance'
        ];
    }
    
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('sportsurbains_participants');
        $this->setDisplayField('nom_complet');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('SportsurbainsCategories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->integer('category_id')
            ->requirePresence('category_id', 'create')
            ->notEmptyString('category_id');

        $validator
            ->scalar('type_sport')
            ->inList('type_sport', ['Skateboard', 'BMX', 'Breakdance'])
            ->requirePresence('type_sport', 'create')
            ->notEmptyString('type_sport');

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
            ->scalar('gender')
            ->inList('gender', ['Homme', 'Femme'])
            ->requirePresence('gender', 'create')
            ->notEmptyString('gender');

        $validator
            ->scalar('cin')
            ->maxLength('cin', 20)
            ->requirePresence('cin', 'create')
            ->notEmptyString('cin');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 20)
            ->requirePresence('telephone', 'create')
            ->notEmptyString('telephone');

        $validator
            ->scalar('whatsapp')
            ->maxLength('whatsapp', 20)
            ->allowEmptyString('whatsapp');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('taille_tshirt')
            ->inList('taille_tshirt', ['XS', 'S', 'M', 'L', 'XL', 'XXL'])
            ->requirePresence('taille_tshirt', 'create')
            ->notEmptyString('taille_tshirt');

        $validator
            ->scalar('cin_recto')
            ->maxLength('cin_recto', 255)
            ->allowEmptyFile('cin_recto');

        $validator
            ->scalar('cin_verso')
            ->maxLength('cin_verso', 255)
            ->allowEmptyFile('cin_verso');

        $validator
            ->scalar('reference_inscription')
            ->maxLength('reference_inscription', 50)
            ->allowEmptyString('reference_inscription');

        $validator
            ->scalar('status')
            ->inList('status', ['pending', 'verified', 'rejected'])
            ->notEmptyString('status');

        $validator
            ->dateTime('verified_at')
            ->allowEmptyDateTime('verified_at');

        $validator
            ->integer('verified_by')
            ->allowEmptyString('verified_by');

        $validator
            ->scalar('verification_notes')
            ->allowEmptyString('verification_notes');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn(['category_id'], 'SportsurbainsCategories'), ['errorField' => 'category_id']);
        $rules->add($rules->isUnique(['reference_inscription']), ['errorField' => 'reference_inscription']);
        
        // Add custom rule to validate birth date against category date range
        $rules->add(function ($entity, $options) {
            if (!$entity->category_id || !$entity->date_naissance) {
                return true;
            }
            
            $category = $this->SportsurbainsCategories->get($entity->category_id);
            
            if ($category->date_range_start && $category->date_range_end) {
                $birthDate = $entity->date_naissance;
                $startDate = $category->date_range_start;
                $endDate = $category->date_range_end;
                
                if ($birthDate < $startDate || $birthDate > $endDate) {
                    return 'La date de naissance n\'est pas valide pour cette catégorie.';
                }
            }
            
            return true;
        }, 'validBirthDateForCategory', [
            'errorField' => 'date_naissance',
            'message' => 'La date de naissance n\'est pas valide pour la catégorie sélectionnée.'
        ]);

        return $rules;
    }

    public function beforeSave(\Cake\Event\EventInterface $event, $entity, $options)
    {
        if ($entity->isNew() && empty($entity->reference_inscription)) {
            $prefix = 'SU';
            $year = date('Y');
            $lastParticipant = $this->find()
                ->where(['reference_inscription LIKE' => $prefix . $year . '%'])
                ->order(['id' => 'DESC'])
                ->first();

            if ($lastParticipant && $lastParticipant->reference_inscription) {
                $lastNumber = (int) substr($lastParticipant->reference_inscription, -4);
                $newNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $entity->reference_inscription = $prefix . $year . $newNumber;
        }

        return true;
    }
}