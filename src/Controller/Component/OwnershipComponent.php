<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class OwnershipComponent extends Component
{
    public $components = ['Flash'];
    
    protected $_defaultConfig = [
        'url' => "/",
        'message' => "Vous n'êtes pas autorisé à accéder à cet emplacement."
    ];
    
    /**
     * Initialize properties.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->controller = $this->_registry->getController();
        $this->session = $this->controller->request->session();
    }
    
    /**
     * Check if the user id given for the action is the same as the user id connected
     * 
     * @param int $id
     * @param string|null $url
     * @param bool|null $message
     * @return boolean|redirect
     */
    public function check($id, $allowAdmin = false, $url = null, $message = null) 
    {
        if( $this->session->read('Auth.User.id') !== (int)$id
		&& $this->session->read('Auth.User.role') !== 'superadmin'
		&& ( ($allowAdmin && $this->session->read('Auth.User.role') !== 'admin') || !$allowAdmin ) ) {
            
            $url = is_null($url) ? $this->_config['url'] : $url;            
            $message = is_null($message) ? $this->_config['message'] : $message;
                
            $this->flash($message);
            $url = ($url === 'referer') ? $this->controller->referer() : $url;

            return $this->controller->redirect($url, null, true);
        } 
        
        return true;
    }
    
    /**
     * Set a flash error message.
     *
     * @param string|false $message The message to set.
     * @return void
     */
    public function flash($message)
    {        
        if ( !($message === false || filter_var($message, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false) ) {
            $this->Flash->error($message);
        }
    }
    
}
