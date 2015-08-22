<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {
	// load halaman view
	public function index()
	{
		$this->load->view('Home');
	}
	public function home(){
		$user = array();
		//function authentication 
		//masuk halaman login
		$this->load->view('Home',$user);	
	}
	public function main(){
		$user = array();
		//function authentication 
		//masuk halaman login
		$this->load->view('Main',$user);	
	}
	public function profile(){
		$user = array();
		//function authentication 
		//masuk halaman login
		$this->load->view('Profile',$user);	
	}
	public function editmyprofile(){
		$user = array();
		//function authentication 
		//masuk halaman login
		$this->load->view('Editmyprofile',$user);	
	}
	public function setting(){
		$user = array();
		//function authentication 
		//masuk halaman login
		$this->load->view('Setting',$user);	
	}
}

?>