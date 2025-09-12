<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HandballCategoriesType Entity
 *
 * @property int $id
 * @property int $handball_category_id
 * @property int $handball_type_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\HandballCategory $handball_category
 * @property \App\Model\Entity\HandballType $handball_type
 */
class HandballCategoriesType extends Entity
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
        'handball_category_id' => true,
        'handball_type_id' => true,
        'created' => true,
        'modified' => true,
        'handball_category' => true,
        'handball_type' => true,
    ];
}
