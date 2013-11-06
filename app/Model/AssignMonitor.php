<?php
App::uses('AppModel', 'Model');
/**
 * AssignMonitor Model
 *
 * @property Monitor $Monitor
 * @property User $User
 */
class AssignMonitor extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'monitor_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
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
	
	/*
	/* Method assign_monitor
	/* @data from post 
	/* @monitor id 
	*/
	public function assign_monitors($data,$monitor_id){
		# GET ASSIGN MONITOR
		$assign_data = isset($data['Monitor']['assign_monitor']) ? $data['Monitor']['assign_monitor'] : array();
		
		# INSERTION OPERATION
		for($i=0;$i<count($assign_data);$i++){
			$post_data['AssignMonitor']['monitor_id'] =  $monitor_id;
			$post_data['AssignMonitor']['user_id'] =  $assign_data[$i];
			$result[] = $this->insertion($post_data);
		}
		return $result; 
	}
	
	
	/*
	/* Method update_assign_monitor
	/* @data from post 
	/* @monitor id 
	*/
	public function update_assign_monitors($data,$monitor_id){
	
		# GET ASSIGN MONITOR
		$this->unassign_all_users($monitor_id);
		$assign_data = isset($data['Monitor']['assign_monitor']) ? $data['Monitor']['assign_monitor'] : array();
				
		# INSERTION OPERATION
		for($i=0;$i<count($assign_data);$i++){
			$post_data['AssignMonitor']['monitor_id'] =  $monitor_id;
			$post_data['AssignMonitor']['user_id'] =  $assign_data[$i];
			$result[] = $this->insertion($post_data);
		}
		return $result; 
	}
	
	/*
	/* Method insertion
	/* @data
	*/
	public function insertion($data){
		$this->create();
		return $this->save($data);
	}
	
	/*
	/* Method unassign_all_users
	/* @monitor_id
	*/
	public function unassign_all_users($monitor_id){
		return $this->deleteAll(array('AssignMonitor.monitor_id' => $monitor_id), false);
	}
	
	/*
	/* get_assigned_user
	/* @monitor_id
	*/
	public function get_assigned_user($monitor_id){
		$result_data = $this->find('all',array('conditions'=>array('AssignMonitor.monitor_id'=>$monitor_id)));
		return $result_data;
	}
	
	/*
	/* get_selected_user 
	/* @monitor_id
	*/
	public function get_selected_user($monitor_id){
		$result = array();
		$result_data = $this->get_assigned_user($monitor_id);
		for($i=0;$i<count($result_data);$i++){
			$result[] = $result_data[$i]['AssignMonitor']['user_id'];
		}
		return $result;
	}
	
}
