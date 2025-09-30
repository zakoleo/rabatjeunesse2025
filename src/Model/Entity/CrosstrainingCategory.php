<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CrosstrainingCategory extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'gender' => true,
        'age_category' => true,
        'date_range_start' => true,
        'date_range_end' => true,
        'min_players' => true,
        'max_players' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'crosstraining_participants' => true,
    ];
}