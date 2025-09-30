<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CrosstrainingParticipant extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'category_id' => true,
        'nom_complet' => true,
        'date_naissance' => true,
        'gender' => true,
        'cin' => true,
        'telephone' => true,
        'whatsapp' => true,
        'email' => true,
        'taille_tshirt' => true,
        'cin_recto' => true,
        'cin_verso' => true,
        'reference_inscription' => true,
        'status' => true,
        'verified_at' => true,
        'verified_by' => true,
        'verification_notes' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'crosstraining_category' => true,
    ];
}