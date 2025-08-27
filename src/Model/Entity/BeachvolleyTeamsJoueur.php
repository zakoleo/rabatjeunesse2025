<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BeachvolleyTeamsJoueur Entity
 *
 * @property int $id
 * @property string $nom_complet
 * @property \Cake\I18n\Date $date_naissance
 * @property string $identifiant
 * @property string $taille_vestimentaire
 * @property int $beachvolley_team_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\BeachvolleyTeam $beachvolley_team
 */
class BeachvolleyTeamsJoueur extends Entity
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
        'nom_complet' => true,
        'date_naissance' => true,
        'identifiant' => true,
        'taille_vestimentaire' => true,
        'beachvolley_team_id' => true,
        'created' => true,
        'modified' => true,
        'beachvolley_team' => true,
    ];
}