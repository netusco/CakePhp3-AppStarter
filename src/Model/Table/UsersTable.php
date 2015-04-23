<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->hasMany('Articles');
        $this->hasMany('PhotoCrop.Photocrops', ['dependent' => true]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('email', 'valid', [
                'rule' => 'email'
            ])
            ->add('email', [
                    'unique' => [
                            'rule' => 'validateUnique',
                            'provider' => 'table',
                            'message' => 'Un compte avec cette adresse existe déjà.'
                    ]
            ])
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->requirePresence('password', 'create')
            ->add('password', 'custom', [
                'rule' => [$this, 'passwordComplexe'],
                'message' => 'Le mot de passe ne respecte pas les règles (une majuscule minimum, un chiffre minimum, 8 caractères minimum).'
            ])
            ->notEmpty('password');

        return $validator;
    }
    
    /**
     * Update validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationUpdate(Validator $validator)
    {
        $validator
            ->add('email', 'valid', [
                'rule' => 'email'
            ])
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->requirePresence('password', 'create')
            ->add('password', 'custom', [
                'rule' => [$this, 'passwordComplexe'],
                'message' => 'Le mot de passe ne respecte pas les règles (une majuscule minimum, un chiffre minimum, 8 caractères minimum).'
            ])
            ->notEmpty('password');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
    
    /**
     * Vérification de la complexité du password choisi
     * 
     * @param string $password
     * @param obj $context object passed on validator calls
     * @return boolean true si toutes les règles sont validées
     */
    public function passwordComplexe($password = null, $context = null) {
        if (is_array($password)) {
            // Règle durant l'enregistrement
            $pass = $this->data['password'];
        } else {
            // Sinon, règle appelée pendant changement de password
            $pass = $password;
        }
        // Vérification de chaque règle séparemment
        // Plus simple à maintenir qu'une grosse regex
        if (strlen($pass) < 8) {
            return false;
        }
        
        $check = preg_match('/[A-Z]/', $pass);
        if (empty($check)) {
            return false;
        }
        
        $check = preg_match('/[a-z]/', $pass);
        if (empty($check)) {
            return false;
        }
        
        $check = preg_match('/[0-9]/', $pass);
        if (empty($check)) {
            return false;
        }

        return true;
    }
}
