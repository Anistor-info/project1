<?php  
    class WebserviceController extends AppController{ 
                
       public function index(){
		   
		   //require_once("nuSOAP/lib/nusoap.php");
			App::import('Vendor', 'nusoap', array('file' => 'nusoap'.DS.'lib'.DS.'nusoap.php'));
		    
			$namespace = "http://localhost/outbackmedia_streaming/webservice";
			// create a new soap server
			$server = new soap_server();
			// configure our WSDL
			$server->configureWSDL("Outbackoperation");
			// set our namespace
			$server->wsdl->schemaTargetNamespace = $namespace;
			
			//Register a method that has parameters and return types
			$server->register(
							// method name:
							'Outback', 	
							// parameter list:
							array('name'=>'xsd:string'), 
							// return value(s):
							array('return'=>'xsd:string'),
							// namespace:
							$namespace,
							// soapaction: (use default)
							false,
							// style: rpc or document
							'rpc',
							// use: encoded or literal
							'encoded',
							// description: documentation for the method
							'Simple Hello World Method');
			
			//Register our method using the complex type
			$server->register('Authorize',
				array('username' => 'xsd:string','password' => 'xsd:string'),
				array('result' => 'xsd:string'),
				'xsd:demo');
			
			//Our Simple method
			function Outback($name)
			{
				return "Hello Outback media Soap api " . $name;
			}
			
			
			//Our complex method
			function Authorize($username,$password){
				if( ($username == 'ravi') and ($password == 123456) ){
					return 'successfull';
				}else{
					
				}
			}
			
			// Get our posted data if the service is being consumed
			// otherwise leave this data blank.                
			$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
							? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
			
			// pass our posted data (or nothing) to the soap service                    
			$server->service($POST_DATA);                
			exit();
	   }
	   
	   public function req(){
		   
		   $this->layout = false;
		   $this->autoRender = false;
			ini_set("soap.wsdl_cache_enabled", "0");
		    
		    //require_once("nuSOAP/lib/nusoap.php");
			App::import('Vendor', 'nusoap', array('file' => 'nusoap'.DS.'lib'.DS.'nusoap.php'));
		    
			$namespace = "http://localhost/outbackmedia_streaming/webservice/req";
			// create a new soap server
			$server = new soap_server();
			// configure our WSDL
			$server->configureWSDL("Outbackoperation");
			// set our namespace
			$server->wsdl->schemaTargetNamespace = $namespace;
			
			//Register a method that has parameters and return types
			$server->register(
							// method name:
							'Outback', 	
							// parameter list:
							array('name'=>'xsd:string'), 
							// return value(s):
							array('return'=>'xsd:string'),
							// namespace:
							$namespace,
							// soapaction: (use default)
							false,
							// style: rpc or document
							'rpc',
							// use: encoded or literal
							'encoded',
							// description: documentation for the method
							'Simple Hello World Method');
			
			//Register our method using the complex type
			$server->register('Authorize',
				array('username' => 'xsd:string','password' => 'xsd:string'),
				array('result' => 'xsd:string'),
				'xsd:demo');
			
			//Our Simple method
			function Outback($name)
			{
				return "Hello Outback media Soap api " . $name;
			}
			
			
			//Our complex method
			function Authorize($username,$password){
				if( ($username == 'ravi') and ($password == 123456) ){
					return 'successfull';
				}else{
					
				}
			}
			
			// Get our posted data if the service is being consumed
			// otherwise leave this data blank.                
			$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
							? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
			
			// pass our posted data (or nothing) to the soap service                    
			$server->service($POST_DATA);                
			exit();
	   }
	   
	   
	  
	  public function test(){		
		try
		{
		  $client = new SoapClient("http://localhost/outbackmedia_streaming/webservice/req?wsdl");
		  $response = $client->Outback('ravi');
		}
		catch (SoapFault $fault)
		{
			trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
		}
		
		echo "<pre>";
		print_r($result);
		exit;
	  }
	  
	  public function test1(){		
		try
		{
		  //$client = new SoapClient("http://localhost/outbackmedia_streaming/webservice/req?wsdl");
		  $client = new SoapClient("http://localhost/outbackmedia_streaming/app/webroot/webservice.php?wsdl");
		  $response = $client->Authorize('ravi',123456);
		}
		catch (SoapFault $fault)
		{
			trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
		}
		
		echo "<pre>";
		print_r($response);
		exit;
	  }
	  
    } 
	
	
?>