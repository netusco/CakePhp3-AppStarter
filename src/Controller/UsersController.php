<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Network\Email\Email;
use Cake\Auth\DefaultPasswordHasher;
use Cake\View\Helper\UrlHelper;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('PhotoCrop.Photocrop', [
            'profile' => [
                'maxNumImagesAllowed' => 5,
                'maxWidthPreview' => 500, //pxs
            ],
        ]);
    }

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
            'add',
            'login',
            'logout',
            'reinitialiserMotPasse', 
            'nouveauMotPasse', 
            'changementMotPasse'
        ]);
    }
    
    /**
     * Check if user is authorized
     * Authorization restricted by ownership should use the method check() within Ownership Component
     * 
     * @param array $user
     * @return boolean
     */
    public function isAuthorized($user)
    {   
        if (parent::isAuthorized($user)) {
            $action = $this->request->params['action'];
            
            // Allowing actions for logged users (excluding admin actions)
            return true;
        }
        
        return false;
    }
    
    /**
     * Login method
     * 
     * @return void Redirects if logged
     */
    public function login()
    {
        if ($this->request->is('post')) {
            
            if($this->Auth->user('id')) {
                $this->Flash->warning(__('Utilisateur déjà connecté'), 'default', array(), 'auth');
                return $this->redirect('/');
            } else {
                $user = $this->Auth->identify();
                if ($user && $user['actif']) {
                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());
                }
                $this->Flash->error('Vos identifiants sont incorrects.');
            }
        }
    }
    
    /**
     * Logout method
     * 
     * @return void Redirects after logging out
     */
    public function logout()
    {
        $this->Flash->success('Merci pour le raccordement avec nous, bonne continuation.');
        return $this->redirect($this->Auth->logout());
    }
        
    /**
     * Dashboard method
     *
     * @return void
     */
    public function dashboard()
    {
        $this->set('title', "Tableau de board");
    }
    
    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            // $user is patched within the Component Photocrop
            $user = $this->Photocrop->preparePhotocropsAndPatchEntity($user);
            if ($this->Users->save($user)) {
                $this->Flash->success('The user has been saved.');
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error('The user could not be saved. Please, try again.');
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        $this->set('title', "Add User");
    }
    
    /**
     * Edit method, gets the id from the session user id
     *
     * @return void Redirects on successful edit, renders view otherwise.
     */
    public function edit() {
        $user = $this->Users->get($this->request->session()->read('Auth.User.id'), ['contain' => ['Photocrops']]);    
        if($this->request->is('post')) {
            
            // $user is patched within the Component Photocrop
            $user = $this->Photocrop->preparePhotocropsAndPatchEntity($user);
            if ($this->Users->save($user)) {
                $this->request->session()->write('Auth.User', $user);
                $this->Flash->success("Le profil a été enregistré.");
                return $this->redirect($this->referer());
            } else {
                $this->Flash->error("Le profil de l'utilisateur n'a pas pu être sauvé. Se il vous plaît, essayez de nouveau.");
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        $this->set('title', "Editer le profil");
    }
    
    /**
     * Demande de rappel d'un mot de passe oublié par l'utilisateur
     * Génère un ticket temporaire de changement de mot de passe
     * => Accès par vue User::login()
     * 
     * @return void
     */
    public function reinitialiserMotPasse() {
        $content_title = "Réinitialisation de mot de passe";
        $this->layout = "login";

        if ($this->request->is(['post'])) {
            $data = $this->request->data;
            $userEntity = $this->Users->find('all')
                ->where(['email' => $data['email'], 'actif' => true])
                ->first();
            if (!empty($userEntity)) {                
                $user = $userEntity->toArray();
                // Génération d'une clé                
                $code = sha1($user['email'] . rand(0, 100));
                $link = Router::url(['controller' => 'Users', 'action' => 'nouveauMotPasse',  $code], true);

                // Sauvegarde des nouvelles données de réinit de pass
                $userEntity->change_pass_code = $code;
                $userEntity->change_pass_date = date('Y-m-d H:i:s');
                $this->Users->save($userEntity);                 

                $email = new Email('default');
                try {
                    $email->from([EMAIL_PAS_RESPONDRE => APP_NAME])
                        ->to([$user['email'] => $user['prenom']])
                        ->subject("Reinitialisation du mot de passe")
                        ->emailFormat("html")
                        ->template('reinitialiser_mot_pass', 'default')
                        ->viewVars(['link' => $link, 'title_for_layout' => "Lien de réinitialisation"])
                        ->send('My message');
                    $this->Flash->success("Un email a été envoyé à votre adresse de messagerie contenant les instructions pour la réinitialisation de votre mot de passe.");
                } catch (Exception $ex) {
                    echo 'Exception : ', $ex->getMessage(), "\n";
                }                              

                return $this->redirect($this->referer());
            } else {
                $this->Flash->error("L'adresse e-mail ne existe pas, ou ce compte a été désactivé. Contactez un administrateur pour plus de précision.");
            }
        }
        $this->set(compact('content_title'));
    }

    /**
     * Génération d'un mot de passe temporaire et reset des données de changement de passe
     * 
     * @return void
     */
    public function nouveauMotPasse() {
        $user = array();

        $content_title = "Création d'un mot de passe";
        $this->layout = "login";
        if (!empty($this->request->params['pass'][0])) {
            $code = $this->request->params['pass'][0];
            $userEntity = $this->Users->find('all', [
                'fields' => ['change_pass_date', 'id']
            ])
                ->where(['change_pass_code' => $code])
                ->first();
            $user = ($userEntity) ? $userEntity->toArray() : false;
        }    
        
        if (!$user) {
            $this->Flash->error("Les informations de ce changement de mot de passe sont incorrectes. Veuillez revérifier le lien que vous avez reçu par email ou faites une nouvelle demande.");         
            return $this->redirect(['action' => 'reinitialiserMotPasse']);
        }
        
        if (!$user['change_pass_date']->wasWithinLast(HOURS_TO_EXPIRE_TOKEN_NEW_PASS.' hours')) {            
            $this->Flash->error("La date limite pour cette demande changement de mot de passe a expiré. Veuillez la renouveller pour obtenir un nouveau mot de passe.");
            return $this->redirect(['action' => 'reinitialiserMotPasse']);
        }
        
        $this->set(compact('content_title'));
        
        return $this->redirect(['action' => 'changementMotPasse', $this->request->params['pass'][0]]);
    }
    
    /**
     * Changement du mot de passe de l'utilisateur
     * 
     * => Accessed from nouveauMotPasse or from User profile
     * @param int $change_pass_code
     * @return void
     */
    public function changementMotPasse($change_pass_code = null) {       
        $content_title = "Changement de mot de passe";
        $this->layout = "login";
        
        if ($this->request->is('post')) { 
            $data = $this->request->data;
            $data['old_pass'] = (is_null($change_pass_code)) ? $this->request->data['password'] : false;
            
            // Check la correspondance des nouveaux pass
            if($this->validateNewPass($data)) {
                // Arrivé jusque là, le changement de mot de passe remplit toutes les conditions et peut être validé   
                $user = ($this->Auth->user('id'))
                    ? $this->Auth->user()
                    : $this->Users->find('all')->select(['id', 'email'])->where(['change_pass_code' => $change_pass_code])->firstOrFail()->toArray();

                $user = array_merge($user, [
                    'password' => $data['new_pass'], // Hashage dans le Model Entity User
                    'change_pass_code' => null,
                    'change_pass_date' => null
                ]);
                $user = $this->Users->newEntity($user, ['validate' => 'update']);
                $result = $this->Users->save($user);    
               
                if ($data['old_pass'] && $result) {
                    $this->Flash->success(__('Votre nouveau mot de passe a été enregistrée.'));
                    return $this->redirect(['action' => 'dashboard']);
                } else if($result) {
                    $this->Flash->info(__('Se il vous plaît veuillez vous connecter avec votre nouveau mot de passe'));
                    return $this->redirect(['action' => 'login']);
                }
            } else {
                return $this->redirect($this->referer());
            }
        }
        $this->set(compact('content_title', 'change_pass_code'));
    }
    
    /**
     * Given the $data operates validations for new password, redirects if it doesn't pass the validation
     * 
     * @param array $data
     * @return bool
     */
    private function validateNewPass($data) {
        
        // Check that pass and confirm pass are equals
        if ($data['new_pass'] !== $data['new_pass_confirm']) {
            $this->Flash->error("Les deux nouveaux mots de passe ne correspondent pas.");                    
            return false;
        }
        
        // Check la complexité du nouveau pass
        if (!$this->Users->passwordComplexe($data['new_pass'])) {
            $this->Flash->error("Le nouveau mot de passe ne respecte pas les règles de complexité. (une majuscule minimum, un chiffre minimum, 8 caractères minimum)");
            return false;
        }
        
        // If it doesn't come from a forget pass, check that old pass is correct
        if ($data['old_pass']) {
            $userEntity = $this->Users->find('all')
                ->where(['id' => $this->Auth->user('id')])
                ->select(['password'])
                ->first();
            $hasher = new DefaultPasswordHasher();
            $bcrypt_pass_check = $hasher->check($data['old_pass'], $userEntity["password"]);
            if (empty($userEntity) || !$bcrypt_pass_check) {
                $this->Flash->error("Le mot de passe actuel n'est pas le bon.");
                return false;
            }
        }
        
        return true;
    }
    
}
