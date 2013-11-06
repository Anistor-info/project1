<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Role $Role
 * @property Company $Company
 */
class User extends AppModel {
	/**
	 * Primary key field
	 *
	 * @var string
	 */
	public $primaryKey = 'user_id';
	/**
	 * Validation rules
	 *
	 * @var array
	 */
	var $validate = array(
		'username'=>array(
		'username_must_not_be_blank'=>array(
			'rule'=>'notEmpty',
			'message'=>'Please Enter the Username'),
	),
		'user_name'=>array(
			'Please enter User name'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter User name.'
				),
			'The username must be between 3 and 50 characters.'=>array(
				'rule'=>array('between', 3, 50),
				'message'=>'The username must be between 3 and 50 characters.'
				),
			'The user name must be Unique.'=>array(
				'rule'=>array('isUnique'),
				'message'=>'This Username already taken Please use some other name.'
				)
				),
	'email'=>array(
			'Please Enter email Id'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter Email.'
				),
			'Please valid email'=>array(
				'rule'=>'email',
				'message'=>'Please enter Valid Email.'
				),
			'Please unique email'=>array(
				'rule' => array('isUnique'),
				'message'=>'Email already exist.'
				)
				),
	'old_password'=>array(
			'Please enter password'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter password.'
				),
			'The password must be between 6 and 50 characters.'=>array(
				'rule'=>array('between', 6, 50),
				'message'=>'The password must be between 6 and 50 characters.'
				)
				),
	'password'=>array(
			'Please enter password'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter password.'
				),
			'The password must be between 6 and 50 characters.'=>array(
				'rule'=>array('between', 6, 50),
				'message'=>'The password must be between 6 and 50 characters.'
				)
				),
	'confirm_password'=>array(
			'Please enter confirm password'=>array(
			    'type'=>'password',
				'rule'=>'notEmpty',
				'message'=>'Please enter confirm password.'
				),
			'The password must match'=>array(
				'rule'=>array('matchPasswords'),
				'message'=>'The password must match confirm password.'
				)
				),
	'first_name'=>array(
			'Please enter first_name'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter First name.'
				)
				),
	'last_name'=>array(
			'Please enter last_name'=>array(
				'rule'=>'notEmpty',
				'message'=>'Please enter Last name.'
				)
				)

				);

				public function matchPasswords(){
					$data  = $this->data;
					if($data['User']['password']==$data['User']['confirm_password']){
						return true;
					}
					return false;
				}

				function beforeSave() {
					if (isset($this->data['User']['password'])) {
						$this->data['User']['password'] = md5($this->data['User']['password']);
					}
					return true;
				}

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * belongsTo associations
		 *
		 * @var array
		 */
		public $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
			)
			);
				
			/**
			 * 	CHECK WHEATHER THE NAME IS VALID
			 **/
			public function is_valid_user($username){
				$user_detail = $this->query("select user_name from users where user_name='".$username."' and token=0");
				if(count($user_detail)>0){
					return true;
				}
				return false;

			}
				
			/**
			 * 	GET INFORMATION CORRESPONDING TO USERNAME AND PASSWORD
			 **/
			public function get_user_info($username,$password){
				$user_info = $this->find('first', array('conditions' => array($this->name.'.user_name' => $username,$this->name.'.password' => $password ,$this->name.'.token' => '0')));
				return $user_info;
			}
				
			/**
			 * 	GET ALL USER FROM DATABASE
			 **/
			public function get_all_users(){
				$all_user_info = $this->find('all');
				return $all_user_info;
			}
				
			/**
			 * 	GET ALL ROLES
			 **/
			public function get_all_roles($role){
				$all_user_roles = $this->query('select role_id,role_name from roles where role_id >'.$role);
				$user_roles=array();
				if(count($all_user_roles)>0){
					for($i=0;$i<count($all_user_roles);$i++){
						$user_roles[$all_user_roles[$i]['roles']['role_id']] = $all_user_roles[$i]['roles']['role_name'];
					}
				}
				return $user_roles;
			}
				
			/**
			 * 	GET ALL COMPANIES
			 **/
			public function get_all_companies(){
				$get_all_companies = $this->query("select company_id,company_name from companies");
				$get_companies=array();
				if(count($get_all_companies)>0){
					for($i=0;$i<count($get_all_companies);$i++){
						$get_companies[$get_all_companies[$i]['companies']['company_id']] = $get_all_companies[$i]['companies']['company_name'];
					}
				}
				return $get_companies;
			}
				
			/**
			 * 	GET ALL THE USERS CORESPONDING TO PARTICULAR COMPANY
			 **/
			public function get_company_users($company_id)	{
				//$all_user_info = $this->query("SELECT * FROM `users` WHERE `company_id` = ".$company_id);
				$all_user_info = $this->find('all', array(
        'conditions' => array('User.company_id' => $company_id,'User.user_name != ' => 'NULL','User.role_id != ' => COMPANY_SUPER_ADMIN)
				));

				return $all_user_info;
			}
				
			/**
			 * 	GET ALL USER IDS THOSE EXIST IN COMPANY
			 **/
			public function get_company_user_ids($company_id){
				$arr = array();
				//$all_user_info = $this->query("SELECT * FROM `users` WHERE `company_id` = ".$company_id);
				$result_data = $this->get_company_users($company_id);

				for($i=0;$i<count($result_data);$i++){
					$arr[] = $result_data[$i]['User']['user_id'];
				}
				return $arr;
			}
				
			/**
			 * 	GET CORRESPONDING COMPANY TO THE USER
			 **/
			public function get_company_id($user_id)	{
				//$all_user_info = $this->query("SELECT * FROM `users` WHERE `company_id` = ".$company_id);
				$all_user_info = $this->find('all', array(
        'conditions' => array('User.user_id' => $user_id)
				));
				return $all_user_info;
			}
				
			/**
			 * 	GET INVITATION DATA
			 **/
			public function get_invitation_data($last_insert_id){
				$data = $this->data;
				$message = $data['User']['welcome_message'];
				$company_id =  $data['User']['company_id'];
				$get_company_name = $this->query("select company_name from companies where company_id = ".$company_id);
				$company_name = $get_company_name[0]['companies']['company_name'];
				$token = $data['User']['token'];
				$url = Router::url('/', true);
				$company_link = $company_name;
				$link_url = $url.'users/create?id='.$last_insert_id.'&token='.$token;
				$link = '<a href="'.$link_url.'">Click here to register</a>';
				$new_message = str_replace('#company_name#', $company_link, $message);
				$new_message = str_replace('#link#', $link, $new_message);
				return $new_message;
			}

			/*
			 * get forget mail content
			 */
			public function get_forget_mail_content($token){
				$url = Router::url('/', true);
				$link_url = $url.'users/resetPassword/'.$token;
				$new_message = '<a href="'.$link_url.'">Click here</a> to reset your password.';
				return $new_message;
			}
				
			/**
			 * 	SEND EMAIL FUNCTION
			 **/
			public function send_mail($to,$subject,$message){
				$email = new CakeEmail();
				$from = ADMIN_MAIL;
				$email->emailFormat('html');
				$email->from($from);
				if(empty($to)){
					$to = ADMIN_MAIL;
				}
				$email->to($to);
				$email->subject($subject);
				return $email->send($message);
			}
				
			/**
			 * 	GET INFORMATION CORRESPONDING TO USER ID AND TOKEN
			 **/
			public function get_user_to_create($id,$token){
				$user_info = $this->find('first', array('conditions' => array($this->name.'.user_id' => $id,$this->name.'.token'=>$token)));
				return $user_info;
			}
				
			/**
			 * 	CHECK PASSWORD
			 **/
			public function checkPassword($user_id,$password){
				return $this->find('first', array('conditions' => array($this->name.'.user_id' => $user_id,$this->name.'.password'=>$password)));
			}
				
			/**
			 * 	UPDATE THE TOKEN CORRESPONDING TO USER ID
			 **/
			public function update_token($id,$token){
				if($this->query("update users set users.token='0' where users.user_id='".$id."' and users.token='".$token."'")){
					return true;
				}else{
					return false;
				}
			}
				
			/**
			 * 	GET INFORMATION CORRESPONDING TO USER ID
			 **/
			public function get_user_detail($user_id){
				return $this->find('first', array('conditions' => array($this->name.'.user_id' => $user_id)));
			}
				
			/**
			 * 	CHECK WHEATHER EMAIL EXIST
			 **/
			public function check_email_exists($id){

				$db_data = $this->get_user_detail($id);
				$db_email = $db_data['User']['email'];
				$post_email = $this->data['User']['email'];
					
				if($db_email == $post_email){
				 $this->stop_validate_unique_email(); // Stop valadation on the unique email
				}
			}

			/*
			 * @ for remove validation of unique email
			 */

			public function stop_validate_unique_email(){
				unset($this->validate['email']['Please unique email']); // Stop valadation on the unique email
			}

			/*
			 * send forget password mail
			 */
			public function send_forget_password_email(){
				# email exist or not
				if($this->check_email_exist()){
					$to = $this->data['User']['email'];
					# generate token
					$token = md5(time());
					# create subject
					$subject = 'reset password';
					# get message
					$message = $this->get_forget_mail_content($token);
					# add token in database
					$this->add_reset_password_token($token);
					return $this->send_mail($to, $subject, $message);
				}else{
					return false;
				}
			}

			/*
			 * check wheater email exist
			 */
			public function check_email_exist(){
				$email = $this->data['User']['email'];
				return $this->find('first', array('conditions' => array($this->name.'.email' => $email)));
			}
			/*
			 *  add reset reset password token
			 */
				
			public function add_reset_password_token($token){
				# get email
				$email = $this->data['User']['email'];
				$this->updateAll(array('User.reset_password_token' => '"'.$token.'"'), array('User.email' => $email));
			}
				
			/*
			 * check wheather token exist
			 */
			public function tokenExist($token){
				return $this->find('first', array('conditions' => array($this->name.'.reset_password_token' => $token)));
			}
			/*
			 * reset the password
			 */
			public function reset_password($token,$password){
				return $this->updateAll(array('User.password' => "'".$password."'",'User.reset_password_token' => 0), array('User.reset_password_token' =>$token));
			}

			/*
			 * encrypt password
			 */
			public function encrypt_password($password){
				return md5($password);
			}
				
			/*
			 /* Method Get Created user
			 /* @user_id
			 */
			public function get_created_user($user_id){
				$all_user_info = $this->find('all', array('conditions' => array('User.created_by' => $user_id,'User.token'=>0)
				));
				return $all_user_info;
			}
			
			/*
			/* Method get_company_simple_users
			/* @company id
			*/
			public function get_company_simple_users($company_id){
				//$all_user_info = $this->query("SELECT * FROM `users` WHERE `company_id` = ".$company_id);
				$all_user_info = $this->find('all', array(
        'conditions' => array('User.company_id' => $company_id,'User.user_name != ' => 'NULL',array("NOT" => array("User.role_id" => array(WEBMASTER,COMPANY_SUPER_ADMIN,COMPANY_ADMIN))))));

				return $all_user_info;
			}
				
			public function parse_user_and_name($array_data){
				$data = array();
				for($i=0;$i<count($array_data);$i++){
					$data[$array_data[$i]['User']['user_id']] = $array_data[$i]['User']['user_name'];
				}
				return $data;
			}

}
