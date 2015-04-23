<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ArticlesController Test Case
 */
class AdminArticlesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'articles' => 'app.articles'
    ];
    
    /**
     * Debug to display cases titles
     * 
     * @var bool
     */
    public $debug = true;

    /**
     * Tear Down
     *
     * @return void
     */
    public function tearDown() {
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
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testIndex: user without admin role'); 
        $this->get('/admin/articles/index');
        $this->session([
            'Auth.User.id' => 1,
            'Auth.User.role' => 'user'
        ]);
        $this->assertRedirect('/login');
        $this->assertResponseCode(302); // redirection - only allowed for admins
        $this->assertResponseEmpty();

        // all Ok
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testIndex: all Ok'); 
        $this->session([
            'Auth.User.id' => 1,
            'Auth.User.role' => 'admin'
        ]);

        $this->get('/admin/articles/index');
        $this->assertResponseOk();
        $this->assertResponseContains('Articles');
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
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testView: all Ok');

        $this->get('/admin/articles/view/the-title');
        $this->assertResponseOk();
        $this->assertResponseContains('article');
        $this->assertEquals('The title', $this->viewVariable('article')->title);
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $data = [
            'title' => 'This is only a title for test',
            'body' => 'This is the body of the test article',
            'user_id' => 2
        ];
        $this->session([
            'Auth.User.id' => 2,
            'Auth.User.role' => 'admin'
        ]);
        
        // case title repeated
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testAdd: case title repeated');
        $newData= array_merge($data, ['title' => 'The title']);
        $this->post('/admin/articles/add', $newData);
        $this->assertResponseOk();
        $this->assertNoRedirect();
        $this->assertEquals('title', key($this->viewVariable('article')->errors()));
        
        // case title empty
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testAdd: case title empty');
        $newData= array_merge($data, ['title' => '']);
        $this->post('/admin/articles/add', $newData);
        $this->assertResponseOk();
        $this->assertNoRedirect();
        $this->assertEquals('title', key($this->viewVariable('article')->errors()));
        
        // case all OK
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testAdd: case all OK');
        $this->post('/admin/articles/add', $data);
        $articles = TableRegistry::get('Articles');
        $query = $articles->find()
                ->where(['title' => 'This is only a title for test'])
                ->select('body')
                ->first();
        $this->assertResponseCode(302);
        $this->assertRedirect('/admin/articles');
        $this->assertEquals('This is the body of the test article', $query['body']);
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $data = [
            'title' => 'This is only a title for test',
            'body' => 'This is the body of the test article',
            'user_id' => 2
        ];
        $this->session([
            'Auth.User.id' => 2,
            'Auth.User.role' => 'admin'
        ]);
        
        // case title repeated
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testEdit: case title repeated');
        $newData= array_merge($data, ['title' => 'The title']);
        $this->post('/admin/articles/edit/a-title-once-again', $newData);
        $this->assertResponseOk();
        $this->assertNoRedirect();
        $this->assertEquals('title', key($this->viewVariable('article')->errors()));
        
        // case title empty
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testEdit: case title empty');
        $newData= array_merge($data, ['title' => '']);
        $this->post('/admin/articles/edit/a-title-once-again', $newData);
        $this->assertResponseOk();
        $this->assertNoRedirect();
        $this->assertEquals('title', key($this->viewVariable('article')->errors()));
        
        // case all OK
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testEdit: case all OK');
        $this->post('/admin/articles/edit/a-title-once-again', $data);
        $articles = TableRegistry::get('Articles');
        $query = $articles->find()
                ->where(['title' => 'This is only a title for test'])
                ->select('body')
                ->first();
        $this->assertResponseCode(302);
        $this->assertRedirect('/admin/articles');
        $this->assertEquals('This is the body of the test article', $query['body']);
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testdelete()
    {
        $slug = 'the-title';
        $this->session([
            'Auth.User.id' => 1,
            'Auth.User.role' => 'admin'
        ]);
        
        // case all OK
        if($this->debug) debug('ADMIN ARTICLES CONTROLLER - testDelete: case all OK');
        $articles = TableRegistry::get('Articles');
        $query = $articles->find()
                ->where(['slug' => $slug])
                ->select('title')
                ->first();
        $this->assertEquals('The title', $query['title']);

        $this->post('/admin/articles/delete/'.$slug);
        $articles = TableRegistry::get('Articles');
        $query = $articles->find()
                ->where(['slug' => $slug])
                ->select('title')
                ->first();
        $this->assertEquals(null, $query['title']);
        $this->assertResponseCode(302);
        $this->assertRedirect('/admin/articles');
    }
}

