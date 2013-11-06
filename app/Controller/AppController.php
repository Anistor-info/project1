<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller {
    
	public $components = array('MySession', 'Session','VideoEncoder');
	public $helpers = array('Global','Form','Session','Html','CountryList','Paginator');
	    
    public function beforeFilter() {
				
		/*
		*** ACCESS PERMISSIONS
		*/
        $controller = $this->params['controller'];
	    $action = $this->params['action'];
	    
		# GET SOME DATA FROM SESSION
		$user_data = $this->Session->read('user.data');	
				
		$privilages = explode(',',$user_data['Privilages']);
		$user_id = $user_data['User']['user_id'];
		$role_id = $user_data['User']['role_id'];
		
		# GET SOME PRIVILAGES FROM DATABASE
		Controller::loadModel('Privilege');
		$privilage_array = $this->Privilege->find('all');
		
		for($i=0;$i<count($privilage_array);$i++){
			$data[$privilage_array[$i]['Privilege']['controller']][$privilage_array[$i]['Privilege']['action']] = $privilage_array[$i]['Privilege']['id'];
		}
        
		# GET CURRENT ACTION 									
		$current_action = isset($data[$controller][$action]) ? $data[$controller][$action] : false;
		
		# CHECK THE CURRENT PERMISSION
        if($current_action){
			if((!in_array($current_action,$privilages))){
			 //$this->Session->setFlash(__(''));
			 $this->redirect(array('controller'=>'users','action' => 'home'));	
			}
		}
		/*
		*** ACCESS PERMISSIONS
		*/	
	
    }
	
	 public function check_logined(){
	    
		$user_info = array();
		//getting user_info from session for user authentication checking
		$user_data = $this->MySession->get_session_data();
        		
		//if not valid user redirect user back to the login page
		if(!$user_data){
			$last['action'] = $this->params['action'];
			$last['controller'] = $this->params['controller'];
			$this->Session->write('last',$last);
			$this->redirect(array('controller'=>'users','action' => 'login'));
			$this->Session->distroy();
		}
   }
   
   # IF PAGE NOT FOUND
     public function afterFilter(){
	
		 if($this->response->statusCode() == '404'){
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		  }	
   }
   
   public function saveUrl($query_string = NULL){
		$url = Router::url('/', true).$this->params['controller'].'/'.$this->params['action'].'/'.$query_string;
		$this->MySession->set_last_url($url);	   
   }
   
   
}
