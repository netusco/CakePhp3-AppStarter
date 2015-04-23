<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity.
 */
class User extends Entity
{

    protected $_hidden = ['password'];
    
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'id' => true,
        'nom' => true,
        'prenom' => true,
        'fullname_slug' => true,
        'email' => true,
        'password' => true,
        'change_pass_code' => true,
        'change_pass_date' => true,
        'role' => true,
        'actif' => true
    ];
    
    protected function _setPassword($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }
    
    /**
    * Name accessor that returns the first and last name.
    * 
    * @return string user first and last name
    */
    public function _getName() {
        return $this->nom . ' ' . $this->prenom;
    }
    
}
