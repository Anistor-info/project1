<?php
App::uses('AppModel', 'Model');
/**
 * Company Model
 *
 * @property Country $Country
 * @property State $State
 */
class Company extends AppModel {
/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'company_id';
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
		),
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $hasMany = array(
        'User' => array(
            'className'     => 'User',
            'foreignKey'    => 'company_id',
			'condition' => array('User.role_id = 2')
        )
    );
	
	public $validate = array(
		'company_name'=>array(
			'Please enter your Company name'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter your Company name.'
			),		
			'The company_name must be between 5 and 50 characters.'=>array(
				'rule'=>array('between', 5, 50),
				'message'=>'The Company name must be between 5 and 50 characters.'
			),
			'The company_name must be Unique.'=>array(
				'rule'=>array('isUnique'),
				'message'=>'This Company name already taken Please use some other name.'
			)			
		),
		'address1'=>array(
			"Please enter your Company's address"=>array(
				'rule'=>'notEmpty',
				'message'=>"Please enter your Company's address."
			)		
		),
		'contact_number'=>array(
			'Not empty'=>array(
				'rule'=>array('notEmpty'),
				'message'=>"Please enter your Company's contact number."
			),
			'company contact number'=>array(
				'rule'=>'isNumeric',
				'message'=>'Please Enter numbers only'
			),			
			'contact number'=>array(
				'rule'=>array('between', 10, 12),
				'message'=>'Please Enter numbers between 10 to 12'
			)
		),
		'monitors'=>array(
			'Please enter your Company name'=>array(
				'rule'=>'notEmpty',
				'message'=>'Monitors should be assigned to every monitor'
			),
		),
		'state_id'=>array(
			'Please Select state'=>array(
				'rule'=>array('comparison', '>', 0),
				'message'=>'Please Select state'
			),
		)
	);	
	

	//check for numbers if not number then set error message
	public function isNumeric($data){
		if(is_numeric($this->data['Company']['contact_number'])){
			return true;
		}
	}	
	
	//get all countries detail
	public function get_country_info(){
		$country_data = $this->query('select country_id,country_name from countries order by country_name');	

		if(count($country_data)>0){
			$countries_data = array();	
			$countries_data[0] = 'Select Country';
			//prepairing data into array according to requirement
			for($i=0;$i<count($country_data);$i++){
				$countries_data[$country_data[$i]['countries']['country_id']] = $country_data[$i]['countries']['country_name'];				
			}
			return $countries_data;			
		}
	}		
	
	//get all states detail	
	public function get_state_info(){
		$state_data = $this->query('select state_id,state_name from states');	
		if(count($state_data)>0){
			$states_data = array();		

			//prepairing data into array according to requirement				
			for($i=0;$i<count($state_data);$i++){
				$states_data[$state_data[$i]['states']['state_id']] = $state_data[$i]['states']['state_name'];				
			}
			return $states_data;			
		}
	}		
	
	public function get_all_companies($current_page,$limit){
		$start_limit =  $current_page * $limit;
	    $end_limit = $limit;
		$company_super_admin_data = $this->query('SELECT `Company`.`company_id`, `Company`.`company_name`, `Company`.`address1`, `Company`.`address2`, `Company`.`contact_number`,`User`.`user_id`,`User`.`company_id`,`User`.`role_id` FROM `outbackmediaserver`.`companies` AS `Company` LEFT JOIN `outbackmediaserver`.`users` AS `User` ON (`Company`.`company_id` = `User`.`company_id` and `User`.`role_id`='.COMPANY_SUPER_ADMIN.') limit '.$start_limit.' , '.$end_limit.' 
');	
	return $company_super_admin_data;
	}
	
	public function delUsers($id){		
		$erase_data = $this->query('delete from users where company_id='.$id);		
	}
	
	public function get_data($company_id){
		return $this->find('first', array('conditions' => array('Company.company_id' => $company_id)));
	}
}
