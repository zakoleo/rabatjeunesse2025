<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CrosstrainingTeamsJoueur Entity
 *
 * @property int $id
 * @property int $crosstraining_team_id
 * @property string $nom_complet
 * @property \Cake\I18n\Date $date_naissance
 * @property string|null $taille
 * @property string|null $cin_ou_extrait
 * @property string|null $cin_recto
 * @property string|null $cin_verso
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\CrosstrainingTeam $crosstraining_team
 */
class CrosstrainingTeamsJoueur extends Entity
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
        'crosstraining_team_id' => true,
        'nom_complet' => true,
        'date_naissance' => true,
        'taille' => true,
        'cin_ou_extrait' => true,
        'cin_recto' => true,
        'cin_verso' => true,
        'created' => true,
        'modified' => true,
        'crosstraining_team' => true,
    ];
}