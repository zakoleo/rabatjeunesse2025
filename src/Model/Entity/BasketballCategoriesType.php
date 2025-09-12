<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BasketballCategoriesType Entity
 *
 * @property int $id
 * @property int $basketball_category_id
 * @property int $basketball_type_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\BasketballCategory $basketball_category
 * @property \App\Model\Entity\BasketballType $basketball_type
 */
class BasketballCategoriesType extends Entity
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
        'basketball_category_id' => true,
        'basketball_type_id' => true,
        'created' => true,
        'modified' => true,
        'basketball_category' => true,
        'basketball_type' => true,
    ];
}
