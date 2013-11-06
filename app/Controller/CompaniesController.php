<?php
App::uses('AppController', 'Controller');
/**
 * Companies Controller
 *
 * @property Company $Company
 */
class CompaniesController extends AppController {
	
/**
 * index method
 *
 * @return void
 */
   
    public $helpers = array('Js' => array('test'));
    
	# paging initialize
	public $paginate = array(
        'limit' => DEFAULT_PAGING_LIMIT
    );
 
	public function index() {
		# check wheather user has logined
		$this->check_logined();
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
			
		# prepare for paging
		$current_page = isset($this->passedArgs['page']) ? $this->passedArgs['page']-1 : 0;
		$limit = isset($this->paginate['limit']) ? $this->paginate['limit'] : 10;
		
		# get corresponding companies data
		$this->Company->recursive = 0;
		$this->set('companies',$this->Company->get_all_companies($current_page,$limit), $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
	
		//check the user authenticated or not
		$this->check_logined();		
		
		# set company id and check if company 
		$this->Company->id = $id;
		if (!$this->Company->exists()) {
			throw new NotFoundException(__('Invalid company'));
		}
		$this->set('company', $this->Company->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	
		//check the user authenticated or not
		$this->check_logined();
		// SAVE PATH INTO SESSION		
		$this->saveUrl();
					
		# if data posted by form
		if ($this->request->is('post')) {
						
			//set the data available for model		
			$this->Company->set($this->request->data);
			//check validations				
			if($this->Company->validates()){
				# create company			
				$this->Company->create();
				# save company
				if ($this->Company->save($this->request->data)) {
					$this->Session->setFlash('Company added successfully.','flash_success');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('Company added failed. Please, try again.','flash_failure');
				}
			}else{
				$this->Session->setFlash('Company added failed. Please, try again.','flash_failure');
			}
		}
		# get all countries
		$countries = $this->Company->Country->find('list',array());
		$all_countries = $this->Company->get_country_info();
		$all_states = $this->Company->get_state_info();
		$states = $this->Company->State->find('list');
		$this->set(compact('countries', 'states'));
		$this->set('countries_data',$all_countries);		
		$this->set('states_data',array(0=>'Select State'));		
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
		$this->saveUrl($id);
		
		# check if company exist
		$this->Company->id = $id;
		if (!$this->Company->exists()) {
			throw new NotFoundException(__('Invalid company'));
		}
		
		# save the data into company 
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Company->save($this->request->data)) {
				$this->Session->setFlash('Company information updated successfully. ','flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The company could not be saved. Please, try again.','flash_failure');
			}
		} else {
		
			//assigning countries and states info to variables to show in view		
			$this->set('countries_data',$this->Company->get_country_info());			
			$this->set('states_data',$this->Company->get_state_info());			
			$this->request->data = $this->Company->read(null, $id);
		}
				
		# get all countries
		$countries = $this->Company->Country->find('list',array());
		$all_countries = $this->Company->get_country_info();
		$all_states = $this->Company->get_state_info();
		$states = $this->Company->State->find('list');
		$this->set(compact('countries', 'states'));
		$this->set('countries_data',$all_countries);		
		$this->set('states_data',$this->Company->get_state_info());				
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
	   
		//check the user authenticated or not
		$this->check_logined();
	
		# post request from any form
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		
		# check company exist 
		$this->Company->id = $id;
		if (!$this->Company->exists()) {
			throw new NotFoundException(__('Invalid company'));
		}
		
		# deletion process
		$this->Company->delUsers($id);
		if ($this->Company->delete()) {
			$this->Session->setFlash('Company deleted successfully','flash_success');
			$this->redirect(array('action' => 'index'));
		}
		
		# notification and redirection
		$this->Session->setFlash('Company deleted failed','flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
}