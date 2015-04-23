<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController {

        
    /**
     * Dashboard method
     *
     * @return void
     */
    public function dashboard()
    {
        $this->set('title', "Tableau de board d'Administrateurs");
    }
    
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
        $this->set('title', "Users");
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ( $this->Ownership->check($id, true) ) {
            $user = $this->Users->get($id);
            $this->set('user', $user);
            $this->set('_serialize', ['user']);
            $this->set('title', "user");
        }
    }

    /**
     * edit method
     *
     * @param string|null $id user id.
     * @return void redirects on successful edit, renders view otherwise.
     * @throws \cake\network\exception\notfoundexception when record not found.
     */
    public function edit($id = null)
    {
        if( $this->Ownership->check($id, true) ) {
            $user = $this->Users->get($id);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($this->Users->save($user)) {
                    $this->Flash->success('The user has been saved.');
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error('The user could not be saved. Please, try again.');
                }
            }
            $this->set(compact('user'));
            $this->set('_serialize', ['user']);
            $this->set('title', "Edit User");
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {    
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Ownership->check($id, false, ['action' => 'index']) === true ) {
            $user = $this->Users->get($id);
            if ( $this->Users->delete($user) ) {
                $this->Flash->success('The user has been deleted.');
            } else {
                $this->Flash->error('The user could not be deleted. Please, try again.');
            }
            return $this->redirect(['action' => 'index']);
        }
    }
    
}
