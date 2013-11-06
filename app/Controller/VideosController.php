<?php
App::uses('AppController', 'Controller');
/**
 * Videos Controller
 *
 */
class VideosController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */

	public $scaffold;
	public function index(){	
	}
	
	
	public function add(){
	    
		# check wheather user logined
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		$user_company_id = $this->MySession->get_current_user_company(); // GET CURRENT USER COMPANY
		$this->saveUrl();
		
		# FORM POSTED THE DATA
		if($this->request->is('post')){
						
			# POST DATA INTO THE OBJECT
			$this->Video->set($this->data);
			
			# VALIDATES THE DATA
			if($this->Video->validates()){
						
			# GET SET POST DATA
			$post_file = isset($this->params->data['Video']['submittedfile']) ? $this->params->data['Video']['submittedfile'] : NULL;
			//$file_location =  isset($post_file['tmp_name']) ? $post_file['tmp_name'] : NULL;
			$file_location =  isset($this->params->data['Video']['file_path']) ? $this->params->data['Video']['file_path'] : NULL;
			$file_type = isset($this->params->data['Video']['type']) ? $this->params->data['Video']['type'] : NULL;
			//$name =  isset($post_file['name']) ? $post_file['name'] : NULL;
			$file_name = isset($this->params->data['Video']['name']) ? $this->params->data['Video']['name'] : NULL;
			$file_array = explode('.',$file_location);
			$file_extension = strtolower($file_array[count($file_array)-1]);
			$base_path = getcwd();
						
				# VIDEO TO VIDEO CONVERSION
				if($file_type == VIDEO_TO_VIDEO){	
				        
					# VIDEO LEVEL VALIDATION
					if(in_array($file_extension,Configure::read('Video.formats'))){
											
						$saved_location = $this->VideoEncoder->convert_video($post_file,$file_location);						
						$thumb = $this->VideoEncoder->generate_thumb($saved_location,array('uploaded_company'=>$user_company_id));
						
						# PREPARE THE DATA FOR SAVING 
						$prepare_data = $this->data;
						$prepare_data['Video']['hash'] = '';
						$prepare_data['Video']['status'] = CONVERTED_STATUS;
						$prepare_data['Video']['uploaded_by'] = $user_id;
						$prepare_data['Video']['path'] = $saved_location;
						$prepare_data['Video']['thumbnail'] = $thumb;
						$prepare_data['Video']['uploaded_company'] = $user_company_id;
						$prepare_data['Video']['duration'] = $this->VideoEncoder->duration_raw;
												
						$result = $this->Video->insertion($prepare_data);
						
						if($result){
						$this->Session->setFlash('Video has been upload.','flash_success');
						}else{
						$this->Session->setFlash('Video has not been upload.','flash_failure');	
						}
					}else{
						$this->Session->setFlash('Format has not been supported !','flash_failure');	
					}
				# IMAGES TO VIDEO CONVERSION 
				}else if($file_type == IMAGE_TO_VIDEO){	
				
				  	# VALIDATE WITH CUSTOM VALIDATION  					
				    if(!$this->Video->images_custom_validation()){
						
						# PREPARE THE DATA FOR SAVING 
						$prepare_data = $this->data;
						$prepare_data['Video']['status'] = PENDING_STATUS;
						$prepare_data['Video']['uploaded_by'] = $user_id;
						$prepare_data['Video']['uploaded_company'] = $user_company_id;
						
						# INSERTION OPERATION
						$result = $this->Video->insertion($prepare_data);
						
						# DELETE THE HASH  
						Controller::loadModel('Hash');
						$this->Hash->deleteAll(array('hash.hash' => $prepare_data['Video']['hash']), false);
						
						$this->Session->setFlash('File is processing !','flash_success');
					}
				   
				# DOCUMENT TO VIDEO CONVERSION 
				}else if($file_type == DOCUMENT_TO_VIDEO){
					
					if($this->Video->document_custom_validation() and in_array($file_extension,Configure::read('Document.formats'))){
						
						# POST DATA
						$hash = $this->data['Video']['hash'];
						$process_dir  = PROCESS_DIR . $this->data['Video']['hash'] .'/';
						$file_name = md5($user_id.microtime()) .'.' . $file_extension;
										
						# CREATE DOCUMENT FOLDER IN USERS DIRECTORY
						$dir = new Folder($process_dir,true, 0777);
						
						# MOVE THE FILE TO APPROPRIATE FOLDER 
						if($file_location){
														 
							# PREPARE THE DATA FOR INSERTION
							$prepare_data = $this->data;
							$prepare_data['Video']['status'] = PENDING_STATUS;
							$prepare_data['Video']['uploaded_by'] = $user_id;
							$prepare_data['Video']['uploaded_company'] = $user_company_id;
							
							# INSERTION OPERATION
							$result = $this->Video->insertion($prepare_data);
							if($result){							
							$this->Session->setFlash('File is processing !','flash_success');
							}else{
							$this->Session->setFlash('File has not been processed !','flash_failure');	
							}
						}else{
							$this->Session->setFlash('File has not been processed !','flash_failure');
						}
					}else{
						$this->Session->setFlash('Format has not been supported','flash_failure');	
					}
				}
			}else{
				$this->Session->setFlash('Please enter the below information correctly.','flash_failure');	
			}
		}
		
		# SET SOME VARIABLES FOR VIEW
		$this->set('options',array(1=>'Images',2=>'Video',3=>'Document'));
		$this->set('intervals',array(0=>'Select Interval',2=>'2 second',5=>'5 second',10=>'10 second',20=>'20 second',30=>'30 second'));
		$hash = md5($user_id . microtime());
    
		$this->set('hash', $hash);
	}
	
	public function cron(){
				
		# GET PROCESSING VIDEOS
		$unconverted_videos = $this->Video->find('all',array('conditions' => array('Video.status' => PENDING_STATUS)));
        if(MAXIMUM_CRON_LIMIT < count($unconverted_videos)){
			$total_unprocess = MAXIMUM_CRON_LIMIT;
		}else{
			$total_unprocess = count($unconverted_videos);
		}
			
		for($i=0;$i<$total_unprocess;$i++){
			
			# PREPARE DATA FOR VIDEO CONVERSION 
			$parse_data =isset($unconverted_videos[$i]['Video']) ? $unconverted_videos[$i]['Video'] : null;
						
			# PASS DATA TO ENCODER FOR CONVERSION
			if(!empty($parse_data)){
				# TIME CALCULATION
				$start_time = microtime(true);
				echo $video = $this->VideoEncoder->process_video($parse_data);
				$end_time = microtime(true);
				echo ' take ' . $time = round($end_time - $start_time), ' sec';
						
				if($video){				
				# PREPARE DATA FOR UPDATION
				$thumb = $this->VideoEncoder->generate_thumb($video,$parse_data);
				$update_array = array('Video.status' => "'" . 1 . "'",'Video.thumbnail'=>"'".$thumb."'",'Video.path'=> "'" .$video ."'",'Video.hash'=>"''",'Video.duration'=>"'".$this->VideoEncoder->duration_raw."'");		
					$prepare_data['Video']['status'] = 1;
					$this->Video->updateAll($update_array,array('Video.id' => $parse_data['id']));	
				}
			}
		}
		exit;
    }
	
	public function listVideo($id = null,$monitor_id=0){
		
	    $query_string = '';
		$query_string = implode('/',$this->params['pass']);
		$this->saveUrl($query_string);
				
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
	    $user_company_id = $this->MySession->get_current_user_company(); // GET CURRENT USER COMPANY		
		Controller::loadModel('Timeline');
		$monitor_id = is_numeric($monitor_id) ? $monitor_id : 0;
		
		$query_string = '';
		$query_string = implode('/',$this->params['pass']);
		$url = Router::url('/', true).$this->params['controller'].'/'.$this->params['action'].'/'.$query_string;
		$this->MySession->set_last_url($url);
								
		# GET USER'S VIDEOS
		$result_data = $this->Video->get_user_videos($user_id);
		$user_dir = $this->webroot. 'files/' . $user_company_id.'/';
		$new_videos = $this->Video->get_videos($user_company_id,NEW_BIN);
		$current_videos = $this->Video->get_videos($user_company_id,CURRENT_BIN);
		$archived_videos = $this->Video->get_videos($user_company_id,ARCHIVED_BIN);
		$timeline_videos = $this->Timeline->get_timeline_videos($monitor_id);
		$total_timline_duration = $this->Timeline->get_total_duration($monitor_id);
																						
		if($id){
			$video_data = $this->Video->get_video($user_company_id,$id);
			$current_video = isset($video_data['Video']['path']) ? $video_data['Video']['path'] : null;	
		}else{
			
			$current_video = isset($timeline_videos[0]['videos']['path']) ? $timeline_videos[0]['videos']['path'] : null; 
		    if(!$current_video){
				$video_data = $this->Video->get_video($user_company_id,$id);
				$current_video = isset($video_data['Video']['path']) ? $video_data['Video']['path'] : null;	
			}
		}
				
		# PASS DATA TO VIEW
		$this->set('selected_video',$id);
		$this->set('timeline_videos',$timeline_videos);
		$this->set('monitor_id',$monitor_id);
		$this->set('total_timline_duration',$total_timline_duration);
		$this->set('new_videos',$new_videos);
		$this->set('current_videos',$current_videos);
		$this->set('archived_videos',$archived_videos);
		$this->set('user_dir',$user_dir);
		$this->set('current_video',$current_video);
		$this->set('data_array', $result_data);
	}
	
	public function removeJunkCron(){
		# GET UNUSED HASHES
		Controller::loadModel('Hash');
		$result_data = $this->Hash->get_hash();
		$counter = 0;
		
		# REMOVE DIRECTORIES ONE BY ONE
		for($i=0;$i<count($result_data);$i++){
			$hash = isset($result_data[$i]['Hash']['hash']) ? $result_data[$i]['Hash']['hash'] : null;	
			if($hash){
				$file_location = PROCESS_DIR . $hash .'/';
				$this->Hash->rrmdir($file_location);
				$this->Hash->unset_hash($hash);	
				$counter++;	
			}
		}
		
		echo $counter . ' folders deleted!';
		exit;
	}
	
	public function uploadify($hash,$data_type){
		
		// Define a destination
		$target_path = PROCESS_DIR  . $hash . '/';
				
		if (!empty($_FILES)) {
			$temp_file = $_FILES['Filedata']['tmp_name'];
			/*//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
			@mkdir($target_path, 0777, true);*/
			$random_name = md5(rand(1,100).microtime());
			
			# CREATE DOCUMENT FOLDER IN USERS DIRECTORY
			$dir = new Folder($target_path,true, 0777);
			$file_parts = pathinfo($_FILES['Filedata']['name']);
			$target_file = rtrim($target_path,'/') . '/' . $random_name .'.'. $file_parts['extension'];
			
			// Validate the file type
			//$fileTypes = array('tiff','jpg','jpeg'); // File extensions
			if($data_type == 'video'){
				$extensions = Configure::read('Video.formats');
			}else if($data_type == 'image'){
				$extensions = Configure::read('Image.formats');
			}else{
				$extensions = Configure::read('Document.formats');
			}
			
			if (in_array(strtolower($file_parts['extension']),$extensions)) {
				move_uploaded_file($temp_file,$target_file);
				Controller::loadModel('hash');
		        $this->hash->set_hash($hash);
				$result['file_path'] = $target_file;
				$result['response'] = true;
				$result['msg'] = 'Upload images successfully.';
				echo json_encode($result);
			} else {
				$result['response'] = false;
				$result['msg'] = $file_parts['extension'] . ' is Invalid file type.';
				echo json_encode($result);
			}
		}
	    exit;
	}
	
	public function jcarouselAjax($first,$last){
		
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		
		# GET USER'S VIDEOS
		$limit_result_data = $this->Video->get_user_videos($user_id,$first-1,$last);
		$total_result_data = $this->Video->get_user_videos($user_id);
		$user_dir = $this->webroot. 'files/' . $user_id.'/';
		$total    = count($total_result_data);
		
		# XML CONTENT
		header('Content-Type: text/xml');
		
		echo '<data>';
		
		// Return total number of images so the callback
		// can set the size of the carousel.
		echo '  <total>' . $total . '</total>';
		
		for($i=0;$i<count($limit_result_data);$i++){
			echo '  <imagedata>';
			echo '  <image>' . $user_dir . $limit_result_data[$i]['Video']['thumbnail'] . '</image>';
			echo '  <link>' . $limit_result_data[$i]['Video']['id'] . '</link>';
			echo '  </imagedata>';
		}
		
		echo '</data>';
		exit;
	}
	
	public function changeBinAjax(){
		
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		
		# FORM POSTED THE DATA
		if($this->request->is('post')){
			
			# VARIABLE PASED FROM AJAX
			$video_id = $this->params->data['video_id'];
			$bin = $this->params->data['bin'];
			
			$update_array = array('Video.bin' => "'" . $bin . "'");		
			echo $this->Video->updateAll($update_array,array('Video.id' => $video_id));		
			exit;
		}
	}
	
	public function setTimelineAjax(){
		
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		$video_id = '';
		
		# FORM POSTED THE DATA
		if($this->request->is('post')){
			
			# LOAD THE MODEL 
			Controller::loadModel('Timeline');
			$data = $this->params->data;
			
			# SAPRATE THE VIDEO IDS AND MONITOR ID 
			$total_video_data = explode('#',$data);
			$monitor_id = isset($total_video_data[1]) ? $total_video_data[1] : 0;
			
			# GO BACK IF MONITOR ID HAS NOT EXIST
			if(!$monitor_id){
				echo $monitor_id;
			}
			
			# DELETION PROCESS 
			$result = $this->Timeline->deletion($monitor_id);
			$video_ids = explode('%',$total_video_data[0]);
			
			# INSERTION PROCESS
			for($i=0;$i < count($video_ids); $i++){
			   $video_id = !empty($video_ids[$i]) ? $video_ids[$i] : null;
			   	
			   if($video_id){
				$prepare_data['Timeline']['monitor_id'] = $monitor_id;
				$prepare_data['Timeline']['video_id'] = $video_id;
				$prepare_data['Timeline']['order'] = $i;
				$result = $this->Timeline->insertion($prepare_data);
			   }
			}
			print_r($video_ids);
			exit;
		}
	}
	
	function removeFromTimeline(){
		
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		$company_id = $this->MySession->get_current_user_company(); // GET CURRENT USER ID
				
		# FORM POSTED THE DATA
		if($this->request->is('post')){
			
			# GET AJAX DATA
			Controller::loadModel('Timeline');
			$video_id = isset($this->params->data['video_id']) ? $this->params->data['video_id'] : 0;
		    $monitor_id = isset($this->params->data['monitor_id']) ? $this->params->data['monitor_id'] : 0;
			$order = isset($this->params->data['order']) ? $this->params->data['order'] : 0;
			
			# CHECK THE COMPANY CORRESPONDING TO VIDEO 
			if($this->Video->check_video_under_company($video_id,$company_id))
			{
				echo $this->Timeline->remove_video($monitor_id,$video_id,$order);	
			}
		}
		exit;
	}
	
	function removeVideoAjax(){
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		$company_id = $this->MySession->get_current_user_company(); // GET CURRENT USER ID
						
		# FORM POSTED THE DATA
		if($this->request->is('post')){
			
			# GET AJAX DATA
			Controller::loadModel('Timeline');
			$video_id = isset($this->params->data['video_id']) ? $this->params->data['video_id'] : 0;
			
			# CHECK THE COMPANY CORRESPONDING TO VIDEO 
			$this->Video->remove_video($video_id,$user_id);
			echo 1;
		}
		exit;
	}
	
	public function getVideosAjax(){
		
		//sleep(15);
		# CHECK LOGINED AND GET USER ID
		$this->check_logined();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		$company_id = $this->MySession->get_current_user_company(); // GET CURRENT USER ID
		$user_dir = $this->webroot. 'files/' . $company_id.'/';
		$number_of_result = 0;			
				
		# FORM POSTED THE DATA
		if($this->request->is('post')){
			
			# GET AJAX DATA
			$items = isset($this->params->data['items']) ? $this->params->data['items'] : 0;
			$max_items = isset($this->params->data['max_items']) ? $this->params->data['max_items'] : 0;
			$bin = isset($this->params->data['bin']) ? $this->params->data['bin'] : 0;
			$monitor_id = isset($this->params->data['monitor_id']) ? $this->params->data['monitor_id'] : 0;
			$next_number_of_result = 0;
						
			# GET APPROPRIATE BIN
			if($bin == 'ARCHIVED_BIN'){
				# GET LIST VIDEOS
				$archived_videos = $this->Video->get_videos($company_id,ARCHIVED_BIN,$items,$max_items);
				$next_number_of_result = count($this->Video->get_videos($company_id,ARCHIVED_BIN,$items+MAX_ITEMS_IN_BIN,$max_items));
				$html = '';
				$number_of_result = count($archived_videos);
				
				# CREATION OF HTML 
				for( $i=0; $i < count($archived_videos); $i++){ 
				$data = $archived_videos[$i]['Video'];
                $html .= '<li id="ap'.$j = $i+$items.'" class="draggable_archived ui-draggable" path="'.$user_dir.$data['path'].'" vid="'.$data['id'].'" duration="'.$data['duration'].'">';
                $html .= '<div class="hide-button video_close"></div>';
                if($data['thumbnail']){ 
                        $image_path = $user_dir . $data['thumbnail'];
                        $html .= '<a href="#">';
                        $html .= '<img class="DragBox" id="'.$i.'" overclass="OverDragBox" dragclass="DragDragBox" src="'.$image_path.'" alt="On top of Kozi kopka" width="96" height="72" /></a>';
                }else{
                      $html .= '<a href="#">';
                      $html .= '<img class="DragBox" id="'.$i.'" overclass="OverDragBox" dragclass="DragDragBox" src="'.$this->webroot.'/img/no-thumbnail-available.jpg" alt="On top of Kozi kopka" width="96" height="72" /></a>';
                 } 
                    $html .= '<span style="text-align:center">'.$data['name'].'</span></li>';
               } 
			}else if($bin == 'NEW_BIN'){
				# GET LIST VIDEOS
				$new_videos = $this->Video->get_videos($company_id,NEW_BIN,$items,$max_items);
				$next_number_of_result = count($this->Video->get_videos($company_id,NEW_BIN,$items+MAX_ITEMS_IN_BIN,$max_items));
				$html = '';
				$number_of_result = count($new_videos);
				
				# CREATION OF HTML 
				for( $i=0; $i < count($new_videos); $i++){ 
				$data = $new_videos[$i]['Video'];
				if($data['path']){
					$html .= '<li id="an'.$j = $i+$items.'" class="draggable_new" path="'.$user_dir.$data['path'].'" vid="'.$data['id'].'" duration="'.$data['duration'].'">';
					$html .= '<div class="hide-button video_close"></div>';
					if($data['thumbnail']){ 
							$image_path = $user_dir . $data['thumbnail'];
							$html .= '<a href="#">';
							$html .= '<img class="DragBox" id="'.$i.'" overclass="OverDragBox" dragclass="DragDragBox" src="'.$image_path.'" alt="On top of Kozi kopka" width="96" height="72" /></a>';
					}else{
						$html .= '<a href="#">';
                        $html .= '<img class="DragBox" id="'.$i.'" overclass="OverDragBox" dragclass="DragDragBox" src="'.$this->webroot.'/img/no-thumbnail-available.jpg" alt="On top of Kozi kopka" width="96" height="72" /></a>';
					 } 
						$html .= '<span style="text-align:center">'.$data['name'].'</span></li>';
				   } 
				}
			}else if($bin == 'CURRENT_BIN'){
				# GET LIST VIDEOS
				$current_videos = $this->Video->get_videos($company_id,CURRENT_BIN,$items,$max_items);
				$next_number_of_result = count($this->Video->get_videos($company_id,CURRENT_BIN,$items+MAX_ITEMS_IN_BIN,$max_items));
				$html = '';
				$number_of_result = count($current_videos);
				
				# CREATION OF HTML 
				for( $i=0; $i < count($current_videos); $i++){ 
				$data = $current_videos[$i]['Video'];
                $html .= '<li id="ac'.$j = $i+$items.'" class="draggable_current" path="'.$user_dir.$data['path'].'" vid="'.$data['id'].'" duration="'.$data['duration'].'">';
                $html .= '<div class="hide-button video_close"></div>';
                if($data['path']){ 
                        $image_path = $user_dir . $data['thumbnail'];
                        $html .= '<a href="#">';
                        $html .= '<img class="DragBox" id="'.$i.'" overclass="OverDragBox" dragclass="DragDragBox" src="'.$image_path.'" alt="On top of Kozi kopka" width="96" height="72" /></a>';
                }else{
                        $html .= '<a href="#">';
                        $html .= '<img class="DragBox" id="'.$i.'" overclass="OverDragBox" dragclass="DragDragBox" src="'.$this->webroot.'/img/no-thumbnail-available.jpg" alt="On top of Kozi kopka" width="96" height="72" /></a>';
                 } 
                    $html .= '<span style="text-align:center">'.$data['name'].'</span></li>';
               } 	
			}
		}
		
		# CREATION OF JSON AND PASS TO AJAX
		$arr['html'] = $html;
		$arr['total_results'] = $number_of_result;
		$arr['has_more_result'] = $next_number_of_result;
		echo json_encode($arr);
		exit;
	}
	
	public function test(){
		echo "hi";
		exit;
	}
	
}
