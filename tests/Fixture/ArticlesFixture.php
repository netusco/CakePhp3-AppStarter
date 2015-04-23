<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ArticlesFixture extends TestFixture
{
    
    /**
     * fields property
     *
     * @var array
     */
    
    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'articles'];
    
    /**
     * method init
     * 
     * @return void
     */
    public function init() {
        
        $this->records = [
        [
            'title' => 'The title',
            'slug' => 'the-title',
            'body' => 'This is the article body.',
            'created' => '2015-03-03 16:33:57',
            'user_id' => 1,
        ], [
            'title' => 'A title once again',
            'slug' => 'a-title-once-again',
            'body' => 'And the article body follows.',
            'created' => '2015-03-03 16:35:37',
            'user_id' => 1,
        ], [
            'title' => 'Title strikes back',
            'slug' => 'title-strikes-back',
            'body' => 'This is really exciting! Not.',
            'created' => '2015-03-03 16:39:22',
            'user_id' => 2,
        ]
    ];
        parent::init();
    }
    
 }
