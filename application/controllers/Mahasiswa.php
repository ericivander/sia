<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends APP_Controller {
	// Inser Data ke Database 
	public function create(){
		$data = $this->postData;
	}
	// Load data User
	public function getUser(){
		//load data from model mahasiswa
		$user = array();
		$this->load->view('Mahasiswa', $user);
	}
}

?>