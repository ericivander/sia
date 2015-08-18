<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pasangan extends MY_Model {
	public $primary_key='nrp';
	protected $belongs_to = array('Mahasiswa','primary_key'=>'nrp');
}