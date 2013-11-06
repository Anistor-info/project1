<?php

/*/**
 * 24 June 2012   Author Ravi Sharma (ravi.sharma@softobiz.com)
 * Api for internal use 
 */

# SOME CONFIGURATIONS 
include('config.php');
$namespace = URL."webservice.php";
$server = new soap_server();
$server->configureWSDL("Outbackoperation");
$server->wsdl->schemaTargetNamespace = $namespace;

# REGISTERTING SOME FUNCTIONS ON SOAP SERVER
$server->register('Outback',array('name'=>'xsd:string'),array('return'=>'xsd:string'),$namespace,false,'rpc','encoded','This method is used for testing purpose.');
$server->register('Authorize',array('username' => 'xsd:string','password' => 'xsd:string'),array('result' => 'xsd:string'),'xsd:Authorize');
$server->register('GetMonitors',array('token' => 'xsd:string'),array('result' => 'xsd:string'),'xsd:GetMonitors');
$server->register('GetMonitorTimeline',array('token' => 'xsd:string','monitor_id' => 'xsd:string'),array('result' => 'xsd:string'),'xsd:GetMonitorTimeline');

/*
/* Method outback
/* for testing purpose
/* @var name 
*/
function Outback($name)
{
	return "Hello" . $name ." this is Outback media Soap api ";
}

/*
/* Method Authorize
/* for authorize the user
/* @var username
/* @var password 
*/
function Authorize($username,$password){
	$xml = '';
	$user = new user();
	$user_data = $user->CheckUserExist($username,$password);
	if(isset($user_data['user_id'])){
		if($user_data['active_user_token']){
			$token = $user_data['active_user_token'];
		}else{
			$user_id = $user_data['user_id'];
			$token = $user->SetToken($user_id);
		}
		$xml .= '<?xml version="1.0"?>';
		$xml .= '<response>';
		$xml .= '<success>1</success>';
		$xml .= '<token>'.$token.'</token>';
		$xml .= '</response>';			
		return $xml;	
	}else{
		$xml .= '<?xml version="1.0"?>';
		$xml .= '<response>';
		$xml .= '<success>0</success>';
		$xml .= '<message>No token found</message>';
		$xml .= '</response>';
		return $xml;
	}		
}

/*
/* Method GetMonitors
/* for get user assigned monitors
/* @var token
*/
function GetMonitors($token){
	if($token){
		$user = new user();
		return $user->GetMonitors($token);
	}else{
		$xml  = '<?xml version="1.0"?>';
		$xml .= '<response>';
		$xml .= '<success>0</success>';
		$xml .= '<message>No token found</message>';
		$xml .= '</response>';
		return $xml;
	}
}

/*
/* Method GetMonitorTimeline
/* for get timeline for particular monitor
/* @var token
/* @var monitor id
*/
function GetMonitorTimeline($token,$monitor_id){
	if( !empty($token) or !empty($monitor_id)){
		$user = new user();
		return $user->GetMonitorTimeline($token,$monitor_id);
	}else{
		$xml  = '<?xml version="1.0"?>';
		$xml .= '<response>';
		$xml .= '<success>0</success>';
		$xml .= '<message>No token found</message>';
		$xml .= '</response>';
		return $xml;
	}
}

# PASS OUR POSTED DATA
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])
? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
$server->service($POST_DATA);
exit();


class user{
	
	/*
	/* constructor
	*/
	public function __construct(){
		session_start();
		$driver = DB_TYPE.'://'.DB_USER.':'.DB_PASSWORD.'@'.DB_HOST.'/'.DATABASE; 
		$this->Connect = ADONewConnection($driver); # eg. 'mysql' or 'oci8' 
		$this->Connect->debug = false;
		$this->Connect->Connect();
	}
	
	/*
	/* Method CheckUserExist
	/* @var username
	/* @var password
	*/
	public function CheckUserExist($username,$password){
		$rs = $this->Connect->Execute("select * from users where user_name = '".$username."' and password = '".md5($password)."'");
		$result = $rs->GetArray();
		return $result[0];	
	}
	
	/*
	/* Method SetToken
	/* @var user_id
	*/
	public function SetToken($user_id){
		$token = md5(rand(0,1000));
		$rs = $this->Connect->Execute("UPDATE users SET active_user_token = '".$token."' where user_id = '".$user_id."'");
		return $token;
	}
	
	/*
	/* Method GetMonitors
	/* @var token
	*/
	public function GetMonitors($token){
		
		if($user_id = $this->IsValidToken($token)){
			$data = $this->GetUserMonitor($user_id,$token);
			if(!empty($data)){
				$xml_data = $this->CreateMonitorXml($data);
				return $xml_data;
			}else{
				$xml  = '<?xml version="1.0"?>';
				$xml .= '<response>';
				$xml .= '<success>1</success>';
				$xml .= '<message>No monitor assigned to logined user</message>';
				$xml .= '<token>'.$token.'</token>';
				$xml .= '</response>';
				return $xml;
			}
		}else{
			$xml  = '<?xml version="1.0"?>';
			$xml .= '<response>';
			$xml .= '<success>0</success>';
			$xml .= '<message>No token found</message>';
			$xml .= '<token>'.$token.'</token>';
			$xml .= '</response>';
			return $xml;
		}
	}
	
	/*
	/* Method IsValidToken
	/* @var token
	*/
	public function IsValidToken($token){
		/*$rs = $this->Connect->Execute("SELECT `active_user_token`,users.user_id FROM `users` join assign_monitors where users.user_id = assign_monitors.user_id and users.active_user_token = '".$token."'");*/
		$rs = $this->Connect->Execute("SELECT users.user_id FROM `users` where users.active_user_token = '".$token."'");
		$result = $rs->GetArray();
		return $result[0]['user_id'];
	}
	
	/*
	/* Method IsValidUserAccess
	/* @var token
	/* @var monitor_id
	*/
	public function IsValidUserAccess($token,$monitor_id){
		$rs = $this->Connect->Execute("SELECT assign_monitors.monitor_id,`active_user_token`,users.user_id FROM `users` join assign_monitors where users.user_id = assign_monitors.user_id and users.active_user_token = '".$token."' and monitor_id = '".$monitor_id."'");
		$result = $rs->GetArray();
		return $result[0]['user_id'];
	}
	
	/*
	/* Method GetUserMonitor
	/* @var user_id
	*/
	public function GetUserMonitor($user_id,$token){
		$rs = $this->Connect->Execute("SELECT * FROM `assign_monitors` join monitors where assign_monitors.monitor_id = monitors.id and assign_monitors.user_id = '".$user_id."' and (monitors.monitor_token = '".$token."' or is_paired = 0)");
		$result = $rs->GetArray();
		return $result;
	}
	
	/*
	/* Method GetUserMonitor
	/* @var user_id
	*/
	public function CreateMonitorXml($array){
		$xml = '<?xml version="1.0"?>';
		$xml .= '<response>';
		$xml .= '<success>1</success>';
		for($i=0;$i<count($array);$i++){
			$xml .=	'<monitor id = "'.$array[$i]['id'].'">';
			$xml .=	'<monitor_name>'.$array[$i]['name'].'</monitor_name>';
			$xml .=	'<monitor_location>'.$array[$i]['location'].'</monitor_location>';
			$xml .=	'<monitor_token>'.$array[$i]['monitor_token'].'</monitor_token>';
			$xml .= '</monitor>';
		}
		$xml .= '</response>';
		return $xml;
	}
	
	/*
	/* Method GetMonitorTimeline
	/* @var token
	/* @monitor_id
	*/
	public function GetMonitorTimeline($token,$monitor_id){
			# CROSS CHECK THE TOKEN
			if($user_id = $this->IsValidUserAccess($token,$monitor_id)){
			}else{
				$xml  = '<?xml version="1.0"?>';
				$xml .= '<response>';
				$xml .= '<success>0</success>';
				$xml .= '<message>Invalid monitor access</message>';
				$xml .= '<token>'.$token.'</token>';
				$xml .= '<monitor>'.$monitor_id.'</monitor>';
				$xml .= '</response>';
				return $xml;
			}
			
			$dbtoken = $this->PairMonitor($monitor_id,$token);
			if($dbtoken == $token){
				$data = $this->GetMonitorsTimeline($monitor_id);
				if(!empty($data)){
					$xml_data = $this->CreateTimelineXml($data);
					return $xml_data;
				}else{
					$xml  = '<?xml version="1.0"?>';
					$xml .= '<response>';
					$xml .= '<success>1</success>';
					$xml .= '<message>No timeline for this monitor</message>';
					$xml .= '<monitor>'.$monitor_id.'</monitor>';
					$xml .= '</response>';
					return $xml;
				}
			}else{
				$xml  = '<?xml version="1.0"?>';
				$xml .= '<response>';
				$xml .= '<success>0</success>';
				$xml .= '<message>Not valid token</message>';
				$xml .= '<token>'.$token.'</token>';
				$xml .= '</response>';
				return $xml;
			}
	}
	
	/*
	/* Method GetMonitorsTimeline
	/* @monitor_id
	*/
	public function GetMonitorsTimeline($monitor_id){
		$rs = $this->Connect->Execute("SELECT *,timelines.id as tid FROM `timelines` join videos where timelines.video_id = videos.id and timelines.monitor_id = '".$monitor_id."'");
		$result = $rs->GetArray();
		return $result;
	}
	
	/*
	/* Method GetMonitorsTimeline
	/* @monitor_id
	*/
	public function CreateTimelineXml($array){
		$xml = '<?xml version="1.0"?>';
		$xml .= '<response>';
		$xml .= '<success>1</success>';
		for($i=0;$i<count($array);$i++){
			$xml .=	'<video id = "'.$array[$i]['video_id'].'">';
			$xml .=	'<order>'.$array[$i]['order'].'</order>';
			$xml .=	'<name>'.$array[$i]['name'].'</name>';
			$xml .=	'<duration>'.$array[$i]['duration'].'</duration>';
			$xml .=	'<uploaded_on>'.$array[$i]['uploaded_on'].'</uploaded_on>';
			$xml .=	'<path>'.URL.USER_UPLOAD_DIR.$array[$i]['uploaded_company'].'/'.$array[$i]['path'].'</path>'; 
			$xml .=	'<thumbnail>'.URL.USER_UPLOAD_DIR.$array[$i]['uploaded_company'].'/'.$array[$i]['thumbnail'].'</thumbnail>'; 
			$xml .=	'<timestamp>'.$array[$i]['timestamp'].'</timestamp>'; 
			$xml .= '</video>';
		}
		$xml .= '</response>';
		return $xml;
	}
	
	/*
	/* Method PairMonitor
	/* For pair the device with monitor
	/* @var monitor_id
	/* @var token
	*/
	public function PairMonitor($monitor_id,$token){
		
		if($monitor_token = $this->CheckPair($monitor_id)){
			return $monitor_token;
		}else{
			$rs = $this->Connect->Execute("UPDATE `monitors` SET monitor_token = '".$token."',is_paired = 1 where id = '".$monitor_id."'");
			return $token; 
		}
	}
	
	/*
	/* Method CheckPair
	/* For check wheather monitor is already paired 
	/* @var monitor_id
	*/
	public function CheckPair($monitor_id){
		$rs = $this->Connect->Execute("SELECT * FROM `monitors` where id = '".$monitor_id."' and is_paired != 0");
		$result = $rs->GetArray();
		return isset($result[0]['monitor_token']) ? $result[0]['monitor_token'] : false;
	}
}

?>