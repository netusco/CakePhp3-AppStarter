<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use Cake\Auth\DefaultPasswordHasher;

class UsersFixture extends TestFixture
{
    
    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'integer'],
        'nom' => ['type' => 'string', 'length' => 45, 'null' => true],
        'prenom' => ['type' => 'string', 'length' => 45, 'null' => true],
        'fullname_slug' => ['type' => 'string', 'length' => 155, 'null' => true],
        'email' => ['type' => 'string', 'length' => 255, 'null' => false],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false],
        'created' => 'datetime',
        'updated' => 'datetime',
        'change_pass_code' => ['type' => 'string', 'length' => 255, 'null' => true],
        'change_pass_date' => ['type' => 'datetime', 'null' => true],
        'role' => ['type' => 'string', 'length' => 55, 'default' => '0', 'null' => true],
        'actif' => ['type' => 'integer', 'default' => '1', 'null' => false],
        '_indexes' => [
            'role' => ['type' => 'index', 'columns' => ['role']],
            'actif' => ['type' => 'index', 'columns' => ['actif']],
        ],
        '_constraints' => [
            'PRIMARY' => ['type' => 'primary', 'columns' => ['id']],
            'email_unique' => ['type' => 'unique', 'columns' => ['email']]
        ]
    ];
    
    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'users'];
    
    /**
     * method init
     * 
     * @return void
     */
    public function init() {
        
        $hasher = new DefaultPasswordHasher();
        
        $this->records = [
        [
            'nom' => 'User',
            'prenom' => 'First',
            'fullname_slug' => 'first_user',
            'email' => EMAIL_TO_TEST,
            'password' => $hasher->hash('juVni4tr3'),
            'role' => 'admin',
            'actif' => true,
            'created' => '2007-03-18 10:39:23',
            'updated' => '2007-03-18 10:41:31'
        ], [
            'nom' => 'User',
            'prenom' => 'Second',
            'fullname_slug' => 'second_user',
            'email' => 'seconduser@somemail.com',
            'password' => $hasher->hash('HuaB78lo'),
            'actif' => true,
            'change_pass_code' => '2400fd3226c673532e8e68d35c8c31115a83f6c3',
            'change_pass_date' => '2014-02-04 09:30:21',
            'created' => '2007-03-18 10:41:23',
            'updated' => '2007-03-18 10:43:31'
        ], [
            'nom' => 'User',
            'prenom' => 'Third',
            'fullname_slug' => 'third_user',
            'email' => 'thirduser@somemail.com',
            'password' => $hasher->hash('Mak66uruck'),
            'actif' => true,
            'created' => '2007-03-18 10:43:23',
            'updated' => '2007-03-18 10:45:31'
        ]
    ];
        parent::init();
    }
    
 }
