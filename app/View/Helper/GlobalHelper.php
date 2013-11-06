<?php
App::uses('AppController', 'Controller');

/*
* Global Helper
*/

class GlobalHelper extends AppHelper {
	var $helpers = array('Session','Html');
	
	/*
	* Method has_acess
	*
	* @var controller
	* @var action
	*/
	public function has_access($controller,$action){     
		Controller::loadModel('Privilege');
		return $this->Privilege->has_access($controller,$action);
	}
	
	/*
	*
	* Method get_privilage
	* @var user_id
	*/
	public function get_privilage($user_id){
		Controller::loadModel('UserPrivilege');
		return explode(',',$this->UserPrivilege->get_privilage($user_id));	
	}
	
	/*
	* 
	* Method is_logined check wheather user has been logined
	*/
	public function is_logined(){
		$login_user = $this->get_session_data();
	    return isset($login_user['User']['user_id']) ? $login_user['User']['user_id'] : null;
	}
	
	/**
	* Method get_all_session_data
	*/
	public function get_session_data(){
		return $this->Session->read('user.data');
	}
	
	/**
	* Method get_current_role_id
	*/
	public function get_current_role_id(){
		$user_data = $this->get_session_data();
	    return isset($user_data['User']['role_id']) ? $user_data['User']['role_id'] : null;
	}
	
	/**
	* Method get_current_role_name
	*/
	public function get_current_role_name(){
		$user_data = $this->get_session_data();
	    return isset($user_data['Role']['role_name']) ? $user_data['Role']['role_name'] : null;
	}
	
	/**
	* Method get_current_role_name
	*/
	public function get_current_username(){
		$user_data = $this->get_session_data();
		return isset($user_data['User']['user_name']) ? $user_data['User']['user_name'] : null;
	}
	
	/**
	* Method get_current_company_id
	*/
	public function get_current_company_id(){
		$user_data = $this->get_session_data();
		return isset($user_data['User']['company_id']) ? $user_data['User']['company_id'] : null;
	}
	
	/**
	* Method get_monitor_list
	*/
	public function get_monitor_list(){
		Controller::loadModel('Monitor');
		$company_id = $this->get_current_company_id();
		$user_id = $this->is_logined();
		
		# ADMIN CAN SEE ALL THE MONITORS
		if($this->get_current_role_id() == COMPANY_SUPER_ADMIN){
			$result_data = $this->Monitor->get_all_monitors($company_id);	
		}else{
			$result_data = $this->Monitor->get_users_all_monitors($user_id);
		}
		
		# LIMIT THE MONITOR LIST	
		if(count($result_data) > MAX_ACCORDIAN_LIMIT){
			$loop_count = MAX_ACCORDIAN_LIMIT;
		}else{
			$loop_count = count($result_data);
		}
		
		# SHOW THE MONITOR LIST
		$html = '';
		for($i=0;$i<$loop_count;$i++){
			if($this->get_current_role_id() == COMPANY_SUPER_ADMIN){
				$monitor = $result_data[$i]['Monitor'];
			}else{
				$monitor = $result_data[$i]['monitors'];
			}
			$html .= '<li>'.$this->Html->link($monitor['name'],array('controller' => 'videos', 'action'=>'listVideo',0,$monitor['id'])).'</li>';	
		}
		return $html;
	}
	
	/*
	* Method get_last_url
	* 
	*/
	public function get_last_url(){
		$user_data = $this->get_session_data();	
		return isset($user_data['User']['last_url']) ? $user_data['User']['last_url'] : Router::url('/', true).'users/welcome';
	}
	
	/*
	* Method set_last_url
	* @var url
	*/
	public function set_last_url($url){
		$user_data = $this->get_session_data();
		echo $user_data['User']['last_url'] = $url;
		//SessionComponent::write('user.data', $user_data); 
	}
	
	
	public function get_bredcrum($page){
		
		$current = '';
		$html = '<div class="bread_wrapper"><ul class="breadcrumbs">
		<li><a href="'.Router::Url("/",true).'users/welcome">'.$this->Html->image("icon-home.png",array("align"=>"top")).'</a></li>';
		
		if($page == 'welcome'){
			$html .='<li><a href="'.Router::Url("/",true).'users/changePassword">Change Password</a></li>';
			if($this->has_access('users','add')){
				$html .= '<li><a href="'.Router::Url("/",true).'users/add">Add User</a></li>';
			}
			$html .='<li><a href="'.Router::Url("/",true).'users/profile">Edit Profile</a></li>';
		}else if($page == 'change_password'){
			if($this->has_access('users','add') and ($this->get_current_role_id() != WEBMASTER) ){
				$html .= '<li><a href="'.Router::Url("/",true).'users/add">Add User</a></li>';
			}
			$html .='<li><a href="'.Router::Url("/",true).'users/profile">Edit Profile</a></li>';
		}else if($page == 'profile'){
			if( $this->has_access('users','add') and ($this->get_current_role_id() != WEBMASTER) ){
				$html .= '<li><a href="'.Router::Url("/",true).'users/add">Add User</a></li>';
			}
			$html .='<li><a href="'.Router::Url("/",true).'users/changePassword">Change Password</a></li>';
		}else if($page == 'add_user'){
			$html .='<li><a href="'.Router::Url("/",true).'users/profile">Edit Profile</a></li>';
			$html .='<li><a href="'.Router::Url("/",true).'users/changePassword">Change Password</a></li>';
		}else if($page == 'add_company'){
			if($this->has_access('companies','index') and ($this->get_current_role_id() == WEBMASTER) ){
				$html .='<li><a href="'.Router::Url("/",true).'companies">List Company</a></li>';
			}
		}else if($page == 'list_company'){
			if($this->has_access('companies','add') and ($this->get_current_role_id() == WEBMASTER) ){
				$html .='<li><a href="'.Router::Url("/",true).'companies/add">Add Company</a></li>';
			}
		}else if($page == 'edit_company'){
			if($this->has_access('companies','index') and ($this->get_current_role_id() == WEBMASTER) ){
				$html .='<li><a href="'.Router::Url("/",true).'companies">List Company</a></li>';
			}
			if($this->has_access('companies','add') and ($this->get_current_role_id() == WEBMASTER) ){
				$html .='<li><a href="'.Router::Url("/",true).'companies/add">Add Company</a></li>';
			}
			$current = $this->data['Company']['company_name'];
		}else if($page == 'user_list'){
			$html .='<li><a href="'.Router::Url("/",true).'users/profile">Edit Profile</a></li>';
			$html .='<li><a href="'.Router::Url("/",true).'users/changePassword">Change Password</a></li>';
			if($this->has_access('users','add') and ($this->get_current_role_id() != WEBMASTER) ){
				$html .='<li><a href="'.Router::Url("/",true).'users/add">Add User</a></li>';
			}
		}else if($page == 'user_edit'){
			$html .='<li><a href="'.Router::Url("/",true).'users/profile">Edit Profile</a></li>';
			$html .='<li><a href="'.Router::Url("/",true).'users/changePassword">Change Password</a></li>';
			$html .='<li><a href="'.Router::Url("/",true).'users/listUser">List User</a></li>';
			$current = $this->data['User']['first_name'];
		}else if($page == 'list_monitor'){
			if($this->has_access('monitors','add') or ($this->get_current_role_id() == COMPANY_SUPER_ADMIN) or ($this->get_current_role_id() == COMPANY_ADMIN)){
				$html .='<li><a href="'.Router::Url("/",true).'monitors/add">Add Monitor</a></li>';
			}
			$html .='<li><a href="'.Router::Url("/",true).'users/listUser">List User</a></li>';
		}else if($page == 'add_monitor'){
			$html .='<li><a href="'.Router::Url("/",true).'monitors">List Monitors</a></li>';
		}
		
		if($current){
			$html .= '<li class="active">'.ucfirst(str_replace('_',' ',$current)).'</li>';	
		}else{
			$html .= '<li class="active">'.ucfirst(str_replace('_',' ',$page)).'</li>';	
		}
		
		$html .= '</ul></div>';
		return $html;
	}
	
}
