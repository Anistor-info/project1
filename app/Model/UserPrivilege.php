<?php
App::uses('AppModel', 'Model');
/**
 * UserPrivilege Model
 *
 * @property User $User
 */
class UserPrivilege extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	* 	GET PRIVILAGES CORRESPONDING TO USER ID
	**/
	public function get_privilage($user_id){
		 $arr = array();
		 //$result_array = $this->find('all', array('conditions' => array('UserPrivilage.user_id' => $user_id)));
		 
		 $query = "select pid from user_privileges where user_id = $user_id union select pid from privilege_sets where set_id in(select pid from user_privileges where user_id = $user_id)";
		 $result_array = $this->query($query);
		 		 
		 for($i=0;$i<count($result_array);$i++){
			$arr[] = $result_array[$i][0]['pid']; 
		 }
		 
		 $fh = implode(',',$arr);
		 return $fh;
	}
	
	/**
	* 	SAVE THE PRIVILAGES
	**/
	public function save_privilages($role,$privileges_request,$user_id){
		
		# if user company admin going to add
		if($role == COMPANY_SUPER_ADMIN){
			$privileges = array(ALL_ACCESS_USER);
			
			# add permission one by one
			for ($i=0;$i<count($privileges);$i++) {
				$this->create();
				$this->save(array('UserPrivilege' => array('user_id' => $user_id,'pid' => $privileges[$i])));
			}
		}
		
		# if user company user going to add
		if($role == COMPANY_ADMIN){
			
			$privileges = array(ALL_ACCESS_USER);
			$all_privilages = array(ADD_USER,EDIT_USER,DELETE_USER);
									
			# if user have all access
			if(!in_array(ALL_ACCESS_USER,$privileges_request) and (count($privileges_request) !=  count($all_privilages))){
				for ($i=0;$i<count($privileges_request);$i++) {
					$this->create();
					$this->save(array('UserPrivilege' => array('user_id' => $user_id,'pid' => $privileges_request[$i])));
				}
			}else{
				$this->create();
				$this->save(array('UserPrivilege' => array('user_id' => $user_id,'pid' => ALL_ACCESS_USER)));
			}
		}
	}
	
}
