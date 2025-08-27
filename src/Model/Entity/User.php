<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property bool $is_admin
 * @property string $password
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\BasketballTeam[] $basketball_teams
 * @property \App\Model\Entity\BeachvolleyTeam[] $beachvolley_teams
 * @property \App\Model\Entity\HandballTeam[] $handball_teams
 * @property \App\Model\Entity\Team[] $teams
 * @property \App\Model\Entity\VolleyballTeam[] $volleyball_teams
 */
class User extends Entity
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
        'username' => true,
        'email' => true,
        'is_admin' => true,
        'password' => true,
        'created' => true,
        'modified' => true,
        'basketball_teams' => true,
        'beachvolley_teams' => true,
        'handball_teams' => true,
        'teams' => true,
        'volleyball_teams' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }
}
