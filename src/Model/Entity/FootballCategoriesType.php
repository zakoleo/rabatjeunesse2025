<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FootballCategoriesType Entity
 *
 * @property int $id
 * @property int $football_category_id
 * @property int $football_type_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\FootballCategory $football_category
 * @property \App\Model\Entity\FootballType $football_type
 */
class FootballCategoriesType extends Entity
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
        'football_category_id' => true,
        'football_type_id' => true,
        'created' => true,
        'modified' => true,
        'football_category' => true,
        'football_type' => true,
    ];
}
