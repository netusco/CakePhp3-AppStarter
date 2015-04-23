<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Network\Email\Email;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController
{
    
    /**
     * beforeFilter callback, executed before every action in the controller.
     *
     * @param object $event
     * @return void
     */
    public function beforeFilter(\Cake\Event\Event $event) {
        
        parent::beforeFilter($event);
        
        // Allowing actions for not logged users
        $this->Auth->allow([
            'index',
            'view'
        ]);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $articles = $this->Articles->find('all');
        $this->set(compact('articles'));
    }
    
    /**
     * View method
     *
     * @param string|null $slug Article slug.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->first();
        $this->set('article', $article);
        $this->set('_serialize', ['article']);
        $this->set('title', "article");
    }

}
