<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Users' => 'app.users'
    ];

    /**
     * Debug to display cases titles
     * 
     * @var bool
     */
    public $debug = true;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->UsersTable = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersTable);

        parent::tearDown();
    }
    
    /**
     * Test validationDefault method
     * 
     * @return void
     */
//    public function testValidationDefault() {
//        $this->markTestIncomplete('Not implemented yet.');
//    }
    
    /**
     * Test validationUpdate method
     * 
     * @return void
     */
//    public function testValidationUpdate() {
//        $this->markTestIncomplete('Not implemented yet.');
//    }

    /**
     * Test passwordComplexe method
     *
     * @return void
     */
    public function testPasswordComplexe()
    {   
        // less than 8 characters
        if($this->debug) debug('USERS MODEL TABLE - testPasswordComplexe: less than 8 characters');
        $this->assertEquals(false, $this->UsersTable->passwordComplexe('aa87'));
        
        // non capital letter
        if($this->debug) debug('USERS MODEL TABLE - testPasswordComplexe: non capital letter');
        $this->assertEquals(false, $this->UsersTable->passwordComplexe('aa87djfioasdhfwehuf483'));
        
        // no number
        if($this->debug) debug('USERS MODEL TABLE - testPasswordComplexe: no number');
        $this->assertEquals(false, $this->UsersTable->passwordComplexe('AADjfiOasdhfwehuf'));
        
        // all ok
        if($this->debug) debug('USERS MODEL TABLE - testPasswordComplexe: all ok');
        $this->assertEquals(true, $this->UsersTable->passwordComplexe('aa45Ijoifp98DDfde'));
    }
}
