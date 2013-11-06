<?php
App::uses('AppController', 'Controller');
/**
 * Monitors Controller
 *
 */
class MonitorsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;
	
	# paging initialize
	public $paginate = array(
        'limit' => DEFAULT_PAGING_LIMIT
    );
	
	public function add($id=null){
		
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
		
		//check the user authenticated or not
		$this->check_logined();
		$company_id = $this->MySession->get_current_user_company();
		$role_id = $this->MySession->get_current_user_role();
		$user_id = $this->MySession->get_logined_userid();
		Controller::loadModel('User');
		Controller::loadModel('AssignMonitor');
						
		# VARIABLES
		$selected_user = array();
		
		// IF FORM SUBMITED
		if($this->request->is('post') or $this->request->is('put')){
			$post_data = $this->data;
			$post_data['Monitor']['company_id'] = $company_id;
			$post_data['Monitor']['status'] = ACTIVE;
			$post_data['Monitor']['id'] = $id;
			$post_data['Monitor']['created_by'] = $user_id;
			$this->Monitor->set($post_data);	
									
			// IF DATA IS VALID
			if($this->Monitor->validates()){
							
				if(!$id){								
					// INSERTION OPERATION
					if($this->Monitor->insertion($post_data)){
						
						# ASSIGN MONTIORS
						$monitor_id = $this->Monitor->getLastInsertID();
					    $this->AssignMonitor->assign_monitors($this->data,$monitor_id);
						
						$this->Session->setFlash('Monitor has been created!','flash_success');
						$this->redirect(array('action' => 'index'));
						exit;	
					}else{
						$this->Session->setFlash('Monitor has not been created!','flash_failure');		
					}
				}else{
					# UPDATION OPERATION
					if($this->Monitor->insertion($post_data)){
						
						# UPDATE THE ASSIGN MONITOR						
						$this->AssignMonitor->update_assign_monitors($this->data,$id);						
						$this->Session->setFlash('Monitor has been update!','flash_success');
						$this->redirect(array('action' => 'index'));
						exit;	
					}else{
						$this->Session->setFlash('Monitor has not been updated!','flash_failure');		
					}
				}
			}else{
				$this->Session->setFlash('Monitor not added, try again!','flash_failure');
			}
		}
		
		# EDIT AND ADD THE MONITOR
		if($id){
			$data = $this->Monitor->get_monitor_data($id);
			$this->request->data = $data;
			$selected_user = $this->AssignMonitor->get_selected_user($id); // get selected users
			$this->set('left_monitors',array());	
		}else{
			$this->set('left_monitors',$this->Monitor->get_left_monitor($company_id));	
		}
		
		# ASSIGN THE MONITORS
		$users = $this->User->get_company_simple_users($company_id);
		$under_users = $this->User->parse_user_and_name($users);
		$this->set('under_users',$under_users);
		$this->set('monitor_id',$id);
		$this->set('selected_user',$selected_user);
		$this->set('test','ravi');
	}
	
	public function index(){
		//check the user authenticated or not
		$this->check_logined();
		$company_id = $this->MySession->get_current_user_company();
		$user_id = $this->MySession->get_logined_userid();
		$controller = $this->params['controller'];
		
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
						
		# GET THE DATA AND SHOW IT
		$cond = array('Monitor.company_id' => $company_id);			
		$this->set('monitors',$this->paginate($cond));
	}
	
	public function delete($id){
		//check the user authenticated or not
		$this->check_logined();
		$company_id = $this->MySession->get_current_user_company();
		
		# check Monitor exist 
		$post_data['Monitor']['company_id'] = $company_id;
		$post_data['Monitor']['id'] = $id;
		$this->Monitor->set($post_data);
		
		# CHECK IF MONITOR EXIST THAN DELETE	
		if($this->Monitor->exist()){
			if ($this->Monitor->delete()) {
				$this->Session->setFlash('Monitor deleted!','flash_success');
				$this->redirect(array('action' => 'index'));
				exit;
			}
		}else{
			$this->Session->setFlash('Monitor not deleted!','flash_failure');
			$this->redirect(array('action' => 'index'));
			exit;
		}
	}

}
