<?php
App::uses('AppModel', 'Model');
/**
 * Monitor Model
 *
 * @property Company $Company
 * @property MonitorsVideo $MonitorsVideo
 * @property Timeline $Timeline
 * @property Old $Old
 * @property User $User
 */
class Monitor extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Monitor name should not be empty.',
			),
			'size' => array(
				'rule' => array('between', 4, 20),
                'message' => 'Monitor name should be at least 4 chars long.'
			),
			'checklimit' => array(
				'rule' => array('check_exceed_limit'),
				'message' => 'Your qouta limit has been exceeded.',
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	/*public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);*/

/**
 * hasMany associations
 *
 * @var array
 */
	/*public $hasMany = array(
		'MonitorsVideo' => array(
			'className' => 'MonitorsVideo',
			'foreignKey' => 'monitor_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Timeline' => array(
			'className' => 'Timeline',
			'foreignKey' => 'monitor_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);*/


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	/*public $hasAndBelongsToMany = array(
		'Old' => array(
			'className' => 'Old',
			'joinTable' => 'old_monitors',
			'foreignKey' => 'monitor_id',
			'associationForeignKey' => 'old_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'User' => array(
			'className' => 'User',
			'joinTable' => 'user_monitor',
			'foreignKey' => 'monitor_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);*/
	
	/* CHECK THE ALLOCATED MONITORS TO PARTICULAR COMPANY */
	public function check_exceed_limit(){
		
		# GET SOME DATA FROM OBJECT
		$company_id = isset($this->data['Monitor']['company_id']) ? $this->data['Monitor']['company_id'] : 0;
		$id = isset($this->data['Monitor']['id']) ? $this->data['Monitor']['id'] : 0;
		
		if(!$this->get_left_monitor($company_id) and (!$id > 0)){
			return false;
		}
		return true;
	}
	
	/* INSERTION OPERATION */
	public function insertion($data){
		$this->create();
		return $this->save($data);	
	}
	
	/*  GET LEFT MONITOR  */
	public function get_left_monitor($company_id){
		 $total = $this->get_total_assign_monitors($company_id);
		 $created = $this->total_created_monitor($company_id);
		 return $total - $created;
	}
	
	/* GET TOTAL MONITOR  */
	public function get_total_assign_monitors($company_id){
		Controller::loadModel('Company');
		$result = $this->Company->get_data($company_id);
		return isset($result['Company']['monitors']) ? $result['Company']['monitors'] : 0;
	}
	
	/* TOTAL CREATED MONITOR */
	public function total_created_monitor($company_id){
		return $this->find('count',array('conditions'=>array('Monitor.company_id'=>$company_id)));	
	}
	
	/*  GET ALL COMPANIES MONITOR */
	public function get_all_monitors($company_id){
		return $this->find('all',array('conditions'=>array('Monitor.company_id'=>$company_id),'order' => array('Monitor.id DESC')));	
	}
	
	/*  GET MONTIOR DATA */
	public function get_monitor_data($id){
		return $this->find('first',array('conditions'=>array('Monitor.id'=>$id)));	
	}
	
	/* CHECK MONITOR EXIST FOR PARTICULAR COMPANY */
	public function exist(){
		# GET IDS FROM POST DATA
		$company_id = isset($this->data['Monitor']['company_id']) ? $this->data['Monitor']['company_id'] : 0;
		$monitor_id = isset($this->data['Monitor']['id']) ? $this->data['Monitor']['id'] : 0;	
		
		return $this->find('first',array('conditions'=>array('Monitor.id'=>$monitor_id,'Monitor.company_id'=>$company_id)));
	}
	
	public function get_users_all_monitors($user_id){
		$query = "SELECT * FROM `monitors` join assign_monitors where monitors.id = assign_monitors.monitor_id and assign_monitors.user_id = '".$user_id."'";    
		 return $this->query($query);
	}

}
