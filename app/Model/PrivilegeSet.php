<?php
App::uses('AppModel', 'Model');
/**
 * PrivilegeSet Model
 *
 * @property Set $Set
 */
class PrivilegeSet extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'set_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'pid' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Set' => array(
			'className' => 'Set',
			'foreignKey' => 'set_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	# GET UNIQUE SET
	public function get_unique_sets(){
		return $this->query("SELECT DISTINCT set_id FROM `privilege_sets`");	
	}
}
