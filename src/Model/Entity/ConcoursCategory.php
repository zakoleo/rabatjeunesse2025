<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ConcoursCategory extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'gender' => true,
        'age_category' => true,
        'date_range_start' => true,
        'date_range_end' => true,
        'min_participants' => true,
        'max_participants' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'concours_participants' => true,
    ];
}