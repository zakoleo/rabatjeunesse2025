<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Team Entity
 *
 * @property int $id
 * @property string $nom_equipe
 * @property string $categorie
 * @property string $genre
 * @property string $type_football
 * @property string $district
 * @property string $organisation
 * @property string $adresse
 * @property int $user_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property string $responsable_nom_complet
 * @property \Cake\I18n\Date $responsable_date_naissance
 * @property string $responsable_tel
 * @property string|null $responsable_whatsapp
 * @property string|null $responsable_cin_recto
 * @property string|null $responsable_cin_verso
 * @property string|null $entraineur_nom_complet
 * @property \Cake\I18n\Date|null $entraineur_date_naissance
 * @property string|null $entraineur_tel
 * @property string|null $entraineur_whatsapp
 * @property string|null $entraineur_cin_recto
 * @property string|null $entraineur_cin_verso
 * @property bool|null $entraineur_same_as_responsable
 * 
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Joueur[] $joueurs
 */
class Team extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'nom_equipe' => true,
        'categorie' => true,
        'genre' => true,
        'type_football' => true,
        'district' => true,
        'organisation' => true,
        'adresse' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'joueurs' => true,
        'responsable_nom_complet' => true,
        'responsable_date_naissance' => true,
        'responsable_tel' => true,
        'responsable_whatsapp' => true,
        'responsable_cin_recto' => true,
        'responsable_cin_verso' => true,
        'entraineur_nom_complet' => true,
        'entraineur_date_naissance' => true,
        'entraineur_tel' => true,
        'entraineur_whatsapp' => true,
        'entraineur_cin_recto' => true,
        'entraineur_cin_verso' => true,
        'entraineur_same_as_responsable' => true,
    ];
}
