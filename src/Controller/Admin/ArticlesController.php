<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends AppController {

        
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('articles', $this->paginate($this->Articles));
        $this->set('_serialize', ['articles']);
        $this->set('title', "Articles");
    }

    /**
     * View method
     *
     * @param string|null $slug Article id.
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

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->data);
            if ($this->Articles->save($article)) {
                $this->Flash->success('The article has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The article could not be saved. Please, try again.');
            }
        }
        $this->set(compact('article'));
        $this->set('_serialize', ['article']);
        $this->set('title', "Add Article");
    }

    /**
     * edit method
     *
     * @param string|null $slug article id.
     * @return void redirects on successful edit, renders view otherwise.
     * @throws \cake\network\exception\notfoundexception when record not found.
     */
    public function edit($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->first();
        if( $this->Ownership->check($article->user_id, true) ) {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $article = $this->Articles->patchEntity($article, $this->request->data);
                if ($this->Articles->save($article)) {
                    $this->Flash->success('The article has been saved.');
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error('The article could not be saved. Please, try again.');
                }
            }
            $this->set(compact('article'));
            $this->set('_serialize', ['article']);
            $this->set('title', "Edit Article");
        }
    }

    /**
     * Delete method
     *
     * @param string|null $slug Article id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($slug = null)
    {    
        $this->request->allowMethod(['post', 'delete']);
        $article = $this->Articles->findBySlug($slug)->first();
        if ($this->Ownership->check($article->user_id, false, ['action' => 'index']) === true ) {
            if ( $this->Articles->delete($article) ) {
                $this->Flash->success('The article has been deleted.');
            } else {
                $this->Flash->error('The article could not be deleted. Please, try again.');
            }
            return $this->redirect(['action' => 'index']);
        }
    }
    
}

