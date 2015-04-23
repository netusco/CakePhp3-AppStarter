<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UsersController Test Case
 */
class AdminUsersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Users' => 'app.users',
        'Photocrops' => 'plugin.PhotoCrop.photocrops'
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
     **/
    public function setUp() {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * Tear Down
     *
     * @return void
     */
    public function tearDown() {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test testIndex method
     *
     * @return void
     */
    public function testIndex()
    {

        // user without admin role
        if($this->debug) debug('ADMIN USERS CONTROLLER - testIndex: user without admin role'); 
        $this->get('/admin/users/index');
        $this->session([
            'Auth.User.id' => 1,
            'Auth.User.role' => 'user'
        ]);
        $this->assertRedirect('/login');
        $this->assertResponseCode(302); // redirection - only allowed for admins
        $this->assertResponseEmpty();

        // all Ok
        if($this->debug) debug('ADMIN USERS CONTROLLER - testIndex: all Ok'); 
        $this->session([
            'Auth.User.id' => 1,
            'Auth.User.role' => 'admin'
        ]);

        $this->get('/admin/users/index');
        $this->assertResponseOk();
        $this->assertResponseContains('Users');
    }

    /**
     * Test testView method
     *
     * @return void
     */
    public function testView()
    {
        $this->session([
            'Auth.User.id' => 1,
            'Auth.User.role' => 'admin'
        ]);

        // all Ok
        if($this->debug) debug('ADMIN USERS CONTROLLER - testView: all Ok');

        $this->get('/admin/users/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('user');
        $this->assertEquals(EMAIL_TO_TEST, $this->viewVariable('user')->email);
    }

    /**
     * test edit method
     *
     * @return void
     */
    public function testedit()
    {
        $data = [
            'nom' => 'Edited User',
            'prenom' => 'Test',
            'email' => 'fake@testemail.com'
        ];
        $this->session([
            'Auth.User.id' => 2,
            'Auth.User.role' => 'admin'
        ]);
        
        // case email repeated
        if($this->debug) debug('ADMIN USERS CONTROLLER - testedit: case email repeated');
        $newdata= array_merge($data, ['email' => EMAIL_TO_TEST]);
        $this->post('/admin/users/edit/2', $newdata);

        $this->assertResponseOk();
        $this->assertNoRedirect();
        $this->assertEquals('email', key($this->viewvariable('user')->errors()));
        
        // case email empty
        if($this->debug) debug('ADMIN USERS CONTROLLER - testedit: case email empty');
        $newdata= array_merge($data, ['email' => '']);
        $this->post('/admin/users/edit/2', $newdata);
        $this->assertResponseok();
        $this->assertNoRedirect();
        $this->assertEquals('email', key($this->viewvariable('user')->errors()));
        
        // case all ok
        if($this->debug) debug('ADMIN USERS CONTROLLER - testedit: case all ok');
        $this->post('/admin/users/edit/2', $data);
        $query = $this->Users->find()
                ->where(['email' => 'fake@testemail.com'])
                ->select('nom')
                ->first();
        $this->assertResponsecode(302);
        $this->assertRedirect('/admin/users');
        $this->assertEquals('Edited User', $query['nom']);
    }

    /**
     * test delete method
     *
     * @return void
     */
    public function testdelete()
    {
        $this->session([
            'Auth.User.id' => 2,
            'Auth.User.role' => 'admin'
        ]);
        
        // case admin access
        if($this->debug) debug('ADMIN USERS CONTROLLER - testdelete: case admin access');
        $query = $this->Users->find()
                ->where(['id' => 1])
                ->select('email')
                ->first();
        $this->assertEquals(EMAIL_TO_TEST, $query['email']);

        $this->post('/admin/users/delete/1');
        $query = $this->Users->find()
                ->where(['id' => 1])
                ->select('email')
                ->first();
        $this->assertEquals(EMAIL_TO_TEST, $query['email']);
        $this->assertResponsecode(302);
        $this->assertRedirect('/admin/users');

        // case superadmin access
        if($this->debug) debug('ADMIN USERS CONTROLLER - testdelete: case superadmin access');
        $this->session([
            'Auth.User.id' => 2,
            'Auth.User.role' => 'superadmin'
        ]);

        $query = $this->Users->find()
                ->where(['id' => 1])
                ->select('email')
                ->first();
        $this->assertEquals(EMAIL_TO_TEST, $query['email']);

        $this->post('/admin/users/delete/1');
        $this->assertSession(2, 'Auth.User.id');
        $this->assertSession('superadmin', 'Auth.User.role');
        $query = $this->Users->find()
                ->where(['id' => 1])
                ->select('email')
                ->first();
        $this->assertEquals(null, $query['email']);
        $this->assertResponsecode(302);
        $this->assertRedirect('/admin/users');
    }
}

