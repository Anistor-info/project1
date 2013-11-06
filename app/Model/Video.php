<?php
App::uses('AppModel', 'Model');
/**
 * Video Model
 *
 * @property UploadedUser $UploadedUser
 * @property Monitor $Monitor
 * @property Processing $Processing
 */
class Video extends AppModel {
/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'video_id';
/**
 * Validation rules
 *
 * @var array
 */
	var $validate = array(
	'name'=>array(
		'name'=>array(
		'rule'=>'notEmpty',
		'message'=>'Please enter name.'
		)
		),
	'interval'=>array(
		'interval'=>array(
		'rule'=>'notEmpty',
		'message'=>'Please select interval.'
		)
		),
	'type'=>array(
		'type'=>array(
		'rule'=>'notEmpty',
		'message'=>'Please select type.'
		)
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	/*public $belongsTo = array(
		'UploadedUser' => array(
			'className' => 'UploadedUser',
			'foreignKey' => 'uploaded_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);*/

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	/*public $hasAndBelongsToMany = array(
		'Monitor' => array(
			'className' => 'Monitor',
			'joinTable' => 'monitors_videos',
			'foreignKey' => 'video_id',
			'associationForeignKey' => 'monitor_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Processing' => array(
			'className' => 'Processing',
			'joinTable' => 'processing_videos',
			'foreignKey' => 'video_id',
			'associationForeignKey' => 'processing_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);*/
	
/**
 * Method image custom validation
 *
 */
	public function images_custom_validation(){
		
		$error = 0;
		# CUSTOM VALIDATIONS
		if($this->data['Video']['interval'] < 1){
			 $this->validationErrors['interval'] = 'Interval should be selected!';
			 $error = 1; 	 
		}else if(!is_dir(PROCESS_DIR . $this->data['Video']['hash'])){
			 $this->validationErrors['type'] = 'Upload images for processing!';
			 $error = 1; 
		}else if($this->dir_is_empty(PROCESS_DIR . $this->data['Video']['hash'])){
			 $this->validationErrors['type'] = 'Upload images for processing!';
			 $error = 1; 
		}
		return $error;
	}
	
	 public function dir_is_empty($dir) {
	  if (!is_readable($dir)) return NULL;
	  return (count(scandir($dir)) == 2);
	}

/**
 * Video custom validation
 *
 */
	public function video_custom_validation(){
		# CUSTOM VALIDATIONS
		if(!$this->data['Video']['submittedfile']['name']){
			  $this->validationErrors['type'] = 'Please Select file!';
			 return false; 
		}
		return true;	
	}

/**
 * Document custom validation
 *
 */	
	public function document_custom_validation(){
		
		# CUSTOM VALIDATIONS
		if($this->data['Video']['interval'] < 1){
			 $this->validationErrors['interval'] = 'Interval should be selected!';
			 return false; 
		}else if(!$this->data['Video']['file_path']){
			 $this->validationErrors['submittedfile'] = 'Please Select file!';
			 return false; 
		}
		return true;	
	}

/**
* Insertion operation
* @var array
*/
	public function insertion($prepare_data){
		$prepare_data['Video']['uploaded_on'] = date('Y-m-d H:i:s'); // upload time and date
		$this->set($prepare_data);
		return $this->save();
	}

/**
* Method Get user video
*  
* @var user id
* @var offset
* @var limit
*/	
	public function get_user_videos($user_id,$offset=null,$limit=null){
		if($offset and $limit){
			return $this->find('all',array('conditions' => array('Video.status'=>1,'Video.uploaded_by'=>$user_id),'offset'=>$offset,'limit'=>$limit));
		}
		return $this->find('all',array('conditions' => array('Video.status'=>1,'Video.uploaded_by'=>$user_id)));
	}
	
/**
* Get videos
* 
* @var company id
* @var video id
*/
	public function get_video($company_id,$video_id){
		# IF VIDEO ID NOT PROVIDED 
		if($video_id){
			return $this->find('first',array('conditions' => array('Video.status'=>1,'Video.id'=>$video_id,'Video.uploaded_company'=>$company_id)));
		}else{
			return $this->find('first',array('conditions' => array('Video.status'=>1,'Video.uploaded_company'=>$company_id)));	
		}
	}

/**
* Get videos
* 
* @var company id
* @var bin
* @var offset  
* @var limit
*/	
	public function get_videos($company_id,$bin,$offset=0,$limit=null){
		if($limit){
			return $this->find('all',array('conditions' => array('Video.status'=>1,'Video.uploaded_company'=>$company_id,'Video.bin'=>$bin),'offset'=>$offset,'limit' => $limit,'order' => array('Video.uploaded_on DESC')));
		}else{
			return $this->find('all',array('conditions' => array('Video.status'=>1,'Video.uploaded_company'=>$company_id,'Video.bin'=>$bin),'order' => array('Video.timestamp DESC','Video.uploaded_on DESC')));
		}
	}

/**
* Check the videos under company
* 
* @var video id
* @var company id
*/		
	public function check_video_under_company($video_id,$company_id){
		return $this->find('all',array('conditions' => array('Video.status'=>1,'Video.uploaded_company'=>$company_id,'Video.id'=>$video_id)));
	}

/**
* Remove video
* 
* @var video id
* @var user id
*/
	public function remove_video($video_id,$user_id){
		$this->remove_video_data($user_id,$video_id);
		$query = "delete from videos where id = '".$video_id."'";
		return $this->query($query);
	}
	
/**
* Remove 
* 
* @var video id
* @var company id
*/
	public function remove_video_data($user_id,$video_id){
		$data = $this->find('first',array('conditions' => array('Video.id'=>$video_id)));
		$video_path = ROOT . '/app/webroot/files/'.$user_id.'/'.$data['Video']['path'];
		$thumb_path = ROOT . '/app/webroot/files/'.$user_id.'/'.$data['Video']['thumbnail'];
		unlink($thumb_path);
		return unlink($video_path); 
	}

}
