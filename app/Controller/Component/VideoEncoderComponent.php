<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class VideoEncoderComponent extends Component {
	
	var $name = "VideoEncoder";
	public $components = array('Session','MySession');
	
		// Bitrate
        var $bitrate = 0;

		// Duration
        var $duration = 0;
        var $duration_raw = "";

		// Codec Name
        var $vcodec = "";

        var $w = 0;
        var $h = 0;

        var $framerate = "";
        var $acodec = "";
        var $audiorate = "";
        var $channels = "";
        var $audiobitrate = "";

	
	/*
	*	TO CONVERT VIDEO TO VIDEOS
	*/
		
	function convert_video($post_file,$file_location) {
		
		# SOME INTIALIZE VARIABLES
		$base_path = getcwd();
		$user_id = $this->MySession->get_logined_userid(); // GET CURRENT USER ID
		$user_company_id = $this->MySession->get_current_user_company(); // GET CURRENT USER ID
		$size =  isset($post_file['size']) ? $post_file['size'] : NULL;
		$type =  isset($post_file['type']) ? $post_file['type'] : NULL;
		$name =  isset($post_file['name']) ? $post_file['name'] : NULL;
		
		
		if($user_id){
			
			# GET UPLOADED FILE TYPE
			$file_array = explode('.',$file_location);
			$file_type = strtolower($file_array[count($file_array)-1]);
			
			if(in_array($file_type,Configure::read('Video.formats'))){
				
				# CREATE DIRECTORY FOR USER DATA
				$user_dir = $base_path.'/files/'.$user_company_id;
				$dir = new Folder($user_dir,true, 0755);
				$random_filename = $this->random_name('mp4');;
				$save_location = $user_dir .'/'. $random_filename;
				
				# PROCESS OF VIDEOS CONVERSION
				exec(Configure::read('Video.ffmpeg_path') . " -i ".$file_location." -sameq -y ".$save_location." 2>&1",$output);
							
				# GETTING VIDEO DURRATION
				$this->phpffmpeg($save_location);
				
				# IF VIDEO HAS DURRATION												
				if($this->duration){
					$this->Session->setFlash('Video has been processed.');
					return $random_filename;
				}else{
					$this->Session->setFlash('Error please try again later.');
					return false;	
				}
			}
		}else{
			return;
		}
	}
	
	/*
	*	TO PROCESS THE VIDEOS
	*/
	
	function process_video($data_array){
		
		# PARSE DATA FROM DATA ARRAY
		$type = isset($data_array['type']) ? $data_array['type'] : null;
				
		# GET THE TYPE AND SEND THAT TO APPRORIATE METHOD
		if($type == IMAGE_TO_VIDEO){
			return $video_path = $this->convert_images_to_video($data_array);
		}else if($type == DOCUMENT_TO_VIDEO){
			return $video_path = $this->convert_document_to_video($data_array);
		}
	}
	
	/*
	*	TO CONVERT IMAGES TO VIDEOS
	*/
	
	function convert_images_to_video($data_array){
		
		# PARSE DATA FROM DATA ARRAY
		$interval = isset($data_array['interval']) ? $data_array['interval'] : null;
		$uploaded_by = isset($data_array['uploaded_company']) ? $data_array['uploaded_company'] : null;
		$hash = isset($data_array['hash']) ? $data_array['hash'] : null;
		$base_path = getcwd();
	
		# CREATE DIRECTORY FOR USER DATA
		if($interval and $uploaded_by and $hash){
			$user_dir = $base_path.'/files/'.$uploaded_by;
			$dir = new Folder($user_dir,true, 0755);
			$random_filename = $to_pdf = $this->random_name('mp4');;
			$save_location = $user_dir .'/'. $random_filename;
			$file_location = PROCESS_DIR . $hash .'/';
			$cmd_prfix = IMAGES_PREFIX;
			$prefix = IMAGES_PREFIX;
			
			# FORMAT CONVERSION FOR VIDEO CONVERSION
			$counter = 1;
			$total_image_in_folder = glob($file_location . "*.*");
			$no_of_images = count($total_image_in_folder);
			
			foreach ($total_image_in_folder as $filename) {
				if(strlen($counter) == 1){
					$prefix = IMAGES_PREFIX . '00';
				}else if(strlen($counter) == 2){
					$prefix = IMAGES_PREFIX . '0';
				}else if(strlen($counter) == 3){
					$prefix = IMAGES_PREFIX;
				}
				
				$dynamic_filename = $file_location . $prefix . $counter . '.jpg';
				$cmd = Configure::read('Video.ffmpeg_path') ." -i ".$filename." -y $dynamic_filename 2>&1";
				$counter++;
								
				exec($cmd,$output);
				unlink($filename);
		    }
			
			# CALCULATIONS FOR TIME INTERVALS
			$frame_rate = 1 / $interval;
			$duration = ($no_of_images * $interval) + 2; 
			$images_path = $file_location.$cmd_prfix."%03d.jpg";
			
			# FIX FOR 2 IMAGES ON 00:00
			$this->image_fix($file_location);
			
			$output = $this->process_image_to_video($frame_rate,$duration,$images_path,$save_location);
				
			# GETTING VIDEO DURRATION
			$this->phpffmpeg($save_location);
			
			# IF VIDEO HAS DURRATION	
			if($this->duration){
				$this->delete_junk($hash);
				return $random_filename;
			}else{
				return false;	
			}
		}else{
			return;	
		}
	}
		
	/*
	*	TO CONVERT IMAGES TO VIDEOS
	*/
	
	function convert_document_to_video($data_array){
			
		# PARSE DATA FROM DATA ARRAY
		$interval = isset($data_array['interval']) ? $data_array['interval'] : null;
		$uploaded_by = isset($data_array['uploaded_company']) ? $data_array['uploaded_company'] : null;
		$hash = isset($data_array['hash']) ? $data_array['hash'] : null;
		$base_path = getcwd();
		
		# CREATE DIRECTORY FOR USER DATA
		if($interval and $uploaded_by and $hash){
			$user_dir = $base_path.'/files/'.$uploaded_by;
			$dir = new Folder($user_dir,true, 0755);
			$random_filename = $this->random_name('mp4');
			$save_location = $user_dir .'/'. $random_filename;
			$file_location = PROCESS_DIR . $hash .'/';
			$data = glob($file_location . "*.*");
			$processing_file = isset($data[0]) ? $data[0] : null;
			$extension = strtolower(end(explode('.', $processing_file)));
			
			# CALCULATIONS FOR TIME INTERVALS
			$outputs = file_get_contents($processing_file);
            $counter = preg_match_all("/\/Page\W/", $outputs, $dummy);
			$frame_rate = 1 / $interval;
			$duration = ($counter * $interval) + 2; 
			$images_path = $file_location.IMAGES_PREFIX."%03d.jpg";
			$start_time = microtime(true);
			
			# PASS FILE FOR CONVERSION 
			if($extension == 'pdf'){
				# IDENTIFY THE IMAGES 
				$im = new imagick( $processing_file );
				$pdf_output = $im->identifyImage();
				$width = isset($pdf_output['geometry']['width']) ? $pdf_output['geometry']['width'] : 0;
				$height = isset($pdf_output['geometry']['height']) ? $pdf_output['geometry']['height'] : 0;
								
				# PROCESS THE PDF TO IMAGES				
				if( ($width == 612) and ($height == 792) ){
					$image_output = $this->process_pdf_to_images($processing_file,$file_location);	
					
					# FIX FOR 2 IMAGES ON 00:00
					$data = $this->image_fix($file_location);
												
					# PROCESS IMAGES TO VIDEO
					$video_output = $this->process_pdf_image_to_video($frame_rate,$duration,$images_path,$save_location);
				}else{
					$image_output = $this->process_odd_demension_pdf_to_images($processing_file,$file_location);	
					
					# FIX FOR 2 IMAGES ON 00:00
					$this->image_fix($file_location);
					
					# PROCESS IMAGES TO VIDEO
				    $video_output = $this->process_image_to_video($frame_rate,$duration,$images_path,$save_location);
				}
											
				$this->delete_junk($hash);	
				return $random_filename;
			}else if(in_array($extension,Configure::read('Ms.formats'))){
				# MS DOCUMENT TO PDF CONVERSION
				$to_pdf = $file_location . $this->random_name('pdf');
				$document_output = $this->process_doc_to_pdf($processing_file,$to_pdf);
																
				# PDF TO IMAGE 
				$image_output = $this->process_odd_demension_pdf_to_images($to_pdf,$file_location);	
				
				# FIX FOR 2 IMAGES ON 00:00
				$this->image_fix($file_location);
						
				# COUNTERS
				$outputs = file_get_contents($to_pdf);
                $counter = preg_match_all("/\/Page\W/", $outputs, $dummy);
				$frame_rate = 1 / $interval;
			    $duration = ($counter * $interval) + 2; 
							
				# PROCESS IMAGES TO VIDEO
				$video_output = $this->process_pdf_image_to_video($frame_rate,$duration,$images_path,$save_location);
				
				# GETTING VIDEO DURRATION
				$this->phpffmpeg($save_location);
				
				# IF VIDEO HAS DURRATION												
				if($this->duration){
					$this->delete_junk($hash);
					return $random_filename;
				}else{
					return false;	
				}
			}
		}		
	}
	
	function process_doc_to_pdf($from_file_path,$to_file_path){
		# CONVERT MS TO PDF OPERATION
		$cmd = "java -jar " . Configure::read('Video.java_path') ." " . $from_file_path . " " . $to_file_path . " 2>&1";
		exec($cmd,$output);
		return $output;	
	}
	
	function process_pdf_to_images($pdf_path,$store_dir){
		# CONVERT PDF TO IMAGES OPERATION
		$images_path = $store_dir .	IMAGES_PREFIX . '%03d.jpg';
		$cmd = Configure::read('Video.imagemagick_path') . " -geometry 1600x1600 -density 300x300 ".$pdf_path." +adjoin -scene 1 ".$images_path." 2>&1";   
		exec($cmd,$output);
		return $output;
	}
	
	function image_fix($store_dir){
		# ASSUME THE IMAGES AND THE DIRECTORIES
		$from_copy = $store_dir .	IMAGES_PREFIX . '001.jpg';
		$to_copy = $store_dir .	IMAGES_PREFIX . '000.jpg';
		exec(Configure::read('Video.imagemagick_path') . " copy $from_copy $to_copy 2>&1",$output);
		return $output;
	}
	
	function process_pdf_image_to_video($frame_rate,$duration,$image_path,$video_path){
		# CONVERT IMAGES TO VIDEO OPERATION
		$cmd = Configure::read('Video.ffmpeg_path') ." -loop 1 -r $frame_rate -b:v 1800 -f image2 -i $image_path -r 64 -t $duration -y $video_path 2>&1";    
		exec($cmd,$output);
		return $output;
	}
	
	function process_image_to_video($frame_rate,$duration,$image_path,$video_path){
		# PROCESS OF VIDEOS CONVERSION
		$cmd = Configure::read('Video.ffmpeg_path') ." -loop 1 -f image2 -r $frame_rate -b:v 1800 -i $image_path -c:v libx264 -preset slow -tune stillimage -r 64 -t $duration -s 400x400 -y $video_path 2>&1";
		exec($cmd,$output);	
			
		return $output;
	}
		
	function process_odd_demension_pdf_to_images($pdf_path,$file_location){
		# GET TOTAL PDF PAGES
		$output = file_get_contents($pdf_path);
		$pdf_pages_count = preg_match_all("/\/Page\W/", $output, $dummy);
		
		# CONVERSION OF PDF TO IMAGES
		for($i=0; $i<$pdf_pages_count; $i++)
		{
			$j = $i+1;
			if(strlen($j) == 1){
				$prefix = IMAGES_PREFIX . '00';
			}else if(strlen($j) == 2){
				$prefix = IMAGES_PREFIX . '0';
			}else if(strlen($j) == 3){
				$prefix = IMAGES_PREFIX;
			}
			$im = new Imagick();
			$im->setResolution(300,300); 
			$im->readImage( $pdf_path .'[' . $i . ']' );
			$im->scaleImage(824,1024);	
			$im->setImageFormat('jpg'); 
			$im->writeImage( $file_location . $prefix . $j .'.jpg');
		}
	}
		
	function delete_junk($hash){
		# DELETE FOLDER AND DATABASE
		$file_location = PROCESS_DIR . $hash .'/';
		Controller::loadModel('Hash');
		$this->Hash->rrmdir($file_location);
		return $this->Hash->unset_hash($hash);	
	}
	
	function random_name($extension = null){
		$ext = !empty($extension) ? '.'.$extension : null;
		return md5(time()) . $ext;
	}
	
	function generate_thumb($video_path,$array_data,$time=1){
		if($video_path){
			# PARSE THE DATA 
			$uploaded_by = isset($array_data['uploaded_company']) ? $array_data['uploaded_company'] : null;
			$base_path = getcwd();
			$thumb_name = 'thumb_' . reset(explode('.',$video_path)) . '.jpg';
			$video_path = $base_path.'/files/'.$uploaded_by .'/'.$video_path;
			$thumb_path = $base_path.'/files/'.$uploaded_by .'/' . $thumb_name;	
			$cmd = Configure::read('Video.ffmpeg_path') ." -i $video_path -vframes 1 -s ".THUMBNAIL_DEMENSIONS." -ss $time -y $thumb_path 2>&1";
			exec($cmd,$output);	
									
			return $thumb_name;
		}
		return false;
	}
		
	function test(){
		return 'hello';	
	}
	
	function phpffmpeg( $file ) {
        
        // Global Array of Information
                $lOut = array();
        
                $cmd =  Configure::read('Video.ffmpeg_path'). " -i ".$file." 2>&1";
                exec($cmd, $lOut);
        
        // We are getting duration of movie
                $lDuration = $this->getLine( '/Duration: (.*?),/', $lOut );
        
                $lArray = explode(':', $lDuration[1]);
                $this->duration = intval( $lArray[0] * 3600 + $lArray[1] * 60 + round( $lArray[2] )); // round for not float
                $this->duration_raw = $lArray[0].":". $lArray[1] .":".round($lArray[2]); // raw txt format of time
        
        // Getting Bitrate
                $lBitrate =  $this->getLine( '/, bitrate: (\S+) kb\/s/', $lOut );
        
                $this->bitrate = intval($lBitrate[1]); // get only digits
        
        // Video information
                $lVideoinfo =  $this->getLine( '/Stream #\d+.\d+: Video: (\S+), \S+, (\d+)x(\d+) .*?\, (\S+) tbr/', $lOut );
        
                // Codec
                $this->vcodec = $lVideoinfo[1];
                $this->w = intval($lVideoinfo[2]);
                $this->h = intval($lVideoinfo[3]);
                $this->framerate = $lVideoinfo[4];
        
        
        // Audio information
                $lAudioinfo =  $this->getLine( '/Stream #\d+.\d+: Audio: (.*?), (.*?) Hz, (\S+).*?\, .*?\, (\S+) kb/', $lOut );
        
                $this->acodec = $lAudioinfo[1];
                $this->audiorate = $lAudioinfo[2];
                $this->channels = $lAudioinfo[3];
                $this->audiobitrate = $lAudioinfo[4];
        }
		
		 function getLine( $inMatch, &$inArray ) {
        
                reset($inArray);
                $fResult = array();
                $i = 0;
                while(($lArr = next($inArray)) !== false) {
                        if( preg_match($inMatch, $lArr, $fResult) ) {
                                return $fResult;
                        }
                        $i++;
                }
        
        // No matches found
                return 0;
        }
}