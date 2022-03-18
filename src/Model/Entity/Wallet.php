<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Wallet Entity
 *
 * @property int $ID
 * @property string $address
 * @property string|null $publicName
 * @property string|null $nonce
 * @property \Cake\I18n\FrozenTime $created
 */
class Wallet extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'address' => true,
        'publicName' => true,
        'nonce' => true,
        'created' => true,
    ];
}
