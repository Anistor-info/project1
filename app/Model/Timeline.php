<?php
App::uses('AppModel', 'Model');
/**
 * Timeline Model
 *
 * @property Monitor $Monitor
 * @property Video $Video
 */
class Timeline extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'monitor_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'video_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'order' => array(
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
	
	/**
	* 	INSERTION OPERATION
	**/
	public function insertion($prepare_data){
		$this->create();
		$this->set($prepare_data);
		return $this->save();
	}
	
	/**
	* 	DELETION OPERATION
	**/
	public function deletion($monitor_id){
		return $this->deleteAll(array('monitor_id' => $monitor_id));
	}
	
	/**
	* 	GET VIDEOS CORRESPONDING 
	**/
	public function get_videos($monitor_id){
		return $this->find('all',array('conditions' => array('Timeline.monitor_id'=>$monitor_id)));
	}
	
	/**
	* 	GET TOTAL DURATION OF MONITOR
	**/
	public function get_total_duration($monitor_id){
		$query = "SELECT SEC_TO_TIME(sum(TIME_TO_SEC(duration))) as duration FROM `timelines` join `videos` where `videos`.id = `timelines`.video_id and `timelines`.monitor_id = $monitor_id order by timelines.order";
		$result_array = $this->query($query);
		return isset($result_array[0][0]['duration']) ? $result_array[0][0]['duration'] : '00:00:00'; 	
	}
	
	/**
	* 	GET TIMELINE VIDEOS CORRESPODING TO THE MONITOR
	**/
	public function get_timeline_videos($monitor_id){
		$query = "SELECT * FROM `timelines` join `videos` where `videos`.id = `timelines`.video_id and `timelines`.monitor_id = $monitor_id order by timelines.order";
		return $result_array = $this->query($query);	
	}
	
	/**
	* 	REMOVE VIDEO FROM TIMELINE 
	**/
	public function remove_video($monitor_id,$video_id,$order){
		return $this->deleteAll(array('monitor_id' => $monitor_id,'video_id'=>$video_id,'order'=>$order));
	}
}
