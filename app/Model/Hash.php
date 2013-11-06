<?php
App::uses('AppModel', 'Model');
/**
 * Hash Model
 *
 */
class Hash extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'hash' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'The user name must be Unique.'=>array(
				'rule'=>array('isUnique'),
				'message'=>'This Username already taken Please use some other name.'
			),
		),
	);
	
	public function set_hash($hash){
		# SET HASH 
		$this->set('hash',$hash);
		
		# VALIDATE THE DATA AND SAVE THE DATA
		if($this->validates()){
			$this->save();
			return $this->getInsertID();
		}
		return false;
	}
	
	/* REMOVE HASH FROM DATABASE */
	public function unset_hash($hash){
		return $this->deleteAll(array('hash' => $hash));
	}
	
	/* GET HASH FROM DATABASE */
	public function get_hash(){
		$conditions = array ("Hash.timestamp <" =>date('Y-m-d h:i:s', strtotime("-1 day")));
		return $this->find('all', array('conditions' => $conditions));
	}
	
	/* REMOVE DIRECTORY WITH FILES */
	function rrmdir($dir) {
		foreach(glob($dir . '/*') as $file) {
			if(is_dir($file))
				rrmdir($file);
			else
				unlink($file);
		}
		rmdir($dir);
	}	

}
