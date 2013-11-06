<?php
App::uses('AppModel', 'Model');
/**
 * Privilege Model
 *
 */
class Privilege extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'privilege' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	var $helper=array('Session');
	
	/* CHECK WHEATHER CURRENT USER HAS ACCESS */
	public function has_access($controller,$action){
		
		# GET THE DATA FROM SESSION		
		$user_data = SessionHelper::read('user.data');	
		
		# GET PRIVILAGES FROM SESSION
		$privilages = explode(',',$user_data['Privilages']);
		$user_id = $user_data['User']['user_id'];
		$role_id = $user_data['User']['role_id'];
		
		# GET ALL PRIVILAGES FROM DATABASE
		$privilage_array = $this->find('all');
		
		# CREATE ONE DYNAMIC ARRAY FOR CHECK PERMISSIONS
		for($i=0;$i<count($privilage_array);$i++){
			$data[$privilage_array[$i]['Privilege']['controller']][$privilage_array[$i]['Privilege']['action']] = $privilage_array[$i]['Privilege']['id'];
		}
		
		# GET THE CURRENT ACTION AND CHECK WHEATHER USER HAS ACCESS OF THAT									
		$current_action = isset($data[$controller][$action]) ? $data[$controller][$action] : false;	
		if($current_action){
			if((!in_array($current_action,$privilages))){
			return false;
			}
		}
		return true;
	}
}
