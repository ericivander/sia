<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends APP_Controller {
	// load halaman view
	public function login(){
		$user = array();
		//function authentication 
		//masuk halaman login
		$this->load->view('Login',$user);	
	}
}

?>