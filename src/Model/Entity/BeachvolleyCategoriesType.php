<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BeachvolleyCategoriesType Entity
 *
 * @property int $id
 * @property int $beachvolley_category_id
 * @property int $beachvolley_type_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\BeachvolleyCategory $beachvolley_category
 * @property \App\Model\Entity\BeachvolleyType $beachvolley_type
 */
class BeachvolleyCategoriesType extends Entity
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
        'beachvolley_category_id' => true,
        'beachvolley_type_id' => true,
        'created' => true,
        'modified' => true,
        'beachvolley_category' => true,
        'beachvolley_type' => true,
    ];
}
