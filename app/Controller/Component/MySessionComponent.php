<?php
Class MySessionComponent extends Component{
	var $name = "MySession";
   public $components = array('Session');
   
   public function get_logined_userid(){
	   # get all data from session
	   $login_user = $this->get_session_data();
	   return isset($login_user['User']['user_id']) ? $login_user['User']['user_id'] : null;
   }
  
   public function get_session_data(){
	   
	   $user_info = array();
		//getting user_info from session for user authentication checking
	   $user_data = $this->Session->read('user.data');
	   return $user_data;
   }
   
   /*
    * get current user role
    */
   public function get_current_user_role(){
		# get session data
		$user_info = $this->get_session_data();
	    		
		# parse the values
		return isset($user_info['Role']['role_id']) ? $user_info['Role']['role_id'] : 0;
   }
   
   /*
    * get logined user's company id
    */
   
    public function get_current_user_company(){
		# get session data
		$user_info = $this->get_session_data();
		
		# parse the values
		return isset($user_info['Company']['company_id']) ? $user_info['Company']['company_id'] : 0;
   }
   
   /*
   /* Method redirect_if_not_company
   */
  /* public function redirect_if_not_company($controller){
	   if($this->get_current_user_role != COMPANY_SUPER_ADMIN){
			$this->$controller->redirect(array('action' => 'index'));
			exit;   
	   }
   }*/
   
   /*
	* Method set_last_url
	* @var url
	*/
	public function set_last_url($url){
		$user_data = $this->get_session_data();
		$ext = substr(strrchr($url, '.'), 1);
		if(!$ext){
			$user_data['User']['last_url'] = $url;
			$this->Session->write('user.data', $user_data); 
			$data =$this->Session->read('user.data');
		}
	}
   
}