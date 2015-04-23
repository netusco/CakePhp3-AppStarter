<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Inflector;

class ArticlesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->primaryKey('id');
        $this->table('articles');
        $this->displayField('title');
        $this->belongsTo('Users');
    }


    /**
    * beforeSave, starts a time before a save is initiated.
    *
    * @param Event $event, Entity $entity, ArrayObject $options
    * @return boolean true or false if slug already used
    */
    public function beforeSave($event, $entity, $options) 
    {
        if($entity->title) {
            $entity->slug = strtolower(Inflector::slug($entity->title, '-')); 
        }

        return true;
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
            ->add('title', [
                'custom' => [
                    'rule' => [$this, 'uniqueSlug'],
                    'provider' => 'custom',
                    'message' => 'Un title pareil existe déjà.'
                ]
            ])
            ->notEmpty('title')
            ->notEmpty('body');

        return $validator;
    }

    /**
     * Custom rule to verify that the slug of the field is unique in the db
     *
     * @param string $element
     * @param obj $context object passed on validator calls
     * @return boolean true if rule is valid
     */
    public function uniqueSlug($element = null, $context = null) 
    {
        if(isset($context['newRecord']) && $context['newRecord']) {
            if($this->findBySlug(Inflector::slug($element, '-'))->first()) {
                return false; 
            }
        } else if(!empty($this->findBySlug(Inflector::slug($element, '-'))->first()) ) {
            if($this->findBySlug(Inflector::slug($element, '-'))->first()->extract(['id'])['id'] !== $context['data']['id']) {
                return false; 
            }
        }

        return true;
    }
}
