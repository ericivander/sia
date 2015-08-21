<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends MY_Controller {
	// Inser Data ke Database 
	public function create(){
		$data = $this->postData;
	}
	// Load data User
	public function getUser(){
		//load data from model mahasiswa
		$data = $this->postData;
		die(var_dump($this->model));
		$user = $this->getmahasiswa($data['$nrp']);
		$this->load->view('Mahasiswa', $user);
	}
}

?>