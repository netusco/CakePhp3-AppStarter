<?php
namespace App\Test\TestCase\Controller;

// use App\Controller\ArticlesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\ArticlesController Test Case
 */
class ArticlesControllerTest extends IntegrationTestCase
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
        // all Ok
        if($this->debug) debug('ARTICLES CONTROLLER - testIndex: all Ok');

        $this->get('/articles/index');
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
        // all Ok
        if($this->debug) debug('ARTICLES CONTROLLER - testView: all Ok');

        $this->get('/articles/view/the-title');
        $this->assertResponseOk();
        $this->assertResponseContains('article');
        $this->assertEquals('The title', $this->viewVariable('article')->title);
    }

}

