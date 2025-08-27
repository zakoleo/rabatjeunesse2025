<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BeachvolleyTeam Entity
 *
 * @property int $id
 * @property string $nom_equipe
 * @property string $categorie
 * @property string $genre
 * @property string $type_beachvolley
 * @property string $district
 * @property string $organisation
 * @property string $adresse
 * @property int $user_id
 * @property string|null $reference_inscription
 * @property string $status
 * @property \Cake\I18n\DateTime|null $verified_at
 * @property int|null $verified_by
 * @property string|null $verification_notes
 * @property int|null $football_category_id
 * @property int|null $football_district_id
 * @property int|null $football_organisation_id
 * @property string|null $responsable_nom_complet
 * @property \Cake\I18n\Date|null $responsable_date_naissance
 * @property string|null $responsable_tel
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
 * @property bool|null $accepter_reglement
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\FootballCategory $football_category
 * @property \App\Model\Entity\FootballDistrict $football_district
 * @property \App\Model\Entity\FootballOrganisation $football_organisation
 * @property \App\Model\Entity\BeachvolleyTeamsJoueur[] $beachvolley_teams_joueurs
 */
class BeachvolleyTeam extends Entity
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
        'type_beachvolley' => true,
        'district' => true,
        'organisation' => true,
        'adresse' => true,
        'user_id' => true,
        'reference_inscription' => true,
        'status' => true,
        'verified_at' => true,
        'verified_by' => true,
        'verification_notes' => true,
        'football_category_id' => true,
        'football_district_id' => true,
        'football_organisation_id' => true,
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
        'accepter_reglement' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'football_category' => true,
        'football_district' => true,
        'football_organisation' => true,
        'beachvolley_teams_joueurs' => true,
    ];
}
