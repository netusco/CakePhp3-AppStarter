<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Cake\Auth\DefaultPasswordHasher;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends IntegrationTestCase
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
     * Test isAuthorized method
     *
     * @return void
     */
    public function testIsAuthorized()
    {
        // test not Authorized
        if($this->debug) debug('USERS CONTROLLER - testIsAuthorized: test not Authorized');
        $this->get('/users/dashboard');
        $this->assertResponseCode(302);
        $this->assertSession(null, 'Auth.User.id');
        $this->assertRedirect(['controller' => 'Users', 'action' => 'login']);

        // test Authorized
        if($this->debug) debug('USERS CONTROLLER - testIsAuthorized: test Authorized');
        $this->session(['Auth.User.id' => 1]);
        $this->get('/users/dashboard');
        $this->assertResponseOk();
        $this->assertSession(1, 'Auth.User.id');
        $this->assertNoRedirect();

    }

    /**
     * Test login method
     *
     * @return void
     */
    public function testLogin()
    {
        $this->User = new UsersController;

        // case no user connected (first we logout)
        if($this->debug) debug('USERS CONTROLLER - testLogin: case no user connected');
        $this->User->logout();

        $this->assertEquals(false, $this->User->Auth->user('id'));

        $data = [
            'email' => EMAIL_TO_TEST,
            'password' => 'juVni4tr3'
        ];
        $this->post('/login', $data);
        $this->assertResponseCode(302);
        $this->assertSession(1, 'Auth.User.id');
        $this->assertRedirect();

        // case user already connected
        if($this->debug) debug('USERS CONTROLLER - testLogin: case user already connected');
        $this->session(['Auth.User.id' => 2]);
        $this->post('/login', $data);

        $this->assertResponseCode(302);
        $this->assertSession(2, 'Auth.User.id');
        $this->assertRedirect('/');

    }

    /**
     * Test logout method
     *
     * @return void
     */
    public function testLogout()
    {
        if($this->debug) debug('USERS CONTROLLER - testLogout');
        $data = [
            'email' => EMAIL_TO_TEST,
            'password' => 'juVni4tr3'
        ];
        // first we log in
        $this->post('/login', $data);
        $this->assertSession(1, 'Auth.User.id');

        $this->get('/logout');
        $this->assertResponseCode(302);
        $this->assertRedirect('/');
        $this->assertSession(null, 'Auth.User.id');
    }

    /**
     * Test dashboard method
     *
     * @return void
     */
    public function testDashboard()
    {               
        // case not authorized
        if($this->debug) debug('USERS CONTROLLER - testDashboard: case not authorized');
        $this->get('/users/dashboard');
        $this->assertResponseCode(302);
        $this->assertSession(null, 'Auth.User.id');
        $this->assertRedirect();

        // case authorized
        if($this->debug) debug('USERS CONTROLLER - testDashboard: case authorized');
        // first we log in
        $this->session(['Auth.User.id' => 2]);
        $this->get('/users/dashboard');
        $this->assertResponseOk();
        $this->assertNoRedirect();
        $this->assertTemplate('dashboard');
    }

    /**
     * Test add method
     * ATENTION: Sometimes fail due to Mail transport problems
     *
     * @return void
     */
    public function testAdd()
    {
        $data = [
            'nom' => 'test',
            'prenom' => 'user',
            'email' => 'testuser@somemail.com',
            'password' => 'wadacatachU49'
        ];

        // case email exists 
        if($this->debug) debug('USERS CONTROLLER - testAdd: case email exists');
        $newData= array_merge($data, ['email' => EMAIL_TO_TEST]);
        $this->post('/inscription', $newData);
        $this->assertResponseOk();
        $this->assertNoRedirect();

        // case password empty
        if($this->debug) debug('USERS CONTROLLER - testAdd: case password empty');
        $newData= array_merge($data, ['password' => '']);
        $this->post('/inscription', $newData);
        $this->assertResponseOk();
        $this->assertNoRedirect();

        // case all OK
        if($this->debug) debug('USERS CONTROLLER - testAdd: case all OK');
        $this->post('/inscription', $data);
        $query = $this->Users->find()
            ->where(['email' => 'testuser@somemail.com'])
            ->select('nom')
            ->first();
        $this->assertResponseCode(302);
        $this->assertRedirect('/');
        $this->assertEquals('test', $query['nom']);
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $data = [
            'nom' => 'test',
            'prenom' => 'user',
        ];
        $this->session(['Auth.User.id' => 2]);

        // case all OK
        if($this->debug) debug('USERS CONTROLLER - testEdit: case all OK');
        $this->post('/users/edit/2', $data);
        $query = $this->Users->find()
            ->where(['id' => 2])
            ->contain('Photocrops')
            ->select('nom')
            ->first();
        $this->assertResponseCode(302);
        $this->assertRedirect();
        $this->assertEquals('test', $query['nom']);
    }

    /**
     * Test reinitialiserMotPasse method
     * ATENTION: Sometimes fail due to Mail transport problems
     *
     * @return void
     */
    public function testReinitialiserMotPasse()
    {
        if($this->debug) debug('USERS CONTROLLER - testReinitialiserMotPasse');
        $data = ['email' => EMAIL_TO_TEST];

        $this->post('/reinitialiser_mot_de_passe', $data);

        $query = $this->Users->find()
            ->where(['email' => $data['email']])
            ->select('change_pass_code')
            ->first();
        // remove the comment below if we want to test the Mail transport
        // $this->assertResponseOk(); 
        $this->assertNotEquals(null, $query['change_pass_code']);
    }

    /**
     * Test nouveauMotPasse method
     *
     * @return void
     */
    public function testNouveauMotPasse()
    {

        // case code is null in db
        if($this->debug) debug('USERS CONTROLLER - testNouveauMotPasse: case code is null in db');
        $data = ['email' => 'thirduser@somemail.com'];                
        $query = $this->Users->find()
            ->where(['email' => $data['email']])
            ->select('change_pass_code')
            ->first();
        $this->get('/users/nouveauMotPasse/'.$query['change_pass_code']);

        $this->assertResponseCode(302);
        $this->assertEquals(null, $query['change_pass_code']);
        $this->assertRedirect(['controller' => 'Users', 'action' => 'reinitialiserMotPasse']);

        // case date is not recent
        if($this->debug) debug('USERS CONTROLLER - testNouveauMotPasse: case date is not recent');
        $data = ['email' => 'seconduser@somemail.com'];        
        $query = $this->Users->find()
            ->where(['email' => $data['email']])
            ->select('change_pass_code')
            ->first();
        $this->get('/users/nouveauMotPasse/'.$query['change_pass_code']);

        $this->assertResponseCode(302);
        $this->assertNotEquals(null, $query['change_pass_code']);
        $this->assertRedirect(['controller' => 'Users', 'action' => 'reinitialiserMotPasse']);

        // case after a new reinitialiserMotPasse with code and actual date saved in db
        if($this->debug) debug('USERS CONTROLLER - testNouveauMotPasse: case after a new reinitialiserMotPasse with code and actual date saved in db');
        $data = ['email' => EMAIL_TO_TEST];
        $this->post('/reinitialiser_mot_de_passe', $data);
        $query = $this->Users->find()
            ->where(['email' => $data['email']])
            ->select('change_pass_code')
            ->first();
        $this->get('/users/nouveauMotPasse/'.$query['change_pass_code']);

        $this->assertResponseCode(302);
        $this->assertNotEquals(null, $query['change_pass_code']);
        $this->assertRedirect(['controller' => 'Users', 'action' => 'changementMotPasse/'.$query['change_pass_code']]);
    }

    /**
     * Test changementMotPasse method
     *
     * @return void
     */
    public function testChangementMotPasse()
    {        
        // case call from the link from the email
        if($this->debug) debug('USERS CONTROLLER - testChangementMotPasse: case call from the link from the email');
        $this->get('/users/changementMotPasse/2400fd3226c673532e8e68d35c8c31115a83f6c3' ); 

        $this->assertResponseOk();
        $this->assertNoRedirect();

        // case authenticated user
        if($this->debug) debug('USERS CONTROLLER - testChangementMotPasse: case authenticated user');
        $this->session([
            'Auth.User.id' => 2, 
            'Auth.User.email' => 'seconduser@somemail.com'
        ]);

        $data = [
            'new_pass' => 'juVni4tr3',
            'new_pass_confirm' => 'juVni4tr3',
            'password' => 'HuaB78lo'
        ];

        $this->post('/users/changementMotPasse', $data); 
        $query = $this->Users->find()
            ->where(['email' => 'seconduser@somemail.com'])
            ->select('password')
            ->first();
        $hasher = new DefaultPasswordHasher();

        $this->assertResponseCode(302);
        $this->assertEquals(true, $hasher->check($data['new_pass'], $query['password']));
        $this->assertRedirect();

        // case non authenticated user
        if($this->debug) debug('USERS CONTROLLER - testChangementMotPasse: case non authenticated user');
        $this->session([
            'Auth.User.id' => 2, 
            'Auth.User.email' => 'seconduser@somemail.com'
        ]);

        $data = [
            'password' => '2400fd3226c673532e8e68d35c8c31115a83f6c3',
            'new_pass' => 'juVni4tr3',
            'new_pass_confirm' => 'juVni4tr3',
            'password' => 'HuaB78lo'
        ];

        $this->post('/users/changementMotPasse', $data); 
        $query = $this->Users->find()
            ->where(['email' => 'seconduser@somemail.com'])
            ->select('password')
            ->first();
        $hasher = new DefaultPasswordHasher();

        $this->assertResponseCode(302);
        $this->assertEquals(true, $hasher->check($data['new_pass'], $query['password']));
        $this->assertRedirect();
    }

}
