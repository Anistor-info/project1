<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property RequestHandlerComponent $RequestHandler
 */

//for using email functionality of cake
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {

	/**
	 * Helpers
	 *
	 * @var array
	 */
	//	public $helpers = array('Ajax', 'Javascript', 'Time');
	/**
	 * Components
	 *
	 * @var array
	 */
	//	public $components = array('RequestHandler');
	/**
	 * index method
	 *
	 * @return void
	 */

	public $paginate = array(
        'limit' => DEFAULT_PAGING_LIMIT
	);

	public function index() {
			
		# check wheather user logined
		$this->check_logined();
				
		# get some data from session
		$logined_user_role_id = $this->MySession->get_current_user_role();
		$logined_user_company_id = $this->MySession->get_current_user_company();
		$logined_user_id = $this->MySession->get_logined_userid();
		$users = array();
		        
		# differentiate to admin form company admin 
		if(($logined_user_role_id != WEBMASTER)){			
			$this->User->recursive = 0;
			$option = array('User.company_id' => $logined_user_company_id,'User.user_name != ' => 'NULL','User.role_id != ' => COMPANY_SUPER_ADMIN,'User.user_id != ' => $logined_user_id);
		    $this->set('users', $this->paginate($option));
			$this->set('role_id',$logined_user_role_id);
		}else if($logined_user_role_id == WEBMASTER){
			$this->User->recursive = 0;
			$option = array('User.user_id != ' => $logined_user_id);
		    $this->set('users',$this->paginate($option));
		}
		
		//$this->set('users',$users);
	}

	/**
	 * view method
	 *
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add($company_id=NULL,$role_id=NULL) {
					
		# CHECK USER LOGINED
		$this->check_logined();
		
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
				
		# GET AND SET SOME DATA 
		$logined_user_role = $this->MySession->get_current_user_role();
		$logined_user_company = $this->MySession->get_current_user_company();
		$logined_user_id = $this->MySession->get_logined_userid();
		$this->set('company_id',$company_id);
		$this->set('super_admin',false);
		$error = 1;
        		
		# CREATING SUPER ADMIN ADDING USER 
		if(!empty($company_id) and $role_id == COMPANY_SUPER_ADMIN){
			$this->set('add_super_admin',true);
		}else{
			$this->set('add_super_admin',false);	
		}
		
		# CREATING COMPANY USER
		if($logined_user_role == COMPANY_ADMIN){
		  	$this->set('add_company_user',true);
		}else{
			$this->set('add_company_user',false);	
		}
		
		# ROLE ASSIGNING 
		if($role_id and ($logined_user_role == WEBMASTER)){
			$this->set('role_id',$role_id);
		}else if($logined_user_role == COMPANY_SUPER_ADMIN){
			$this->set('role_id',array('options'=>array(COMPANY_ADMIN=>'admin',COMPANY_USER=>'user')));
		}else{
			$this->set('role_id',COMPANY_USER);
		}
		
		# GET ALL SYSTEM ROLES
		if($logined_user_role){		
			$this->set('user_roles',$this->User->get_all_roles($logined_user_role));
		}
		
		# WEBMASTER GETTING ALL COMPANIES
		if($role_id == COMPANY_SUPER_ADMIN){
			$this->set('company_names',$this->User->get_all_companies());
		}else{
			$this->set('company_id',$logined_user_company); // current user company id
		}
		
		# IF DATA POSTED
		if ($this->request->is('post'))
		{
			# POST DATA INTO THE OBJECT
			$this->User->set($this->data);
			$company_id = isset($this->data['User']['company_id']) ? $this->data['User']['company_id'] : 0;
            
			if(!$logined_user_company and !$company_id){			
				$this->Session->setFlash(__('The user should have one company','flash_success'));	
				$error = 0;
			}
			   
			# VALIDATES THE DATA
			if($this->User->validates() and $error){
				
				# GET PRIVILAGE FROM POSTED DATA
				$privileges_request = isset($this->data['privilege']) ? $this->data['privilege'] : array();
				
				# PREPARE USER DATA FOR INSERTION
				$this->User->create();
				$token = $this->User->encrypt_password(time());
				$submitted_data = $this->request->data;
				$submitted_data['User']['token'] = $token;
				$submitted_data['User']['created_by'] = $logined_user_id;
				
				# ADD MONITOR
				if( ($submitted_data['User']['role_id'] == COMPANY_SUPER_ADMIN) or ($submitted_data['User']['role_id'] == COMPANY_ADMIN) ){
					$privileges_request[] = ADD_MONITOR; 
				}
															
				# INSERTION OPERATION
				if ($this->User->save($submitted_data)) {
					$this->User->set($submitted_data);
					$last_insert_id = $this->User->getLastInsertId();
					Controller::loadModel('UserPrivilege');
					$this->UserPrivilege->save_privilages($submitted_data['User']['role_id'],$privileges_request,$last_insert_id);
					
					# SEND INVITAION THROUGH EMAIL 
					$invitation_data = $this->User->get_invitation_data($last_insert_id);
					$subject = 'Join us';
					$to = $submitted_data['User']['email'];
					$this->User->send_mail($to,$subject,$invitation_data);
					$this->Session->setFlash('User added successfully.');
					$this->redirect(array('controller'=>'users','action' => 'index'));
				} else {	
					$this->Session->setFlash('User could not be added. Please, try again.','flash_failure');
					$this->redirect(array('controller'=>'users','action' => 'index'));
				}
			}else{
				$this->Session->setFlash('User could not be added. Please, try again.','flash_failure');
			}
		}
		
		# GET SOME DATA FROM DATABASE
		$roles = $this->User->Role->find('list');
		$companies = $this->User->Company->find('list');
		$this->set(compact('roles', 'companies'));
	}

	/**
	 * edit method
	 *
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		
			
		//check the user authenticated or not
		$this->check_logined();
		
		// SAVE PATH INTO SESSION		
		$query_string = '';
		$query_string = implode('/',$this->params['pass']);
		$url = Router::url('/', true).$this->params['controller'].'/'.$this->params['action'].'/'.$query_string;
		$this->MySession->set_last_url($url);
		
		# get some data from session
		$role = $this->MySession->get_current_user_role();
		$company_id = $this->MySession->get_current_user_company();
		$user_id = $this->MySession->get_logined_userid();
		$user_data = $this->User->get_company_user_ids($company_id);

		// check wheather valid user
		if(!in_array($id,$user_data) and ($role != 1)){
			$this->Session->setFlash(__('Invalid user'));
			$this->redirect(array('controller'=>'Users','action' => 'index'));
		}

		// if user goes to edit own profile
		if($id == $user_id){
			$this->redirect(array('controller'=>'Users','action' => 'profile'));
		}

		if(!$id){
			$this->redirect(array('controller'=>'Users','action' => 'index'));
		}
		// get all profile
		$this->set('user_roles',$this->User->get_all_roles($role));

		// set some id for view
		$this->User->id = $id;
		$this->set('edit_user_id',$id);
		$this->set('login_user_id',$user_id);

		if ($this->request->is('post') || $this->request->is('put')) {

			//set the submitted data to the model			
			$this->User->set($this->data);
			$this->User->check_email_exists($id);

			//check validations
			if($this->User->validates()){
				// load model
				Controller::loadModel('UserPrivilege');
				$conditions = array('UserPrivilege.user_id' => $id);

				// delete all privilages
				$this->UserPrivilege->deleteAll($conditions, false);
				$privileges_request = isset($this->data['privilege']) ? $this->data['privilege'] : 0;

				// create new privilages
				for ($i=0;$i<count($privileges_request);$i++) {
					$this->UserPrivilege->create();
					$this->UserPrivilege->save(array('UserPrivilege' => array('user_id' => $id,'pid' => $privileges_request[$i])));
				}

				// update user
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash('The user has been Update','flash_success');
					$this->redirect(array('controller'=>'Users','action' => 'index'));
				} else {
					$this->Session->setFlash('The user could not be updated. Please, try again.','flash_failure');
				}
			}else{
				$this->Session->setFlash('The user could not be updated. Please, try again.','flash_failure');
			}
		} else {
			# get data from database
			$user_result_data = $this->User->read(null, $id);
			$this->request->data = $user_result_data;
			$this->set('role_id',$user_result_data['User']['role_id']);
		}
		
		
		# GET SOME DATA FROM DATABASE AND PASS IT TO VIEW
		
		$roles = $this->User->Role->find('list');
		$companies = $this->User->Company->find('list');
		$this->set(compact('roles', 'companies'));
	}

	public function profile(){
		
		//check wheather user logined
		$this->check_logined();
		
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
		
		// get current user id 
		$user_id = $this->MySession->get_logined_userid();

		if ($this->request->is('post')) {
			
			// get user data
			$post_data = $this->request->data;
			$this->User->set($post_data);

			// check is it users own exist
			$this->User->check_email_exists($user_id);

			// validates the data
			if($this->User->validates()){
				$post_data['User']['user_id'] = $user_id;
				if ($this->User->save($post_data)) {
					$this->Session->setFlash('The user has been Update','flash_success');
					$this->redirect(array('controller'=>'Users','action' => 'profile'));
				} else {
					$this->Session->setFlash('The user could not be updated. Please, try again.','flash_failure');
				}
			}else{
				$this->Session->setFlash('The user could not be updated. Please, try again.','flash_failure');	
			}
		}

		// get user details
		$user_data = $this->User->get_user_detail($user_id);
		$this->request->data['User']['email'] = $user_data['User']['email'];
		$this->request->data['User']['first_name'] = $user_data['User']['first_name'];
		$this->request->data['User']['last_name'] = $user_data['User']['last_name'];
	}

	public function changePassword(){
					
		// if data has been posted
		if ($this->request->is('post')) {

			// get logined user id
			$user_id = $this->MySession->get_logined_userid();
			$post_data = $this->request->data;

			// set data in user object
			$this->User->set($post_data);
			if($this->User->validates()){
				$password = md5($post_data['User']['old_password']);
				$new_password = $post_data['User']['password'];
				$confirm_password = $post_data['User']['confirm_password'];
				$valid_user_data = $this->User->checkPassword($user_id,$password);

				// password matched with
				if(!empty($valid_user_data)){
					if($new_password == $confirm_password){
						
						# prepare the data for save
						$submited_data['User']['user_id'] = $user_id;
						$submited_data['User']['password'] = $post_data['User']['password'];
						
						# save the data
						$this->User->save($submited_data);
						$this->Session->setFlash('Pasword has been changed.','flash_success');
					}
				}else{
					$this->Session->setFlash('Old Password is not correct.','flash_failure');
				}
			}else{
				    $this->Session->setFlash('Password has not been changed. Try again','flash_failure');
			}
		}
		
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
	}

	/**
	 * delete method
	 *
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {

		//check wheather user logined
		$this->check_logined();
		
		# get data from session
		$company_id = $this->MySession->get_current_user_company();
		$role_id = $this->MySession->get_current_user_role();
		$logined_user_id = $this->MySession->get_logined_userid();
		$company_users = $this->User->get_company_id($id);
		
		# USER WILL NOT DELETE HIS OWN 
		if($logined_user_id == $id){
			$this->Session->setFlash(__('Invalid action'));
			$this->redirect(array('controller'=>'Users','action' => 'index'));
		}
		
		// get user data
		$user_data = $this->User->get_company_user_ids($company_id);
		if(!in_array($id,$user_data) and ($role_id != 1)){
			$this->Session->setFlash(__('Invalid user'));
			$this->redirect(array('controller'=>'Users','action' => 'index'));
		}

		// post data not allowed
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
        		
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted successfully.'));
			$this->redirect(array('controller'=>'Users','action' => 'index'));
		}

		$this->Session->setFlash(__('User could not deleted.'));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Login
	 */
	public function login(){

		//setting the layout of the section/module
		$this->layout ='default';
		$this->User->set($this->data);

		//getting user_info from session for user authentication checking
		$user_data = $this->Session->read('user.data');
		
		$data = $this->Session->read('user');
				
		//if not valid user redirect user back to the login page
		if($user_data){
			$this->redirect(array('action' => 'index'));
		}

		//checking the form is submit
		if($this->request->is('Post')){
			$username = $this->data['User']['username'];
			$password = $this->data['password'];
			if(isset($username) && isset($password)){
					
				//checking the validations
				if($this->User->validates()){

					//changing password to md5 because we are using the md5 passwords in the db
					$password = md5($password);

					//checking user valid or not(exists or not)
					if($this->User->is_valid_user($username)){
						//getting user_info from db
						$valid_user_data = $this->User->get_user_info($username,$password);
						$user_id = isset($valid_user_data['User']['user_id']) ? $valid_user_data['User']['user_id'] : null;

						if($user_id){
							Controller::loadModel('UserPrivilege');
							$privilege = $this->UserPrivilege->get_privilage($user_id);
							$valid_user_data['Privilages'] = $privilege;
						}

						if($valid_user_data){
							//adding user_info into session
							$this->Session->write('user.data',$valid_user_data );
						    
							$last_url = $this->Session->read('last');
							$last = $this->Session->read('user.data');
							
							$this->redirect(array('action' => 'home'));
														
							/*if(empty($last_url)){
							    //redirecting to the view section
								$this->redirect(array('action' => 'home'));
							}else{
								$action = $last_url['action'];
								$controller = $last_url['controller'];
								$this->redirect(array('action' => $action,'controller'=>$controller));	
							}*/
						} else{
							//redirect invalid user back to the login page
							$this->Session->setFlash('Invalid username/password','flash_failure');
							$this->redirect(array('action' => 'login'));
						}
					} else{
						//redirect invalid user back to the login page
						$this->Session->setFlash('Invalid username/password','flash_failure');
						$this->redirect(array('action' => 'login'));
					}
				}
			}
		}
	}

	public function logout(){
		$this->Session->delete('user.data');
		$this->redirect(array('action' => 'login'));
	}

	public function home(){
		
		//check the user authenticated or not
		$this->check_logined();
		
		
		# get some data from session
		$role = $this->MySession->get_current_user_role();
		$company_id = $this->MySession->get_current_user_company();

		$this->set('role',$role);
	}

	public function create() {

		//checking the form is submit
		if($this->request->is('Post')){
			$this->User->set($this->request->data);
			
			# VALIDATE THE DATA 
			if($this->User->validates()){
				
				# SAVE THE DATA
				if ($this->User->save($this->request->data)) {
					$data = $this->request->data;
					$id = $data['User']['user_id'];
					$username = $data['User']['user_name'];
					$password = $data['User']['user_password'];
					$token = $data['User']['token'];
					$this->User->update_token($id,$token);

					//getting user_info from db
					$valid_user_data = $this->User->get_user_info($username,$password);

					if($valid_user_data){
						//adding user_info into session
						$this->Session->write('user.data',$valid_user_data );
					}
					$this->Session->setFlash('The user has been created successfully','flash_success');
					$this->redirect(array('action' => 'login'));
				} else {
					$this->Session->setFlash('The user could not be created. Please, try again.','flash_failure');
				}
			}
		}else{
			$id = isset($this->request->query['id'])?$this->request->query['id']:NULL;
			$this->set('id',$id);
			$token = isset($this->request->query['token'])?$this->request->query['token']:NULL;
			$this->set('token_id',$token);
			$user_info = $this->User->get_user_to_create($id,$token);
			if(isset($user_info['User'])){
				$this->data = $user_info;
			} else {
				$this->Session->setFlash('This Token has been expired or used. Contact with Admin for new token','flash_failure');
				$this->redirect(array('action' => 'login'));
			}
		}
	}

	/*
	 * @forget password
	 */

	public function forgetPassword(){	
		# check is data posted
		if($this->request->is('post')){
			# set data into user object
			$this->User->set($this->data);
			# stop validating unique email
			$this->User->stop_validate_unique_email();
			# validate the email
			if($this->User->validates()){
				if($this->User->send_forget_password_email()){
					$this->Session->setFlash('Mail sent, please check your email','flash_success');
				}else{
					$this->Session->setFlash('Mail not found!','flash_failure');
				}
			}
		}
	}

	public function resetPassword($token = null){
		# if data posted
		if($this->request->is('post')){
			# get values from post
			$token = $this->data['User']['token'];
			$password = $this->data['password'];
			$confirm_password = $this->data['confirm_password'];
			# set values user object
			$this->User->set('password',$password);
			$this->User->set('confirm_password',$confirm_password);
			# if token exist and password matched with confirm password
			if($this->User->tokenExist($token) and $this->User->validates()){
				# encrypt password
				$password = $this->User->encrypt_password($password);
				# reset the password
				$this->User->reset_password($token,$password);
				$this->Session->setFlash('Password has been reset, please login','flash_success');
				$this->redirect(array('action' => 'login'));
				exit;
			}else{
				# both password not matched
				$this->Session->setFlash('Passwords not matched!','flash_failure');
			}
		}
		# if token exist
		if($this->User->tokenExist($token)){
			# pass token for view
			$this->set('token',$token);
		}else{
			# redirect to forget password if token not exist
			$this->Session->setFlash('Token has been already used!','flash_failure');
			$this->redirect(array('action' => 'forgetPassword'));
			exit;
		}
	}
	
	public function listUser() {
			
		# check wheather user logined
		$this->check_logined();
		
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
		
		# get some data from session
		$logined_user_role_id = $this->MySession->get_current_user_role();
		$logined_user_company_id = $this->MySession->get_current_user_company();
		$logined_user_id = $this->MySession->get_logined_userid();
		$users = array();
		        
		# differentiate to admin form company admin 
		if(($logined_user_role_id != WEBMASTER)){			
			$this->User->recursive = 0;
			$option = array('User.company_id' => $logined_user_company_id,'User.user_name != ' => 'NULL','User.role_id != ' => COMPANY_SUPER_ADMIN,'User.user_id != ' => $logined_user_id);
		    $this->set('users', $this->paginate($option));
			$this->set('role_id',$logined_user_role_id);
		}else if($logined_user_role_id == WEBMASTER){
			$this->User->recursive = 0;
			$option = array('User.user_id != ' => $logined_user_id);
		    $this->set('users',$this->paginate($option));
		}
		//$this->set('users',$users);
	}
	
	public function welcome(){
		
	}
}
