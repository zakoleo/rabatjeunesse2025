<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Category Entity
 *
 * @property int $id
 * @property int $sport_id
 * @property string $name
 * @property string|null $age_range
 * @property int|null $min_birth_year
 * @property int|null $max_birth_year
 * @property \Cake\I18n\Date|null $min_birth_date
 * @property \Cake\I18n\Date|null $max_birth_date
 * @property bool $active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Sport $sport
 * @property \App\Model\Entity\NewTeam[] $new_teams
 */
class Category extends Entity
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
        'sport_id' => true,
        'name' => true,
        'age_range' => true,
        'min_birth_year' => true,
        'max_birth_year' => true,
        'min_birth_date' => true,
        'max_birth_date' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'sport' => true,
        'new_teams' => true,
    ];
}
