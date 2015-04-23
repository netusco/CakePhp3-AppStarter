<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\OwnershipComponent;
use Cake\TestSuite\TestCase;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;

/**
 * App\Controller\Component\OwnershipComponent Test Case
 */
class OwnershipComponentTest extends TestCase
{
    
    /**
     * Debug to display cases titles
     * 
     * @var bool
     */
    public $debug = true;
    
    /**
     * Registry
     * 
     * @var obj
     */
    public $registry = null;
    
    /**
     * Component
     * 
     * @var obj
     */
    public $component = null;
    
    /**
     * Controller
     * 
     * @var obj
     */
    public $controller = null;
    
    /**
     * Session
     * 
     * @var obj
     */
    public $session = null;
    
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        
        $request = new Request();
        $this->controller = $this->getMock(
            'Cake\Controller\Controller', 
            ['redirect'],
            [$request]
        );
        $this->session = $this->controller->request->session();
        $this->registry = new ComponentRegistry($this->controller);
        $this->component = new OwnershipComponent($this->registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->component);
    }

    /**
     * Test initialize method
     *
     * @return void
     */
//    public function testInitialize()
//    {
//        $this->markTestIncomplete('Not implemented yet.');
//    }

    /**
     * Test check method
     *
     * @return void
     */
    public function testCheck()
    {
        // case no session
        if($this->debug) debug('OWNERSHIP COMPONENT - testCheck: case no session');
        $result = $this->component->check(1);
        
        $this->assertNull($result);
        
        //case authenticated as superadmin
        if($this->debug) debug('OWNERSHIP COMPONENT - testCheck: case authenticated as superadmin');        
        $this->session->write('Auth.User.role', 'superadmin');
        $result = $this->component->check(1);
                
        $this->assertTrue($result);
       
        //case authenticated as admin and access as admin
        if($this->debug) debug('OWNERSHIP COMPONENT - testCheck: case authenticated as superadmin');        
        $this->session->write('Auth.User.role', 'admin');
        $result = $this->component->check(1, true);
                
        $this->assertTrue($result);

        //case authenticated as admin but no access allowed as admin
        if($this->debug) debug('OWNERSHIP COMPONENT - testCheck: case authenticated as superadmin');        
        $this->session->write('Auth.User.role', 'admin');
        $result = $this->component->check(1, true);
                
        $this->assertTrue($result);

        //case authenticated as the owner
        if($this->debug) debug('OWNERSHIP COMPONENT - testCheck: case authenticated as the owner');
        $this->session = $this->controller->request->session();
        $this->session->write('Auth.User.role', 'user');
        $this->session->write('Auth.User.id', 1);
        $result = $this->component->check(1);
                
        $this->assertTrue($result);
    }

    /**
     * Test flash method
     *
     * @return void
     */
//    public function testFlash()
//    {
//        $this->markTestIncomplete('Not implemented yet.');
//    }
}
