<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FootballType Entity
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $min_players
 * @property int $max_players
 * @property bool $active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\NewTeam[] $new_teams
 */
class FootballType extends Entity
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
        'name' => true,
        'code' => true,
        'min_players' => true,
        'max_players' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'new_teams' => true,
    ];
}
