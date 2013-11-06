<?php 


class TestController extends AppController {
		
	public function index(){
		
		$abc = 'http://in.video.yahoo.com/entertainment-26099860/bollywood-26715902/glorious-100-years-of-cinema-29188839.html';
		$arr_ex = explode('/',$abc);
		print_r($arr_ex[count($arr_ex)-1]);
		exit;	
	}


	public function abcDef(){
	 echo "hi";
	 exit;	
	}
}