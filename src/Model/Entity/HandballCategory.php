<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HandballCategory Entity
 *
 * @property int $id
 * @property string $name
 * @property string $age_range
 * @property \Cake\I18n\Date $min_date
 * @property \Cake\I18n\Date $max_date
 * @property int $min_birth_year
 * @property int $max_birth_year
 * @property bool $active
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $updated
 *
 * @property \App\Model\Entity\HandballTeam[] $handball_teams
 */
class HandballCategory extends Entity
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
        'age_range' => true,
        'min_date' => true,
        'max_date' => true,
        'min_birth_year' => true,
        'max_birth_year' => true,
        'active' => true,
        'created' => true,
        'updated' => true,
        'handball_teams' => true,
    ];
}
