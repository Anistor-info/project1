<?php
App::uses('AppModel', 'Model');
/**
 * State Model
 *
 * @property Country $Country
 */
class State extends AppModel {
/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'state_id';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Country' => array(
			'className' => 'Country',
			'foreignKey' => 'country_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function GetStates($country_id){
		return $this->find('all',array('conditions' => array('State.country_id'=>$country_id)));	
	}
}
